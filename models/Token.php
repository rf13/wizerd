<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Url;

/**
 * This is the model class for table "token".
 *
 * @property integer $user_id
 * @property string $code
 * @property integer $type
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class Token extends ActiveRecord
{
    const TYPE_CONFIRMATION = 0;
    const TYPE_RECOVERY = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'token';
    }

    /**
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return ['user_id', 'code', 'type'];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->setAttribute('code', Yii::$app->security->generateRandomString());
        }
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'code', 'type', 'created_at'], 'required'],
            [['user_id', 'type', 'created_at', 'updated_at'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['code'], 'string', 'max' => 32],
            [
                ['user_id', 'code', 'type'], 'unique',
                'targetAttribute' => ['user_id', 'code', 'type'],
                'message' => 'The combination of User ID, Code and Type has already been taken.'
            ],
            [
                ['user_id'], 'exist', 'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['user_id' => 'id']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User',
            'code' => 'Code',
            'type' => 'Type',
            'created_at' => 'Created',
            'updated_at' => 'Updated',
        ];
    }

    /**
     * Finds token by user
     *
     * @param  int $user User ID
     * @param  int $type Token type
     * @return null|Token
     */
    public static function findByUser($user, $type)
    {
        $types = array(self::TYPE_CONFIRMATION, self::TYPE_RECOVERY);
        if (!in_array($type, $types)) {
            throw new \RuntimeException();
        }
        return static::findOne([
            'user_id' => $user,
            'type' => $type
        ]);
    }

    /**
     * Finds token by code
     *
     * @param  int $user User ID
     * @param  string $code Code validation
     * @param  int $type Token type
     * @return null|Token
     */
    public static function findByCode($user, $code, $type)
    {
        $types = array(self::TYPE_CONFIRMATION, self::TYPE_RECOVERY);
        if (!in_array($type, $types)) {
            throw new \RuntimeException();
        }
        return static::findOne([
            'user_id' => $user,
            'code' => $code,
            'type' => $type
        ]);
    }

    /**
     * @return null|User
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return string URL
     */
    public function getUrl()
    {
        switch ($this->type) {
            case self::TYPE_CONFIRMATION:
                $route = 'user/confirm';
                break;
            case self::TYPE_RECOVERY:
                $route = 'user/reset';
                break;
            default:
                throw new \RuntimeException();
        }
        return Url::to([$route, 'id' => $this->user_id, 'code' => $this->code], true);
    }

    /**
     * @return bool Whether token has expired.
     */
    public function getIsExpired()
    {
        $expirationTime = $this->created_at + 3600*24;
        return ($expirationTime < time());
    }
}
