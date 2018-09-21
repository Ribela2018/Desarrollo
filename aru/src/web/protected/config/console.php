<?php

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'ARU Consola',
	'timeZone'=>'America/Caracas',
	'charset'=>'utf-8',
	// preloading 'log' component
	'preload'=>array('log'),
        'import'=>array(
		'application.models.*',
		'application.components.*',
                'application.extensions.mpdf.*',
                'application.components.reports.*',
                'application.controllers.*',
                'application.modules.report.models.*',
                'application.modules.report.controllers.*',
                'application.modules.report.components.*',
                'application.modules.report.components.reports.*',
		),
	// application components
	'components'=>array(
		'senddeclareday'=>array(
			'class'=>"application.components.sendDeclareDayAutomatico",
			), 
                'sendemployeehour'=>array(
			'class'=>"application.components.sendEmployeeHourAutomatico",
			),
                'sendemployeehappybirthday'=>array(
			'class'=>"application.components.sendEmployeeHappyBirthdayAutomatico",
			),
                'sendnotificationholiday'=>array(
                        'class'=>"application.components.sendNotificationHolidayAutomatico",
                        ),
                'sendnotificationhour'=>array(
                        'class'=>"application.components.sendNotificationHourAutomatico",
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
		'user'=>array(
			'class'=>'WebUser',
			// enable cookie-based authentication
			//'allowAutoLogin'=>true,
			'loginUrl'=>array('/user/login'),
			),
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
			// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				'recargas/<action:\w+>/<compania:\w+>'=>'recargas/<action>/view',

				),
			),
		// uncomment the following to use a MySQL database
		'importcsv'=>array(
			'path'=>'upload/importCsv/', // path to folder for saving csv file and file with import params
            ),        
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
					),
				// uncomment the following to show log messages on web pages
				//array(
				//	'class'=>'CWebLogRoute',
				//		),
				),
			),
		'format'=>array(
			'class'=>'application.components.Formatter',
			'numberFormat'=>array(
				'decimals'=>2,
				'decimalSeparator'=>'.',
				'thousandSeparator'=>''
				),
			),
		),

	);