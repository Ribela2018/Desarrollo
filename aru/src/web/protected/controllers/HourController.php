<?php

class HourController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
//	public function filters()
//	{
//		return array(
//			'accessControl', // perform access control for CRUD operations
//			'postOnly + delete', // we only allow deletion via POST request
//		);
//	}
//
//	/**
//	 * Specifies the access control rules.
//	 * This method is used by the 'accessControl' filter.
//	 * @return array access control rules
//	 */
//	public function accessRules()
//	{
//		return array(
//			array('allow',  // allow all users to perform 'index' and 'view' actions
//				'actions'=>array('index','view'),
//				'users'=>array('*'),
//			),
//			array('allow', // allow authenticated user to perform 'create' and 'update' actions
//				'actions'=>array('create','update'),
//				'users'=>array('@'),
//			),
//			array('allow', // allow admin user to perform 'admin' and 'delete' actions
//				'actions'=>array('admin','delete'),
//				'users'=>array('admin'),
//			),
//			array('deny',  // deny all users
//				'users'=>array('*'),
//			),
//		);
//	}

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
		$model=new Hour;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Hour']))
		{
			$model->attributes=$_POST['Hour'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
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

		if(isset($_POST['Hour']))
		{
			$model->attributes=$_POST['Hour'];
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
		$dataProvider=new CActiveDataProvider('Hour');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Hour('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Hour']))
			$model->attributes=$_GET['Hour'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Hour the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Hour::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Hour $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='hour-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
    
    
    public function actionGetNewHorarioEmployee()
    {
        $start_time = $_GET['start_time'];
        $end_time = $_GET['end_time'];
        $i = 0;
        $countEmployee = 0;
        $modelNewHours = Hour::model()->find("start_time = '$start_time' AND end_time = '$end_time'");
        if($modelNewHours == NULL)
        {
            $modelHora = new Hour;
            $modelHora->start_time = $start_time;
            $modelHora->end_time = $end_time;
            if($modelHora->save())
            {
                $i++;
            }
            elseif($modelHora->save(false))
            {
                $i++;
            }
        }
        else{
            $arrayHoursExist = $modelNewHours->start_time . ' a ' . $modelNewHours->end_time;
            $countEmployee++;
        }
        
        if($i > 0)
        {
            echo json_encode(true);
        }
        elseif($i < $countEmployee)
        {
            if(count($countEmployee) > 0)
            {
                echo json_encode($arrayHoursExist);
            }
            else
            {
                echo json_encode(false);
            }
        }
        
    }
    
     public static function actionRefreshGetEmployeeHours() 
      {
         $typeHour=new Hour();
         $listadoHour=CHtml::dropDownList('asignacionHours', $typeHour, Hour::getEmployeeHours(), array("class" => "form-control input-medium select2", "empty" => "Seleccione Una Opción")) ;
         echo $listadoHour ;
                  
      }
     public static function actionRefreshGetHoursByEmployee() 
      {
         $typeHourByEmployee=new Hour();
         $idEmployee= $_GET['idEmployee'];
         $listadoHourByEmployee=CHtml::dropDownList('hourEmployee', $typeHourByEmployee, Hour::getEmployeeHoursByEmployee($idEmployee), array("class" => "form-control input-medium select2", "empty" => "Seleccione Una Opción")) ;
         echo $listadoHourByEmployee ;
                  
      }
      
   public function actionGetEmployeeHoursCheckByDay()
    {
        $idEmployee= $_GET['idEmployee'];
        $consulta = "select eh.id as name, h.id, h.start_time, h.end_time, eh.static, eh.start_break, eh.end_break from hour h inner join employee_hour eh on eh.id_hours = h.id where eh.id_employee = " . $idEmployee . " ORDER BY eh.id;";
        $modelChechEmployeeByHour = Hour::model()->findAllBySql($consulta);
        $table = "<table style='width:100%' id='tableAsigHorarios'>"
                . "<tr>"
                . "<td><h4>Horario Asignados</h4></td>"
                . "<td colspan='3'><h4 style='margin-left:300px'>Descanso Asignado</h4></td>"
                . "</tr>"
                . "<tr>"
                . "<td></td>"
                . "<td><span style='margin-left:124px' class='verde'>Hora Inicio</span></td>"
                . "<td><span style='margin-left:54px' class='verde'>Hora Fin</span></td>"
                . "</tr>";
        foreach($modelChechEmployeeByHour as $key => $value)
        {
            $checked = '';
            if($value->static){
                $checked = 'checked';
            }
            
            if ($value->start_break){$asigHorarioStart=$value->start_break;}else{$asigHorarioStart=' ';}
            if ($value->end_break){$asigHorarioEnd=$value->end_break;}else{$asigHorarioEnd=' ';}
            $table.="<tr>"
                    . "<td><div class='radio-list'><label><input type='radio' name='hour' value='$value->id' $checked/>$value->start_time -- $value->end_time</label></div></td>"
                    . "<td align='center'>
                            <div class='input-group' style='width: 177px !important'>
                                <input type='text' id='start_time_break' name='start_time_break' class='form-control  timepicker timepicker-24 rangoTiempoInicio' value='$asigHorarioStart'>
                                <span class='input-group-btn'>
                                    <button class='btn default' type='button' style='height: 34px'><i class='fa fa-clock-o'></i></button>
                                </span>
                            </div>
                      </td>
                      <td style='width:214px'>
                            <div class='input-group' style='width: 177px !important'>
                                 <input type='text' id='end_time_break' name='end_time_break' class='form-control timepicker timepicker-24 rangoTiempoFin' value='$asigHorarioEnd'>
                                 <span class='input-group-btn'>
                                     <button class='btn default' type='button' style='height: 34px'><i class='fa fa-clock-o'></i></button>
                                 </span>
                             </div>
                      </td>
                      <td><a id='btonAsigHourBreak' class='btonAsiHourBreak'><input type='bottom' id='$value->name' name='botnAsig' value='Asignar' class='btn green btonAsignarDescanso tooltips' style='width:101px'  data-original-title='Asignar Descanso'/><a/></td>"
                    . "</tr>"
                    . "<tr><td>&nbsp</td></tr>";
        }
        $table.="<tr><td colspan='4'><a id='btnCerrarModalAsignaHora'><input type='bottom' name='cerrarModal' id='cerrarModal' class='btn blue' data-dismiss='modal' value='Cerrar' style='width:100%'/></a></td></tr></table>";
        echo $table;
      
    }

}
