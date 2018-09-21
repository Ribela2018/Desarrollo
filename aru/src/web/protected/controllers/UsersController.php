<?php

class UsersController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','update','admin','updatepass','ChangePass'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','dinamycUsername'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Users;
		// Uncomment the following line if AJAX validation is needed
		 $this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{     
                    $createUser = '';
                    $modelEmployee = new Employee;
                    if(strpos(trim($_POST['Users']['id_employee']),' ')){
                        $nameEmployee = explode(' ', $_POST['Users']['id_employee']);
                        $modelEmployee->first_name = ucwords(strtolower($nameEmployee[0]));
                        $modelEmployee->last_name = ucwords(strtolower($nameEmployee[1]));
                        $modelEmployee->email_company = $_POST['Users']['email'];
                        if($modelEmployee->save()){
                            $createUser = TRUE;
                        }else{
                            $createUser = FALSE;
                        }
                    }else{
                        $createUser = FALSE;
                    }
                    if($createUser == TRUE)
                    {
                        $newPassword = Users::getNewPassword();
                        $model->id = Users::model()->findBySql("SELECT (MAX(id)+1) as id FROM users;")->id;
                        $model->username = $_POST['Users']['username'];
                        $model->password = md5($newPassword);
                        $model->email = $_POST['Users']['email'];
                        $model->activeKey = 'as5d45as4d5a4sd';
                        $model->id_rol = $_POST['Users']['id_rol'];
                        $model->create_at = date('Y-m-d H:i:s').'-04:30';
                        $model->id_status = 3;
                        $model->id_employee = $modelEmployee->id;
                        if($model->save())
                        {
                            Security::filepublickey($model->id); 
                            $html = '<h3>Credenciales de Acceso al Nuevo Usuario</h3>';
                            $html .= '<br>';
                            $html .= '<b>Usuario:</b> '.$_POST['Users']['username'];
                            $html .= '<br>';
                            $html .= '<b>Contraseña:</b> '.$newPassword;
                            $html .= '<br>';
                            $html .= '<b>Email:</b> '.$_POST['Users']['email'];
                            Yii::app()->email->sendEmail($html, 'rrhh@sacet.biz', 'Credenciales de Acceso - Nuevo Usuario', NULL);
                            $model = new Users;
                            Yii::app()->user->setFlash('success', "Usuario Generado con Exito.");
                        }else{
                            Yii::app()->user->setFlash('error', "Usuario No Generado."); 
                        }     
                    }else{
                        Yii::app()->user->setFlash('error', "Usuario No Generado.");
//                        $this->redirect('create',array('model'=>$model));
                    }
		}
		$this->render('create',array(
			'model'=>$model,
		));
	}
        
        public function actionDinamycUsername() 
        {
            if(isset($_POST)){
                $name = $_POST['name'];
                $username = Users::validateUsernameDinamyc($name);
                if($username != 'Empty'){
                    echo json_encode($username);
                }else{
                    echo json_encode('Empty');
                }
            }else{
                echo json_encode('Empty');
            }
        }

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('User');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Users('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return User the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Users::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param User $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        
        /**
         * 
         * 
         */
        
        public function actionUpdatePass(){
            $id=Yii::app()->user->id;  
            $model=Users::model()->findByPk($id);
            if($model->id_status != 3){
               
                $this->render('viewpass',array(
                            'model'=>$model,
                    ));
            }else{
                $Employee=new Employee;
                $Address=new Address;
                $this->render('/employee/viewfirstemployee', array('model'=>$Employee,'Address'=>$Address));
            }
        }
        
        
        public function actionChangePass(){
          
            $id=Yii::app()->user->id;  
            $model=Users::model()->findByPk($id);
            if($model->id_status != 3){
                $last_pass=  md5($_GET['confirmar_pass']);
               
                
                    $validate_pass=Users::model()->getPass($last_pass);
                                if ($validate_pass!=NULL){
                                     $model=Users::model()->findByPk($id);
                                     $model->password= md5($_GET['pass']);
                                     $model->save();
                                     echo json_encode("1");
                                }
                                else {
                                    echo json_encode("2");   
                                }
                
            }
            
        }
        
        
      
        
        
       
        
        
     
}
