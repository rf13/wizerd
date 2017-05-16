<?php

namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\User;
use app\models\Token;
use app\components\MessageComponent;

/**
 * RecoveryForm is the model behind the recovery form.
 * @property MessageComponent $message
 */
class RecoveryForm extends Model
{
    const SCENARIO_FORGOT = 'forgot';
    const SCENARIO_RESET = 'reset';

    public $email;
    public $password;

    private $_user = false;

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'recovery-form';
    }

    /** @inheritdoc */
    public function scenarios()
    {
        return [
            self::SCENARIO_FORGOT => ['email'],
            self::SCENARIO_RESET  => ['password'],
        ];
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            [
                'email', 'exist',
                'targetClass' => User::className(),
                'message' => 'There is no user with this email address',
            ],
            ['email', 'checkConfirmed'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6, 'max' => 16],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'email'    => 'Email',
            'password' => 'Password',
        ];
    }

    /**
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function checkConfirmed($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) {
                $this->addError($attribute, 'User not found');
            } else if (!$user->getIsConfirmed()) {
                $this->addError($attribute, 'You need to confirm your email address');
            }
        }
    }

    /**
     * @return bool
     */
    public function sendRecoveryMessage()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $token = new Token();
            $token->user_id = $user->id;
            $token->type = Token::TYPE_RECOVERY;
            if ($token->save(false)) {
                return Yii::$app->message->sendRecoveryMessage($user, $token);
            }
        }
        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function resetPassword(User $user)
    {
        if ($this->validate()) {
            if ($user->resetPassword($this->password)) return true;
        }
        return false;
    }

    /**
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
