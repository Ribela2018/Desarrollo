<?php

class RemunerationController extends Controller
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
        
       public function actionCreateRemuneration()
       {
            $model = new Remuneration;
            $idEmployee = $_GET['idEmployee'];
            $idPayRoll = $_GET['idPayRoll'];
            $modelPayRollEmployee = PayrollEmployee::getDataPayRollEmployee($idEmployee,$idPayRoll);
            $arrayReturn = Array();
            $count = 0;
            
            $modelCatgory = StructureCategory::model()->findAll("name != 'Información Colaboradores'");
            foreach ($modelCatgory as $key => $value) {
               $arrayReturn[$value->name] = Remuneration::getArrayRemunerationCategory($idEmployee,$idPayRoll,$value->id,false);
               $count++;
            }

            $this->renderPartial('_form_create', array(
                'model' => $model,
                'modelRemuneration' => $arrayReturn,
                'modelPayRollEmployee' => $modelPayRollEmployee,
                'idEmployee' => $idEmployee,
            ),false);
       }
        
       public function actionUpdateRemuneration() 
       {
           $idEmployee = $_GET['idEmployee'];
           $idPayRoll = $_GET['idPayRoll'];
           $idPayRollEmployee = PayrollEmployee::model()->find("id_employee = $idEmployee AND id_payroll = $idPayRoll AND (end_date IS NULL OR end_date >= '".date('Y-m-d', time())."')")->id;
           $arrayCategory = json_decode($_GET['arrayCategory']);
           $arrayReturn = Array();
           $countSave = 0;
           $countReturn = 0;
           $countCategory = count($arrayCategory);
           
           foreach ($arrayCategory as $key => $value) {
               $idCategory = StructureCategoryDescription::getIdByName($key);
               $modelRemuneration = Remuneration::model()->find("id_payroll = $idPayRollEmployee AND id_structure_category_description = $idCategory");
               if($modelRemuneration != NULL){
                   $modelRemuneration->value = Currency::getCurrencyValue($value);
                   if($modelRemuneration->save(false)){
                       $countSave++;
                   }else{
                       $arrayReturn[$countReturn] = $key; 
                   }
               }else{
                   $modelNewRemuneration = new Remuneration;
                   $modelNewRemuneration->id_payroll = $idPayRollEmployee;
                   $modelNewRemuneration->id_structure_category_description = $idCategory;
                   $modelNewRemuneration->value = Currency::getCurrencyValue($value);
                   if($modelNewRemuneration->save()){
                       $countSave++;
                   }elseif($modelNewRemuneration->save(false)){
                       $countSave++;
                   }else{
                       $arrayReturn[$countReturn] = $key;
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