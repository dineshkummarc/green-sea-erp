<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'../admin',
	'name'=>'绿浪视觉-管理中心',


	// preloading 'log' component
	'preload'=>array('log'),

	// 设置默认时区
    'timeZone'=>'Asia/Shanghai',

	'language'=>'zh_cn',

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.models.Admin',
	),

	'aliases'=>array(
	    'widget'=>'application.widget',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123',
			'ipFilters'=>array('127.0.0.1','::1'),
		),
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'session'=>array(
			'sessionName'=>'erpClient',
		    'class'=>'CHttpSession',
			'timeout'=>1800
        ),
		// uncomment the following to enable URLs in path-format
		/*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		*/
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=127.0.0.1;dbname=ll_erp',
			'enableProfiling'=>true,
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'root',
		    'tablePrefix' => 'll_erp_',
			'charset' => 'utf8',
		    'schemaCachingDuration'=>0,
		),
		'authManager'=>array(
            'class'=>'AuthManager',
        ),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            //'errorAction'=>'site/error',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),/*
				array( // configuration for the toolbar
                    'class'=>'application.extensions.debugtb.XWebDebugRouter',
                    'config'=>'alignLeft, opaque, runInDebug, fixedPos, collapsed, yamlStyle',
                    'levels'=>'error, warning, trace, profile, info',
                ),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		'timestamp'=>time(),
		'upload_path'=>dirname(__FILE__).'/../../uploads/',
    	'upload_url'=>'uploads/',
	),
);