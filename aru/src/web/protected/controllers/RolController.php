<?php

class RolController extends Controller
{
	public function actionIndex()
	{
            $model= new Rol;
            if(isset($_POST['Rol']))
              {
                
                $model->attributes=$_POST['Rol'];
               if($model->save())
                    $this->redirect(array('Employee/AdmiControllers'));
               
              }
              
              else
               {
                  $this->render('index',array('model'=>$model));
               }
            
		
	}
        
        
        public function actionCreateRol()
         {
             $name=$_GET['name_rol'];
             $model= new Rol;
             
             if ($name!=NULL)
                 {
                  $model->name_rol=$name;
                  $model->save(); 
                  echo json_encode("1");}
                  else {echo json_encode("2");}
           
         }
         /**
          * 
          */
         public function actionIdRol()
         {
             $nameController=$_POST['nameController'];
             $idrol=$_POST['rol'];//id del rol
             $acionesControladores= ActionController::getAControllers($nameController,$idrol);//id de acciones y controladores asociados al rol
             echo json_encode($acionesControladores);
         }

}