<?php
$params = array_merge(
	require(__DIR__ . '/../../common/config/params.php'),
	require(__DIR__ . '/../../common/config/params-local.php'),
	require(__DIR__ . '/params.php'),
	require(__DIR__ . '/params-local.php')
);

return [
	'id' => 'app-backend',
	'basePath' => dirname(__DIR__),
	'controllerNamespace' => 'backend\controllers',
	'bootstrap' => ['log'],

	'modules' => [
		'translate' => [
			'class' => 'backend\modules\translate\Module',
			'defaultRoute' => 'source-message'
		],
		'gridview' =>  [
			'class' => '\kartik\grid\Module'
		],
		'helps' => [
			'class' => 'backend\modules\helps\Module',
		],
	],
	'components' => [
		'request' => [
			'csrfParam' => '_csrf-backend',
		],
		'user' => [
			'identityClass' => 'common\models\User',
			'enableAutoLogin' => true,
			'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
		],
		'session' => [
			// this is the name of the session cookie used for login on the backend
			'name' => 'advanced-backend',
		],
		'log' => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                    'categories' => ['message_log'],
                    'logFile' => '@runtime/logs/GJsMessageISM.log',
                    'logVars' => [],
                ],
			],
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'urlManager' => [
			'enableStrictParsing' => false,
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => [

			]
		],

// 		'i18n' => Zelenin\yii\modules\I18n\Module::className(),
// 		'admin' => [
// 			'class' => 'mdm\admin\Module',
// 			'layout' => 'left-menu', // avaliable value 'left-menu', 'right-menu' and 'top-menu'
// 			'controllerMap' => [
// 				'assignment' => [
// 					'class' => 'mdm\admin\controllers\AssignmentController',
// 					'userClassName' => 'common\models\User',
// 					'idField' => 'user_id'
// 				]
// 			],
// 			'menus' => [
// 				'assignment' => [
// 					'label' => 'Grand Access' // change label
// 				],
//                    'route' => null, // disable menu
// 			],
// 		],
	],
	'params' => $params,
];
