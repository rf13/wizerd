<?php

namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\User;
use app\models\Token;

/**
 * ProfileSettingsForm is the model for change account's settings.
 */
class ProfileSettingsForm extends Model
{
    const SCENARIO_BUSINESS = 'business';
    const SCENARIO_CONSUMER = 'consumer';

    public $is_consumer;
    public $first_name;
    public $last_name;

    public $current_email;
    public $new_email;
    public $confirm_email;
    public $password;
    public $new_password;
    public $confirm_password;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name'], 'required', 'on' => self::SCENARIO_CONSUMER],
            [['first_name', 'last_name'], 'string', 'on' => self::SCENARIO_CONSUMER],
            [['new_email', 'current_email','confirm_email'], 'email', 'message' => 'This is not a valid email address.'],
            [['new_email', 'current_email'],'validateEmail'],

            ['confirm_email', 'compare', 'compareAttribute' => 'current_email', 'message' => 'These emails don’t match. Please try again.'],
          //  ['current_email', 'compare', 'compareAttribute' => 'confirm_email'],
            [['new_password', 'confirm_password', 'password'], 'string', 'min' => 8, 'max' => 16, 'tooShort' => 'Password must contain at least {min} characters.', 'tooLong' => 'Password must contain at most {max} characters.'],

           // ['new_password', 'compare', 'compareAttribute' => 'confirm_password'],

            ['confirm_password', 'compare', 'compareAttribute' => 'new_password', 'message' => 'These passwords don’t match. Please try again.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'confirm_email'          => 'Confirm email'
        ];
    }

    public function validateEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) {
                $this->addError($attribute, 'Access error!');
            } else {
               /* if ($user->email == $this->current_email) {
                    $this->addError($attribute, 'This coincides with the current email.');
                }
               */
            }
            if (User::find()->where('email=:email and id!=:id')->addParams([':email' => $this->current_email,':id'=>$user->id])->count() > 0) {
                $this->addError($attribute, 'Sorry, this email was already registered');
            }
        }
    }

    public function loadDefaultValues()
    {
        if ($this->is_consumer) {
            $user = $this->getUser();
            $user = $user->getConsumer();
            $this->first_name = $user->first_name;
            $this->last_name = $user->last_name;
        }
    }

    /**
     * @return boolean whether the settings was saved
     */
    public function changeSettings()
    {
        if ($this->validate()) {
            $change = false;
            $user = $this->getUser();
            if ($this->is_consumer) {
                $consumer = $user->getConsumer();
                $consumer->nickname = $this->first_name . ' ' . $this->last_name;
                $consumer->first_name = $this->first_name;
                $consumer->last_name = $this->last_name;
                $consumer->update();
            }
            if (empty($this->current_email) && empty($this->password)) {
                return true;
            }
            $success = 'The account settings were updated successfully';
            if (!empty($this->confirm_email) && ($this->current_email == $this->confirm_email)) {
                $change = true;
                $user->email = $this->current_email;
                $user->is_email_verified = 0;
                $user->verification_time = null;
                $token = new Token();
                $token->user_id = $user->id;
                $token->type = Token::TYPE_CONFIRMATION;
                if ($token->save(false)) {
                    if (Yii::$app->message->sendConfirmationMessage($user, $token)) {
                        $success .= ' and a message with further instructions has been sent to your email';
                    }
                }
            }

            // TODO: fix that. doesn't work properly
            if (!empty($this->new_password) && ($this->new_password == $this->confirm_password)) {
                if (!$user->validatePassword($this->password)) {
                    Yii::$app->session->setFlash(
                        'error',
                        'Please type correct current password'
                    );
                    return false;
                }
                $change = true;
                $user->generatePassword($this->new_password);
            }
            if ($change) {
                if (!$user->save(false)) {
                    Yii::$app->session->setFlash(
                        'error',
                        'An error occurred while change settings'
                    );
                    return false;
                }
                Yii::$app->session->setFlash('success', $success);
                return true;
            }
        }
        return false;
    }

    /**
     * Get current user
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findIdentity(Yii::$app->user->id);
        }

        return $this->_user;
    }
}