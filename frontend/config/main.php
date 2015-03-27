<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
/*
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
*/
    'id' => 'frontend',
    'basePath'=>dirname(__DIR__),
	'homeUrl'=>Yii::getAlias('@frontendUrl'),
	'controllerNamespace' => 'frontend\controllers',
	'defaultRoute' => 'site/index',
	'modules' => [
		'user' => [
			'class' => 'frontend\modules\user\Module',
		],
	],
	'components' => [
		'authClientCollection' => [
			'class' => 'yii\authclient\Collection',
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'user' => [
			'class'=>'yii\web\User',
			'identityClass' => 'common\models\User',
			'loginUrl'=>['/user/sign-in/login'],
			'enableAutoLogin' => true,
		],
		'urlManager'=> [
			'class'=>'yii\web\UrlManager',
			'enablePrettyUrl'=>true,
			'showScriptName'=>false,
			'rules'=> [
				['pattern'=>'page/<alias>', 'route'=>'page/view']
			]
		]

	],

    'params' => $params,
];
