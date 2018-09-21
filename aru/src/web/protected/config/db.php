<?php
// Solo contiene la informacion de conexion de base de datos
return array(
	'components'=>array(
		'db'=>array(
			'connectionString'=>'pgsql:host=localhost;port=5432;dbname=aru',
				'username'=>'postgres',
				'password'=>'postgresql',
			// 'username'=>'hr',
            // 'password'=>'mLXSD9DjFaYVLf5Z',
   
            ),
		'ribela'=>array(
                        'class'=>'CDbConnection',
			'connectionString'=>'pgsql:host=67.215.160.228;port=5435;dbname=ribela_erp_beta',
					'username'=>'postgres',
				'password'=>'postgresql',				
				// 'username'=>'postgres',           	
            	// 'password'=>'hnV5r7dtdC6qnrgA',

            ),
		)
	);
