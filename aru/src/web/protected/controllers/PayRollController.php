<?php

class PayRollController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

	// Uncomment the following methods and override them if needed

	
//	public function filters()
//        {
//            // return the filter configuration for this controller, e.g.:
//            return array(
//                'accessControl', /// perform access control for CRUD operations
//                array(
//                    'application.filters.UserLoginFilter + employeePayRoll, adminPayRoll',/*cuando no estas logeado*/
//                    ),
//                array(
//                    'application.filters.UserUpdateFilter + employeePayRoll, adminPayRoll',
//                    )
//                );
//        }
//
//        public function accessRules()
//        {
//            return array(
//                array(
//                    'allow',
//                    'actions'=>Rol::getActions('PayRoll', Yii::app()->user->id),
//                    'users'=>array(
//                        Yii::app()->user->name
//                        )
//                    ),
//                array(
//                    'deny', // deny all users
//                    'users'=>array('*'),
//                    ),
//                );
//        }


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

        
        public function actionEmployeePayRoll()
        {
            $modelNew = new Payroll();
            $modelAll = Remuneration::getRemunerationByEmployee();
            $modelRRHH = PositionCode::getEmployeesRRHH();
            $this->render('employeePayRoll', array('model' => $modelNew,'modelAll' => $modelAll,'modelRRHH' => $modelRRHH,));
        }
        
        public function actionAdminPayRoll()
        {
            $modelNew = new Payroll();
            $modelAll = Payroll::model()->findAll();
            $modelEmployee = PayrollEmployee::getAllEmployeeByPayRoll(0,'false');
            $this->render('adminPayRoll', array('model' => $modelNew,'modelEmployee' => $modelEmployee,'modelAll' => $modelAll,));
        }
        

        public function actionGetAllPayRoll() {
            
            $modelNew = new Payroll();
            $modelAll = Payroll::model()->findAll();
            $modelEmployee = PayrollEmployee::getAllEmployeeByPayRoll(0,'false');
            $this->renderPartial('adminPayRoll', array('model' => $modelNew,'modelEmployee' => $modelEmployee,'modelAll' => $modelAll,),false);

        }


}