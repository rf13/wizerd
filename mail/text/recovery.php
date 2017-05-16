<?php
/**
 * @var app\models\User   $user
 * @var app\models\Token  $token
 */
?>
Hello, <?= $user->getUsername() ?>

We have received a request to reset the password for your account on <?= Yii::$app->name ?>.
Please click the link below to complete your password reset

<?= $token->getUrl() ?>

If you cannot click the link, please try pasting the text into your browser.
If you did not make this request you can ignore this email.