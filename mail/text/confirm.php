<?php
/**
 * @var app\models\User   $user
 * @var app\models\Token  $token
 */
?>
Hello, <?= $user->getUsername() ?>

Thank you for signing up on <?= Yii::$app->name ?>.
In order to complete your registration, please click the link below

<?= $token->getUrl() ?>

If you cannot click the link, please try pasting the text into your browser.
If you did not make this request you can ignore this email.