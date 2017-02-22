<?php
return [
    'name' => 'Advert Project',
    'language' => 'ru-RU',
	'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	'components' => [
//        'db' => require(dirname(__DIR__)."/config/db.php"),
        'cache' => [
			'class' => 'yii\caching\FileCache',
		],
		'authManager' => [
			'class' => 'yii\rbac\DbManager',
		],
		'i18n' => [
			'class' => Zelenin\yii\modules\I18n\components\I18N::className(),
			'languages' => ['ru-RU', 'kz-KZ', 'en-US'],
			'on missingTranslation' => ['Zelenin\yii\modules\I18n\Module', 'missingTranslation'],
			/*'translations' => [
				'yii' => [
					'class' => yii\i18n\DbMessageSource::className(),
				],
				'app' => [
					'class' => yii\i18n\DbMessageSource::className(),
				],
			],*/
		],
        'assetManager' => [
            'forceCopy' => true
        ],
	],
	'timeZone' => 'Asia/Almaty',
];
