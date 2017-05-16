<?php

namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\User;
use \app\models\Token;

class ResendForm extends Model
{
    public $email;

    private $_user = false;
    
    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'email'],
            [
                'email', 'exist',
                'targetClass' => User::className(),
                'message' => 'Sorry, that person hasn\'t registered yet'
            ],
            ['email', 'checkConfirm'],
        ];
    }

    /**
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function checkConfirm($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if ($user && $user->getIsConfirmed()) {
                $this->addError('email', 'This account has already been confirmed');
            }
        }
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
        ];
    }

    /**
     * Creates new confirmation token and sends it to the user.
     *
     * @return bool
     */
    public function resendConfirmation()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $token = Token::findByUser($user->id, Token::TYPE_CONFIRMATION);
            if ($token) {
                $token->type = Token::TYPE_CONFIRMATION;
                if ($token->save() == false) {
                    return false;
                }
            } else {
                $token = new Token();
                $token->user_id = $user->id;
                $token->type = Token::TYPE_CONFIRMATION;
                if (!$token->save()) {
                    return false;
                }
            }
            if (Yii::$app->message->sendReconfirmationMessage($user, $token)) {
                return true;
            }
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
