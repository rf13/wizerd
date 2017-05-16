<?php

define('ENVIRONMENT', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
if (ENVIRONMENT != 'production') {
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_ENV') or define('YII_ENV', 'dev');
}

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
Yii::$classMap['OAuthToken'] = __DIR__ . '/../libs/OAuth.php';
Yii::$classMap['OAuthConsumer'] = __DIR__ . '/../libs/OAuth.php';
Yii::$classMap['OAuthSignatureMethod_HMAC_SHA1'] = __DIR__ . '/../libs/OAuth.php';
Yii::$classMap['OAuthRequest'] = __DIR__ . '/../libs/OAuth.php';
Yii::$classMap['OAuthToken'] = __DIR__ . '/../libs/OAuth.php';

Yii::$classMap['yii\base\ArrayableTrait'] = __DIR__ . '/../components/ArrayableTrait.php';
Yii::$classMap['yii\helpers\ArrayHelper'] = __DIR__ . '/../components/ArrayHelper.php';

(new yii\web\Application($config))->run();
