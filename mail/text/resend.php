<?php
/**
 * @var app\models\User   $user
 * @var app\models\Token  $token
 */
?>
Hello, <?= $user->getUsername() ?>

We have received a request to change the email address for your account on <?= Yii::$app->name ?>.
In order to complete your request, please click the link below

<?= $token->getUrl() ?>

If you cannot click the link, please try pasting the text into your browser.
If you did not make this request you can ignore this email.