<?php

namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;
    public $is_mobile;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required', 'message' => '{attribute} can’t be blank.'],
            ['email', 'email', 'message' => 'This is not a valid email address.'],
            [
                'email', 'exist',
                'targetClass' => User::className(),
                'message' => 'Sorry, that person hasn\'t registered yet'
            ],
            ['email', 'validateConfirmation'],
            ['password', 'validatePassword'],
            ['rememberMe', 'boolean'],
            //['is_mobile', 'validateBusinessOnMobile'],
        ];
    }

    public function validateBusinessOnMobile($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || ($this->$attribute && $user->getBusinessAccount())) {
                $error = "Business accounts can't be setup on a mobile device. Please log in on a desktop or tablet device to access your business account.";
                $this->addError($attribute, $error);
            }
        }
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'password' => 'Password',
            'rememberMe' => 'Remember me'
        ];
    }

    /**
     * Check the email confirmation.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateConfirmation($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->getIsConfirmed()) {
                $error = 'You need to confirm your email address before signing in. ';
                $error .= Html::a('Resend email confirmation now.', Url::toRoute('user/resend'));
                $this->addError($attribute, $error);
            }
        }
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect email or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided email and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }
}
