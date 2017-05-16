<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'name' => 'Wizzerd',

    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
        'api' => [
            'class' => 'app\modules\api\Module',
            'modules' => [
                'v1' => ['class' => 'app\modules\api\modules\v1\Module'],
            ],
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'y8QCJ0RZKGfi1aQEepIic-bug4zK9twl',
            'baseUrl' => '',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['user/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.zoho.com',
                'username' => 'info@wizerd.com',
                'password' => 'jdY73ju6kq',
                'port' => '587',
                'encryption' => 'tls',
            ],
        ],
        'alert' => [
            'class' => 'app\components\AlertComponent',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG
                ? 3
                : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => [
                        'error',
                        'warning'
                    ],
                ],
            ],
        ],
        'oauth' => [
            'class' => 'app\components\OAuth',
        ],
        'message' => [
            'class' => 'app\components\MessageComponent',
        ],
        'tip' => [
            'class' => 'app\components\TipComponent',
        ],
        'db' => require(__DIR__ . '/db.php'),
        'mutex' => [
            'class' => 'yii\mutex\MysqlMutex',
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'log-in' => 'user/login',
                'recovery' => 'user/recovery',
                'sign-up' => 'user/register',
                'reconfirmation' => 'user/resend',
                'logout' => 'user/logout',
                'support' => 'site/faq',
                'contact' => 'site/contact',
                'about' => 'site/about',
                'privacy' => 'site/privacy',
                'terms' => 'site/terms',
                'sales' => 'site/sales',
                'business-support' => 'site/business-support',
                'business-welcome' => 'site/business-welcome',
                'how-it-works' => 'site/instruction',
                'business-no-access' => 'site/business-no-access',
                '<module:admin>/<controller:\w+>' => '<module>/<controller>/index',
                '<module:admin>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
                '<controller:(user|site|account)>/<action>' => '<controller>/<action>',
                [
                    'class' => 'app\components\MainUrlRule',
                    'connectionID' => 'db',
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'controller' => [
                        //'api/v1/user',
                        'api/v1/service',
                        'api/v1/business',
                    ],
                    'extraPatterns' => [
                        'GET search' => 'search',
                    ],
                ],
            ],
        ],
        'assetManager' => [
            'basePath' => '@webroot/assets',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
