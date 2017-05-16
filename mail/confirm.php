<?php
/**
 * @var app\models\User $user
 * @var app\models\Token $token
 */
use yii\helpers\Html;

?>
<p>
    Hello,
</p>
<p>
    Thank's for signing up. To confirm your account, please click the link below:
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
