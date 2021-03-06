<?php
/**
 * @var app\models\User   $user
 * @var app\models\Token  $token
 */
use yii\helpers\Html;
?>
<p>
    Hello,
</p>
<p>
    We have received a request to reset the password for your Wizerd account. Please click the link below to complete your password reset. 
</p>
<p>
    <?= Html::a(Html::encode($token->getUrl()), $token->getUrl()); ?>
</p>
<p>
    If you can't click the link, then try pasting the text into your browser.
</p>
<p>
    If you did not make this request then you can ignore this email.
</p>
<p>
    Thanks,<br/>
    Wizerd
</p>
