<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property integer $is_email_verified
 * @property integer $verification_time
 * @property string $auth_key
 *
 * @property Business $business
 * @property Consumer $consumer
 */
class User extends ActiveRecord implements IdentityInterface
{
    const ROLE_CONS = 'consumer';
    const ROLE_BUSN = 'business';
    const ROLE_ADMIN = 'admin';
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_REGISTER = 'register';

    public static function tableName()
    {
        return 'user';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'password' => 'Password',
            'is_email_verified' => 'Is verified',
            'verification_time' => 'Verification time',
            'auth_key' => 'Auth key'
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_LOGIN] = [
            'username',
            'password'
        ];
        $scenarios[self::SCENARIO_REGISTER] = [
            'username',
            'email',
            'password'
        ];

        return $scenarios;
    }

    public function rules()
    {
        return [
            [
                [
                    'email',
                    'password'
                ],
                'required'
            ],
            [
                [
                    'is_email_verified',
                    'verification_time'
                ],
                'integer'
            ],
            [
                [
                    'email',
                    'password',
                    'auth_key'
                ],
                'string',
                'max' => 255
            ],
            [
                'email',
                'unique'
            ]
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = Yii::$app->security->generateRandomString();
            }

            return true;
        }

        return false;
    }

    public function afterDelete()
    {
        parent::afterDelete();
        Yii::$app->db->createCommand()
            ->delete('auth_assignment', 'user_id = ' . $this->id)
            ->execute();
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return User|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @param string $type the type of token
     * @return IdentityInterface|null the identity object that matches the given token.
     * @throws NotSupportedException if the method is not implemented.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('Method "' . __CLASS__ . '::' . __METHOD__ . '" is not implemented.');
    }

    /**
     * Finds user by email
     *
     * @param  string $email
     * @return null|ActiveRecord
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function generatePassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Resets password.
     * @param string $password
     * @return bool
     */
    public function resetPassword($password)
    {
        return (bool)$this->updateAttributes([
            'password' => Yii::$app->security->generatePasswordHash($password)
        ]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @return bool Whether the user is confirmed or not.
     */
    public function getIsConfirmed()
    {
        return $this->is_email_verified != 0;
    }

    /**
     * @param int $id User id
     * @param string $code Validation code for confirm email
     * @return bool
     */
    public function checkConfirmCode($id, $code)
    {
        $token = Token::findByCode($id, $code, Token::TYPE_CONFIRMATION);
        if ($token instanceof Token) {
            if ($token->getIsExpired()) {
                $result = false;
                $message = 'The confirmation link is expired. Please try requesting a new one.';
            } else {
                $user = User::findIdentity($id);
                $result = $user->confirmAccount();
                if ($result) {
                    Yii::$app->user->login($user, 3600 * 24 * 30);
                    //$message = 'Thank you, registration is now complete.';
                } else {
                    $message = 'Something went wrong and your account has not been confirmed.';
                }
            }
            $token->delete();
        } else {
            $result = false;
            $message = 'The confirmation link is invalid. Please try requesting a new one.';
        }
        if (isset($message)) {
            Yii::$app->session->setFlash($result
                ? 'success'
                : 'danger', $message);
        }

        return $result;
    }

    /**
     * @param int $id User id
     * @param string $code Validation code for reset password
     * @return bool
     */
    public function checkResetCode($id, $code)
    {
        $token = Token::findByCode($id, $code, Token::TYPE_RECOVERY);
        if ($token instanceof Token) {
            if ($token->getIsExpired()) {
                Yii::$app->session->setFlash('danger', 'The reset link is expired.');

                return false;
            }
            $token->delete();
        } else {
            Yii::$app->session->setFlash('danger', 'The reset link is invalid.');

            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function confirmAccount()
    {
        return (bool)$this->updateAttributes([
            'is_email_verified' => 1,
            'verification_time' => time()
        ]);
    }

    /**
     * @return null|Consumer
     */
    public function getConsumer()
    {
        return Consumer::findOne(['user_id' => $this->getPrimaryKey()]);
    }

    /**
     * @return null|Business
     */
    public function getBusiness()
    {
        return Business::findOne(['user_id' => $this->getPrimaryKey()]);
    }

    /**
     * @return array|null
     */
    public function getBusinessAccount()
    {
        $busQuery = (new Query())->select([
            'id',
            'phone',
            'yelp_url',
            'vanity_name'
        ])
            ->from('business')
            ->where('user_id=:id')
            ->addParams([':id' => $this->getPrimaryKey()]);
        $hourQuery = (new Query())->select('COUNT(*)')
            ->from('operation')
            ->where('bus_id=bus.id');
        $menuQuery = (new Query())->select('COUNT(*)')
            ->from('menu')
            ->where('bus_id=bus.id');
        $photoQuery = (new Query())->select('COUNT(*)')
            ->from('photo')
            ->where('bus_id=bus.id and main>0 and main<=2');
        $staffQuery = (new Query())->select('COUNT(*)')
            ->from('staff')
            ->where('bus_id=bus.id');

        return (new Query())->select([
            'phone',
            'vanity_name',
            //'yelp_url',
            //'staff' => $staffQuery,
            'operation' => $hourQuery,
            'menu' => $menuQuery,
            'photo' => $photoQuery
        ])
            ->from(['bus' => $busQuery])
            ->one();
    }

    /**
     * @return Business|Consumer|string|null
     */
    public function getAccountByRole()
    {
        if (!Yii::$app->user->isGuest) {
            $roles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);
            foreach ($roles as $role => $obj) {
                if ($role == self::ROLE_CONS) {
                    $user = $this->getConsumer();

                    return $user;
                }
                if ($role == self::ROLE_BUSN) {
                    $user = $this->getBusiness();

                    return $user;
                }
                if ($role == self::ROLE_ADMIN) {
                    return self::ROLE_ADMIN;
                }
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        if (Yii::$app->user->isGuest) {
            return '';
        } else {
            $user = $this->getAccountByRole();
            if ($user === self::ROLE_ADMIN) {
                return 'Admin';
            }
            if ($user instanceof Business) {
                return $user->getAttribute('name');
            }
            if ($user instanceof Consumer) {
                return $user->getAttribute('nickname');
            }
        }
    }

    public function getSavedTiers()
    {
        /*  return WishList::find()
                  ->where('user_id=:user_id',['user_id'=>$this->consumer->id])
                  ->all();
          */
        return (new Query())->select('tier.id as id')
            ->from('wish_list')
            ->leftJoin('tier', 'wish_list.tier_id=tier.id')
            ->where('wish_list.user_id=:user_id', ['user_id' => $this->consumer->id])
            ->all();

    }

    public function getSavedTiersData()
    {
        return (new Query())->select('
                tier.id as id,
                business.id as business_id,
                business.name,
                business.address,
                business.phone,
                business.vanity_name,
                business.is_home,
                business.suite,
                business.website,
                business.contact_email,
                zip_code.zip,
                city.name as city_name,
                state.code as state_code,
                wish_list.id as wl_id,
                wish_list.standart_price,
                wish_list.promo_price,
                wish_list.promo_cat_title,
                wish_list.promo_srv_title,
                promo.end,
                promo.terms
            ')
            ->from('wish_list')
            ->leftJoin('tier', 'wish_list.tier_id=tier.id')
            ->leftJoin('promo', 'wish_list.promo_id = promo.id')
            ->leftJoin('business', 'promo.bus_id = business.id')
            ->leftJoin('zip_code', 'business.zip_id = zip_code.id')
            ->leftJoin('city', 'zip_code.city_id = city.id')
            ->leftJoin('state', 'city.state_id = state.id')
            ->where('wish_list.user_id=:user_id', ['user_id' => $this->consumer->id])
            ->all();
    }

    public static function getSearchLimit($ip)
    {

        return false;// mean can make search


    }

}
