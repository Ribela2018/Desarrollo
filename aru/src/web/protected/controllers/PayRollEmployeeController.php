<?php

class PayRollEmployeeController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
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
    
    
    public function actionGetNewPayRollEmployee()
    {
       $arrayEmployeeExist = Array(); 
       $arrayPayRoll = json_decode($_GET['arrayPayRoll']);
       $payRoll = $_GET['concepPayRoll'];
       $i = 0;
       $countEmployee = 0;

       $countArrayEmployee = count($arrayPayRoll);
       
       foreach ($arrayPayRoll as $keyPayRoll => $idEmployee) {

           $idPayRoll = Payroll::getIdPayRollByName($payRoll);
           $modelPayRollCheck = PayrollEmployee::model()->find("id_employee = $idEmployee AND id_payroll = $idPayRoll AND end_date IS NULL");
           if($modelPayRollCheck == NULL){
                $modelPayRoll = new PayrollEmployee;
                $modelPayRoll->id_payroll = $idPayRoll;
                $modelPayRoll->id_employee = $idEmployee;
                $modelPayRoll->start_date = date('Y-m-d',time());
                $modelPayRoll->end_date = NULL;
                $modelPayRoll->salary = 0;
                $modelPayRoll->id_currency = 1;
                if($modelPayRoll->save()){
                    $i++;
                }elseif($modelPayRoll->save(false)){
                    $i++;
                }
           }else{
                $modelEmployee = Employee::model()->find("id = $idEmployee");
                $arrayEmployeeExist[$countEmployee] = $modelEmployee->first_name.' '.$modelEmployee->last_name;
                $countEmployee++;
           }
       }

       if($i == $countArrayEmployee){
           echo json_encode(true);
       }elseif($i < $countArrayEmployee){
           if(count($countEmployee) > 0){
               echo json_encode($arrayEmployeeExist);
           }else{
               echo json_encode(false);
           }
       }
    }
    
    public function actionGetEmployeeByPayRoll() 
    {   
        $idPayRoll = $_GET['idPayRoll'];
        $table = NULL;
        
        $table = PayrollEmployee::getAllEmployeeByPayRoll($idPayRoll);
        echo json_encode($table);

    }

    
    public function actionUpdatePayRollByEmployee() {
        
        $idPayRoll = $_GET['idPayRoll'];
        $idEmployee = $_GET['idEmployee'];
        $arrayData = json_decode($_GET['arrayData']);
        $arraFrom = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $arraTo = Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        $i = 0;
        
        $modelPayRollByEmployee = PayrollEmployee::model()->find("id_payroll = $idPayRoll AND id_employee = $idEmployee AND (end_date IS NULL OR end_date >= '".date('Y-m-d', time())."')");
        if($modelPayRollByEmployee != NULL){
            foreach ($arrayData as $key => $value) {
                if($value == NULL && $value == ''){
                    $modelPayRollByEmployee->$key = NULL;
                }else{
                    if($key == 'start_date' || $key == 'end_date'){
                        $modelPayRollByEmployee->$key = date('Y-m-d',strtotime(str_replace($arraFrom,$arraTo,$value)));
                    }elseif($key == 'salary'){
                        $modelPayRollByEmployee->$key = Currency::getCurrencyValue($value);
                    }else{
                        $modelPayRollByEmployee->$key = $value;
                    }
                }
            }
            if($modelPayRollByEmployee->save(false)){
                $i++;
            }
        }
        
        if($i > 0){
            echo json_encode(true);
        }else{
            echo json_encode(false);
        }
    }
    
    public function actionDeletePayRollByEmployee() {
        
        $idPayRoll = $_GET['idPayRoll'];
        $arrayEmployee = json_decode($_GET['arrayEmployee']);
        $delete = NULL;
        $i = 0;
        
        foreach ($arrayEmployee as $key => $idEmployee) {

            $modelPayRollByEmployee = PayrollEmployee::model()->find("id_payroll = $idPayRoll AND id_employee = $idEmployee");
            if($modelPayRollByEmployee != NULL){
                $modelRemuneration = Remuneration::model()->deleteAll("id_payroll = $modelPayRollByEmployee->id");
                $delete = $modelPayRollByEmployee->delete();
                if($delete || $modelRemuneration > 0){
                    $i++;
                }
            }
        }
        
        if($i > 0){
            echo json_encode(true);
        }else{
            echo json_encode(false);
        }
        
    }
    
    public function actionGetDataEmployeePayRoll() {
        
        $idEmployee = $_GET['idEmployee'];
        $idPayRoll = $_GET['idPayRoll'];
        
        $arrayData = PayrollEmployee::getDataPayRollEmployee($idEmployee,$idPayRoll);
        
        echo json_encode($arrayData);
        
    }
    
    public function actionFinEmployeePayRoll() {
        
        $payRoll = $_GET['idPayRoll'];
        $arrayPayRoll = json_decode($_GET['arrayEmployee']);
        $countSave = 0;
        
  
        foreach ($arrayPayRoll as $key => $idEmployee) {
            
            $modelCheck = PayrollEmployee::model()->find("id_employee = $idEmployee AND id_payroll = $payRoll AND end_date IS NULL");
            if($modelCheck != NULL){
                $modelCheck->end_date = date('Y-m-d',time());
                if($modelCheck->save(false)){
                    $countSave++;
                }
            }

        }
        
        if($countSave > 0){
            echo json_encode(true);
        }else{
            echo json_encode(false);
        }
        
    }

}