<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'绿浪视觉-客户管理中心',

    'defaultController'=>'order',

	// preloading 'log' component
	'preload'=>array('log'),

    'onBeginRequest'=>create_function('$event', 'return ob_start("ob_gzhandler");'),
    'onEndRequest'=>create_function('$event', 'return ob_end_flush();'),

	// autoloading model and component classes
	'import'=>array(
		'application.admin.models.*',
		'application.components.*',
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
			'sessionName'=>'erpAdmin',
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
            'enableParamLogging'=>true,
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
				),
				array( // configuration for the toolbar
                    'class'=>'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
				    'ipFilters'=>array('127.0.0.1'),
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
		// this is used in contact page
		'timestamp'=>time(),
	    'upload_path'=>dirname(__FILE__).'/../../uploads/',
    	'upload_url'=>'uploads/',
	    'sitemap'=>require("sitemap.php"),
	    'menu'=>require("menu.php"),
	),
);