<?php
namespace app\components;

use Yii;
use yii\base\Component;
use app\models\User;
use app\models\Token;

class MessageComponent extends Component
{
    public $textPath;

    public function init()
    {
        parent::init();
        $this->textPath = 'text/';
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $view
     * @param array $params
     *
     * @return bool
     */
    protected function sendMessage($to, $subject, $view, array $params = [])
    {
        $mailer = Yii::$app->mailer;
        $message = $mailer->compose([
            'html' => $view,
            'text' => $this->textPath . $view
        ], $params);

        return $message->setTo($to)
            ->setFrom([Yii::$app->params['infoEmail'] => 'Wizerd'])
            ->setSubject($subject)
            ->send();
    }

    /**
     * Sends an email to a user with confirmation link after registration.
     *
     * @param User $user
     * @param Token $token
     *
     * @return bool
     */
    public function sendConfirmationMessage(User $user, Token $token)
    {
        return $this->sendMessage($user->email, 'Confirm your Wizerd account', 'confirm', [
                'user' => $user,
                'token' => $token
            ]);
    }

    /**
     * Sends an email to a user with reconfirmation link.
     *
     * @param User $user
     * @param Token $token
     *
     * @return bool
     */
    public function sendReconfirmationMessage(User $user, Token $token)
    {
        return $this->sendMessage($user->email, 'Confirm your Wizerd account', 'resend', [
                'user' => $user,
                'token' => $token
            ]);
    }

    /**
     * Sends an email to a user with recovery link.
     *
     * @param User $user
     * @param Token $token
     *
     * @return bool
     */
    public function sendRecoveryMessage(User $user, Token $token)
    {
        return $this->sendMessage($user->email, 'Complete your Wizerd password reset', 'recovery', [
                'user' => $user,
                'token' => $token
            ]);
    }
}
