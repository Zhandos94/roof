<?php
$params = array_merge(
	require(__DIR__ . '/../../common/config/params.php'),
	require(__DIR__ . '/../../common/config/params-local.php'),
	require(__DIR__ . '/params.php'),
	require(__DIR__ . '/params-local.php')
);

return [
	'language' => 'ru-RU',
	'id' => 'app-frontend',
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log'],
	'controllerNamespace' => 'frontend\controllers',
    'defaultRoute' => 'main',
	'modules' => [
        'main' => [
            'class' => 'frontend\modules\main\Module',
        ],
        'cabinet' => [
            'class' => 'frontend\modules\cabinet\Main',
        ],


	],

	'components' => [
        'mail' => [
            'class'            => 'zyx\phpmailer\Mailer',
            'viewPath'         => '@common/mail',
            'useFileTransport' => false,
            'config'           => [
                'mailer'     => 'smtp',
                'host'       => 'smtp.yandex.ru',
                'port'       => '465',
                'smtpsecure' => 'ssl',
                'smtpauth'   => true,
                'username'   => 'zhumagali.zh@yandex.ru',
                'password'   => 'YandeXZhandos94',
                'ishtml' => true,
                'charset' => 'UTF-8',
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'zh.zhumagaly@gmail.com',
                'password' => 'SamsunG1994',
                'port' => '587',
//                'username'   => 'zhumagali.zh@yandex.ru',
//                'password'   => 'YandeXZhandos94',
//                'port'       => '465',
                'encryption' => 'tls',
            ],
        ],


        'common' => [
            'class' => 'frontend\components\Common',
        ],

        'formatter' => [
            'dateFormat' => 'dd-MM-yyyy',
        ],
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
		'user' => [
			'identityClass' => 'common\models\User',
			'enableAutoLogin' => true,
			'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
		],
		'session' => [
			// this is the name of the session cookie used for login on the frontend
			'name' => 'advanced-frontend',
		],
		'log' => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error'],
					'categories' => ['comment_log'],
					'logFile' => '@runtime/logs/comment.log',
					'logVars' => [],
				],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                    'categories' => ['advert'],
                    'logFile' => '@runtime/logs/advert.log',
                    'logVars' => [],
                ],
			],
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
//            'errorView' => '@yii/views/errorHandler/error.php',
//            'exceptionView' => '@frontend/'
		],

		'urlManager' => [
			'enableStrictParsing' => false,
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => [
				'companies' => 'companies/com-bank-req/index',
				'company/<com_id:\d+>' => 'companies/com-additional/com-view',
				'news/<id:\d+>' => 'news/default/view',
				'articles/<id:\d+>' => 'articles/default/view',
				'articles/<slug:[\w-]+>' => 'articles/default/index',
				'news/<slug:[\w-]+>' => 'news/default/index',
			]
		],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
	],
	'params' => $params,
];
