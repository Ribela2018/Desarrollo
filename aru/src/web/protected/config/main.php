<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . '/../extensions/bootstrap/css/bootstrap.css');
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'ARU',
	'language'=>'es',
	'timeZone'=>'America/Caracas',
	'charset'=>'utf-8',
	// preloading 'log' component
	'preload'=>array('log','bootstrap'),
	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
                'application.controllers.*',
                'application.components.reports.*',
                'application.modules.report.models.*',
                'application.modules.report.controllers.*',
                'application.modules.report.components.*',
                'application.modules.report.components.reports.*',
                'application.extensions.mpdf.*',
                'application.modules.user.components.PHPExcel.*',
		'application.modules.Hr.models.*',
		'application.modules.Hr.controllers.*',
		'application.modules.Sys.models.*',
		'application.modules.Sys.controllers.*'
                
	),
	'modules'=>array( 
            'report', 
	    'Hr',
            'Sys',	
        
        
        ),
	// application components
	'components'=>array(
		'user'=>array(
			'allowAutoLogin'=>true,
			),
//        'ribela' => array(
//                    'connectionString'=>'pgsql:host=127.0.0.1;port=5432;dbname=ribela_final',
//            ),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
                                //'report/employee/allHourEmployee' => 'allHourEmployee',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
                'session' => array(
                    'timeout' => 3600,
                 ),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
                'report'=>array(
			'class'=>'application.components.Reports'
		),
                'reportModule'=>array(
			'class'=>'application.modules.report.components.Reports'
		),
                'email'=>array(
			'class'=>'application.components.Email'
                ),
                'numerosLetras'=>array(
                        'class'=>'application.components.NumerosLetras'
                ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
                   'class'=>'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
               ),
				// uncomment the following to show log messages on web pages
				
				array(
					'class'=>'CWebLogRoute',
				),
				
			),
		),
        
        // PDF
        'ePdf' => array(
            'class' => 'application.extensions.yii-pdf.EYiiPdf',
            'params' => array(
                'mpdf' => array(
                    'librarySourcePath' => 'application.extensions.mpdf.*',
                    'constants' => array(
                        '_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
                    ),
                    'class' => 'mpdf', // the literal class filename to be loaded from the vendors folder
                /* 'defaultParams'     => array( // More info: http://mpdf1.com/manual/index.php?tid=184
                  'mode'              => '', //  This parameter specifies the mode of the new document.
                  'format'            => 'A4', // format A4, A5, ...
                  'default_font_size' => 0, // Sets the default document font size in points (pt)
                  'default_font'      => '', // Sets the default font-family for the new document.
                  'mgl'               => 15, // margin_left. Sets the page margins for the new document.
                  'mgr'               => 15, // margin_right
                  'mgt'               => 16, // margin_top
                  'mgb'               => 16, // margin_bottom
                  'mgh'               => 9, // margin_header
                  'mgf'               => 9, // margin_footer
                  'orientation'       => 'P', // landscape or portrait orientation
                  ) */
                ),
            ),
        ),
        
        
        //PHPEXCEL
        'excel'=>array(
			'class'=>'application.components.Excel'
			),
        //DetecMovil
        'mobileDetect'=>array(
			'class'=>'application.extensions.MobileDetect.MobileDetect.MobileDetect'
			),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);