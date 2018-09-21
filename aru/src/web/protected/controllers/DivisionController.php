<?php
class DivisionController extends Controller
{
    /**
     *
     */
//    public function filters()
//    {
//        // return the filter configuration for this controller, e.g.:
//        return array(
//            'accessControl', /// perform access control for CRUD operations
//            array(
//                'application.filters.UserLoginFilter + index',/*cuando no estas logeado*/
//                ),
//            array(
//                'application.filters.UserUpdateFilter + index',
//                )
//            );
//    }
    
    /**
     *
     */
    public function accessRules()
    {
        return array(
            array(
                'allow',
                'actions'=>Rol::getActions('Division', Yii::app()->user->id),
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
     *
     */
    public function actionIndex()
    {
        $this->render('index');
    }
    
    public function actionGetNewDivision() {
        
         $newDivision = $_GET['new_division'];
         $idDependence = $_GET['id_dependencia'];
         
         $modelDivision = Division::getNewDivision($newDivision,$idDependence);
          echo json_encode($modelDivision);
       

    }

    /**
     * funcion para visualizar las divisiones existentes en la organizacion
     * 
     */
    public function actionViewDivision()
     {
        $model = Division::model()->findAll();
        $this->render('viewDivision',array('model'=>$model));
     }  
     
     public function actionUpdateDivision() {
         
         $idDivision = $_GET['id'];
         $newName = $_GET['newName'];
         
         $model = Division::model()->findByPk($idDivision);
         $model->name = $newName;
         if($model->save()){
            echo json_encode(true);
         }else{
            echo json_encode(false);
         }
     }
     
     public function actionDeleteDivision() {
         
         $idDivision = $_GET['id'];
         $modelPositionCode = count(PositionCode::model()->findAll("id_division = $idDivision"));
         if($modelPositionCode < 1){
            $modelObstacles = count(EmployeeActivityObstacle::model()->findAll("id_division = $idDivision")); 
            if($modelObstacles < 1){
                $model = Division::model()->deleteByPk($idDivision);
                if($model){
                    echo json_encode(true);
                }else{
                    echo json_encode(false);
                }
            }else{
                echo json_encode('DivisionExistObstacle');
            }
         }else{
             echo json_encode('DivisionExistPC');
         }
     }
}