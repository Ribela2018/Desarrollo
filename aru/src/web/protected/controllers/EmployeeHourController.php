<?php

class EmployeeHourController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
    
    
    public function actionEmployeeHours()
    {
        $idUser = Yii::app()->user->id;
        if(!is_null($idUser)){
            $employee= new Employee();
            $typeHour= new Hour();
            $time_break= new EmployeeHour();

            $modelEmployee = EmployeeHour::getAllEmployeeByHours();
            $this->render('employeeHours', array(
                'employee'=>$employee,
                'modelEmployee'=>$modelEmployee, 
                'typeHour'=>$typeHour,
                'time_break'=>$time_break
            ));
        }else{
            $this->redirect(Yii::app()->homeUrl);
        }
        
    }
    
    public function actionAllDataHourEmployee() {
        
        if(isset($_GET['idEmployee'])){
            $employee = $_GET['idEmployee'];
        }else{
            $employee = 'Empty';
        }
        
        $this->render('allDataHourEmployee', array(
            'employee'=>$employee,
        ));
        
    }

    // Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
    
   public function actionGetAsignaHorarioEmployee()
    {
        $arrayIdEmployee = json_decode($_GET['value']);
        $asignacionHoras = $_GET['asignacionHoras'];
        $countArrayEmployee = count($arrayIdEmployee);
        $i = 0;
        $countEmployee = 0;
        $arrayEmployeeExist='';
        foreach($arrayIdEmployee as $keyAsignacionHoras => $idEmployee)
        {
            $modelEmployeeHours = EmployeeHour::model()->find("id_employee = $idEmployee AND id_hours = $asignacionHoras AND end_date IS NULL");
            if($modelEmployeeHours == NULL)
            {
                $modelEmployeeHora = new EmployeeHour;
                $modelEmployeeHora->id_employee = $idEmployee;
                $modelEmployeeHora->id_hours = $asignacionHoras;
                $modelEmployeeHora->start_date = date('Y-m-d',time());
                if($modelEmployeeHora->save())
                {
                    $i++;
                }
                elseif($modelEmployeeHora->save(false))
                {
                    $i++;
                }
            }
            else
            {
                $modelEmployee = Employee::model()->find("id = $idEmployee");
                $arrayEmployeeExist[$countEmployee] = $modelEmployee->first_name . ' ' . $modelEmployee->last_name;
                $countEmployee++;
            }
        }

        if($i == $countArrayEmployee)
        {
            echo json_encode(true);
        }
        elseif($i < $countArrayEmployee)
        {
            if(count($countEmployee) > 0)
            {
                echo json_encode($arrayEmployeeExist);
            }
            else
            {
                echo json_encode(false);
            }
        }
    }
    
    public function actionDeleteHourEmployee()
    {
        $idHour = $_GET['idHour'];
        $idEmployee = $_GET['idEmployee'];
        $modelEmployeeHours = EmployeeHour::model()->find("id_employee = $idEmployee AND id_hours = $idHour");

        if($modelEmployeeHours != NULL)
        {
            $idEmpleyooHours = $modelEmployeeHours->id;
            $modelEmployeeHoursDay = EmployeeHoursDay::model()->findAll("id_employee_hours = $idEmpleyooHours");
            if($modelEmployeeHoursDay == NULL){
                $model = EmployeeHour::model()->deleteByPk($idEmpleyooHours);
                if($model)
                {
                    echo json_encode(true);
                }
                else
                {
                    echo json_encode(false);
                }
            }else{
                echo json_encode('CalendarExist');
            }
        }
        else
        {
            echo json_encode('ErrorDB');
        }
    }
    
    public function actionSaveBreakHour() 
    {
        $startBreakHour = $_GET['startBreak'];
        $endBreakHour = $_GET['endBreak'];
        $idEmployeeHour = $_GET['idEmployeeHour'];
        
        if($startBreakHour != 'Empty' && $endBreakHour != 'Empty' && $idEmployeeHour != 'Empty'){
            $model = EmployeeHour::model()->findByPk($idEmployeeHour);
            if($model != NULL){
                $model->start_break = $startBreakHour;
                $model->end_break = $endBreakHour;
                if($model->save(false)){
                    echo json_encode(true);
                }else{
                    echo json_encode(false);
                }
            }else{
                echo json_encode('HoursNotExist');
            }
        }else{
            echo json_encode('EmptyData');
        }
    }
    
    public function actionSetStaticHourEmployee() 
    {
        $idEmployee = $_GET['idEmployee'];
        $idHour = $_GET['idHour'];
        if($idEmployee != 'Empty' && $idHour != 'Empty'){
            $model = EmployeeHour::model()->findAll("id_employee = $idEmployee");
            if($model != NULL){
                foreach ($model as $key => $value) {
                    $value->static = FALSE;
                    $value->save(false);
                }
                $modelStatic = EmployeeHour::model()->find("id_employee = $idEmployee AND id_hours = $idHour");
                $modelStatic->static = TRUE;
                if($modelStatic->save()){
                    echo json_encode(true);
                }else{
                    echo json_encode(false);
                }
            }else{
                echo json_encode('HoursNotExist');
            }
        }else{
            echo json_encode('EmptyData');
        }
    }
    
    
    public function actionGetHoursBreak()
    {
        $arrayIdEmployee = json_decode($_GET['value']);
        $start_break = $_GET['start_break'];
        $end_break = $_GET['end_break'];
        $i = 0;
        if($arrayIdEmployee != 'Empty' && $start_break != 'Empty' && $end_break != 'Empty')
        {

            foreach($arrayIdEmployee as $keyAsignacionHorasDescanso => $idEmployee)
            {

                $modelEmployeeHoursBreak = EmployeeHour::model()->findAll("id_employee = $idEmployee  AND end_date IS NULL");
                 
                if($modelEmployeeHoursBreak != NULL)
                {
                    foreach($modelEmployeeHoursBreak as $key => $value)
                    {
                        $value->start_break = $start_break;
                        $value->end_break = $end_break;
                        if($value->save())
                        {
                            $i++;
                        }
                        elseif($value->save(false))
                        {
                            $i++;
                        }
                    }
                }
            }

            if($i != 0)
            {
                echo json_encode(true);
            }
            else
            {
                echo json_encode(false);
            }
        }
    }

}