<?php
set_time_limit(-1);
/**
 *
 */
class EventEmployeeController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
     
	/**
     *
     */
    public function filters()
    {
        // return the filter configuration for this controller, e.g.:
        return array(
            'accessControl', /// perform access control for CRUD operations
            array(
                'application.filters.UserLoginFilter + view, create, update, index, admin, check, declarar, informacion',
                ),
            array(
                'application.filters.UserUpdateFilter + view, create, update, index, admin, check, declarar, informacion',
                )
            );
    }

    /**
     *
     */
    public function accessRules()
    {
        return array(
            array(
                'allow',
                'actions'=>Rol::getActions('EventEmployee', Yii::app()->user->id),
                'users'=>array(
                    Yii::app()->user->name
                    )
                ),
              array(
                'deny', // deny all users
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
      
            $Employee=Employee::getEmployee(Yii::app()->user->id);
            if($Employee!=NULL)
            {  
                $model=  EventEmployee::getMaxDateMinHour($Employee->id);
           
                if ($model){
                    $eventos=EventEmployee::getWorkday($Employee->id, $model->date);
                    $validate_hour=EventEmployee::getValidate_hour($eventos[0]['hour'], $model->date);
                
                  $calculo= strtotime($eventos[0]['hour']);
                  $actual= strtotime('16:00:00');
                       if ($calculo>$actual)
                           {
                            
                               $eventos1=EventEmployee::getWorkdayotra($Employee->id, $model->date);
                              
                           }
                           
                           else
                               {
                                $eventos1=EventEmployee::getWorkdaynormal($Employee->id, $model->date);
                                      
                               }
                               
                             
//                       
                              
                    if ($validate_hour!=false){
                        
                        $this->render('create',array('eventos'=>$eventos1,));
                    }else {
                        
                        $eventos1=FALSE;
                        $this->render('create',array('eventos'=>$eventos1,));
                    }
                }else{
                    
                    $eventos=FALSE;
                    $this->render('create',array('eventos'=>$eventos,));
                    
                        
                }
            }
            else
            {
                $Employee = new Employee; 
                $Address = new Address;
                $this->render('/employee/infoEmployee',array('Employee' => $Employee, 'Address' => $Address));
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

		if(isset($_POST['EventEmployee']))
		{
			$model->attributes=$_POST['EventEmployee'];
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
		$dataProvider=new CActiveDataProvider('EventEmployee');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new EventEmployee('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['EventEmployee']))
			$model->attributes=$_GET['EventEmployee'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return EventEmployee the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=EventEmployee::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param EventEmployee $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='event-employee-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
    /**
     * funcion para verificar por medio de la fecha los eventos donde esta el usuario 
     */
    public function actionCheck()
    {
    	$id=Yii::app()->user->id;
    	$date=date('Ymd');
    	$getevente=EventEmployee::getWorkday($id, $date);

    	if($getevente!=NULL)
    	{
            $this->render('Check',array(
                    'geteve'=>$getevente,
                    )
            );
    	}
    }
    
    /**
     *
     */    
    public static function actionDeclarar()
    {
        
        if(isset($_GET['location']) && isset($_GET['date_event']) && isset($_GET['time_event']))
        {
            $userId = Yii::app()->user->id;
            $employeeId = Employee::getEmployee($userId);
            
            $detect = Yii::app()->mobileDetect;
            
            $mobil= $detect->isMobile();
            $table= $detect->isTablet();
            $iphone= $detect->isIphone();
            $phone=FALSE;
            
            if ($mobil !=NULL || $table!=NULL || $iphone=NULL)
                {
                        $phone=TRUE;
                }
           
            $last = null;
            $type_event=null;
            $idEmployee = Employee::getEmployee(Yii::app()->user->id)->id;
            $date = date('Ymd');
            $model = EventEmployee::getMaxDateMinHour($idEmployee);
            $event2 = EventEmployee::model()->findBySql("SELECT e.* FROM event_employee AS e WHERE  e.date = (SELECT MAX(event_employee.date) FROM event_employee WHERE event_employee.id_type_event=2) AND e.id_type_event=2 AND e.id_employee=".$idEmployee);
            if (strtotime($event2->date) == strtotime(date('Y-m-d'))){
                
                $houtEventAdd30M=strtotime('+30 minutes', strtotime($event2->hour_event));
                $houtEventAdd1H=strtotime('+1 Hour', strtotime($event2->hour_event));
                //date('H:i:s', strtotime('+60 minutes', strtotime($_GET['time_event'])));
                if(strtotime($_GET['time_event'])<=$houtEventAdd30M){
                    echo "false";
                    Yii::app()->end();
                }
                
                
                if ($houtEventAdd30M < strtotime($_GET['time_event']) && strtotime($_GET['time_event']) <$houtEventAdd1H){
                    
                    $_GET['time_event'] = date('H:i:s', strtotime('+1 Hour', strtotime($event2->hour_event)));

                }
            }
           
            

            if ($model!=null) {
                $eventosOld = EventEmployee::getWorkday($idEmployee, $model->date);
                $validate_hour = EventEmployee::getValidate_hour($eventosOld[0]['hour'], $model->date);
                $last = end($eventosOld);
                
                $eventosOldprue = EventEmployee::getWorkdayprueba($idEmployee, $model->date);
                $lastprue = end($eventosOldprue);
                
                  $calculo= strtotime($eventosOld[0]['hour']);
                  $getDeclare= strtotime($_GET['time_event']);
                  $actual= strtotime('16:00:00');
                  
                  if($validate_hour)
                {

                    if($calculo > $actual)
                    {
                       
                        if($lastprue['event'] < 4)
                        {
                            $guard = 1;
                            $type_event = $lastprue['event'] + 1;
                        }
                        else
                        {
                            if ($getDeclare>$actual)
                                {
                                $guard = 1;
                                }
                                else
                                    {
                                    $guard = null;
                                    }
                            
                            $type_event = 1;
                        }
                    }
                    else
                    {
                        $eventosOld = EventEmployee::getWorkdayprueba($idEmployee, $model->date);
                        $last = end($eventosOld);
                        
                        if($last['event'] < 4)
                        {
                            $guard = null;
                            $type_event = $last['event'] + 1;
                            
                        }
                        else
                        {
                            
                            $guard = 1;
                            $type_event = 1;
                        }
                    }
                }
                else
                {


                    if($getDeclare > $actual)
                    {
                        $guard = 1;
                        $type_event = 1;
                    }
                    else
                    {
                        $guard = null;
                        $type_event = 1;
                    }
                }






//                if ($validate_hour) {
//                    $date = $model->date;
//                    
//                    if ($calculo<$actual)
//                        {
//                            $guard=1;
//                        }
//                        else
//                            {
//                                $guard=null;
//                            }
//                        
//                }
//                
//                
//                $horadeclaracion=$_GET['time_event'];
//                
//  
//                $eventos = EventEmployee::getWorkday($idEmployee, $date);
//              
//         
//                if ($eventos != false) {
//                    $last = end($eventos);
//                    if ($last['event']<4 ) {
//                        $type_event = $last['event'] + 1;
//                        $aux = TRUE;
//                    }
//                    else{//SE AGREGO ESTE CONDICIONAL PARA CUANDO
//                     $type_event = 1;
//                     $aux = TRUE;
//                   
//                    }
//                } else {
//                   
//                    $type_event = 1;
//                    $aux = TRUE;
//                }
//            } else {
//                $type_event = 1;
//                $aux = TRUE;
//            }
//         
//            
//            $calculo= strtotime($horadeclaracion);
//            $actual= strtotime('16:00:00');
//            
//           
//            
//            if (($type_event ==1)&&($calculo<$actual))
//                {
//                    $guard=null;
//                }
//                else
//                    {
//                        $guard=1;
                    }else{
                        
                        $horadeclaracion=$_GET['time_event'];
                           $calculo= strtotime($horadeclaracion);
                           $actual= strtotime('16:00:00');
                           
                           if ($calculo<$actual)
                            {
                                $guard=null;
                            }
                            else
                                {
                                $guard=1;
                                
                                }
                          $model = new EventEmployee();
                          $model->hour_event = $_GET['time_event'];
                          $model->id_type_event = 1;
                          $model->date = $_GET['date_event'];
                          $model->id_employee = $idEmployee;
                          $model->id_location = Location::getId($_GET['location']);
                          $model->guard = $guard;

                            if($model->save())
                                {
                                $success=TRUE;
                              echo json_encode(array('event' => $model->id_type_event, 'hour' => $model->hour_event, 'date' => $model->date));
                            
                            }
                            else{
                                $success=FALSE;
                                echo 'fallo';
                            }
                    }
            
                    
                    
            if($type_event)
            {
                $model = new EventEmployee();
                $model->hour_event = $_GET['time_event'];
                $model->id_type_event = $type_event;
                $model->date = $_GET['date_event'];
                $model->id_employee = $idEmployee;
                $model->id_location = Location::getId($_GET['location']);
                $model->guard = $guard;

                if($model->save()){
                    $success=TRUE;
                echo json_encode(array('event'=>$model->id_type_event,'hour'=>$model->hour_event,'date'=>$model->date));
                
                }
                else{
                     $success=FALSE;
                echo 'fallo';
                
                }
            }
        }
        else
        {
            echo 'no set';
        }
        
            if($type_event==1)
                {
                 LogAru::getIdNewLog(1,$userId,$phone,$success );
                }
            if($type_event==4)
                {
                 LogAru::getIdNewLog(2,$userId,$phone,$success );
                }
    }
    
    /**
     *
     */
    public function actionInformacion()
    {
    	$idEmployee = Employee::getEmployee(Yii::app()->user->id)->id;
        $date = date('Ymd');
        $eventos = EventEmployee::getWorkday($idEmployee, $date);
        echo json_encode($eventos);
    }
    
    
    

}

