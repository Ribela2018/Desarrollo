<?php

class PositionController extends Controller
{
    
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
    
    
    
    public function accessRules()
    {
        return array(
            array(
                'allow',
                'actions'=>Rol::getActions('Position', Yii::app()->user->id),
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
    
    public function actionIndex()
    {
        $model= new Position;
        $this->render('index', array('model'=>$model));
    }
    
    
    /**
     * funcion para crear nuevos cargos
     */
//    public function actionAddPosition()
//    {
//         if (($_GET['nuevoCargo']!=null) && ($_GET['leader']!=null))
//         {
//            $model=new Position;
//            $model->name=$_GET['nuevoCargo'];
//            $model->leader=$_GET['leader'];
//            if($model->save())echo json_encode(true); else echo json_encode(false); 
//         }
//
//    }
    
    
    public function actionGetNewPosition() {
        
         $newPosition = $_GET['new_position'];
         $leader = $_GET['leader'];
         $modelPosition = Position::getNewPosition($newPosition, $leader);
          echo json_encode($modelPosition);
         //return $modelDivision;

    }
     
     
     /**
     * funcion para visualizar las posiciones (cargos) existentes en la organizacion
     * 
     */
    public function actionViewPosition()
     {
        $model = Position::model()->findAllBySql("SELECT * FROM position ORDER BY leader DESC;");
        $this->render('viewPosition',array('model'=>$model));
     }
    
     public function actionUpdatePosition() {
         
         $idPosition = $_GET['id'];
         $newName = $_GET['newName'];
         
         $model = Position::model()->findByPk($idPosition);
         $model->name = $newName;
         if($model->save()){
           echo  json_encode(true);
         }else{
           echo  json_encode(false);
         }
     }
     
     public function actionDeletePosition() {
         
         $idPosition = $_GET['id'];

         $modelPositionCode = count(PositionCode::model()->findAll("id_position = $idPosition"));
         if($modelPositionCode < 1){
            $model = Position::model()->deleteByPk($idPosition);
            if($model){
             echo   json_encode(true);
            }else{
             echo   json_encode(false);
            }
         }else{
           echo  json_encode('PositionExistPC');

         }
     }
	
}