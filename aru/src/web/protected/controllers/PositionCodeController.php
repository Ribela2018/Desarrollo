<?php

class PositionCodeController extends Controller
{
    
//    public function filters()
//    {
//        // return the filter configuration for this controller, e.g.:
//        return array(
//            'accessControl', /// perform access control for CRUD operations
//            array(
//                'application.filters.UserLoginFilter + index, AdminPositionCode, CrearPosition ',/*cuando no estas logeado*/
//                ),
//            array(
//                'application.filters.UserUpdateFilter + index, AdminPositionCode, CrearPosition',
//                )
//            );
//    }
//    
//    public function accessRules()
//    {
//        return array(
//            array(
//                'allow',
//                'actions'=>Rol::getActions('PositionCode', Yii::app()->user->id),
//                'users'=>array(
//                    Yii::app()->user->name
//                    )
//                ),
//            array(
//                'deny', // deny all users
//                'users'=>array('*'),
//                ),
//            );
//    }
 
    public function actionIndex()
    { 
        $model= new PositionCode;
        $this->render('index',array('model'=>$model));
    }
    
    public function actionUpdate()
    { 
        $model= new PositionCode;
        $this->renderPartial('update',array('model'=>$model), false);
    }

    public function actionCreatePositionCode()
    {
        if($_GET['id_division'] != NULL) $idDivision = $_GET['id_division']; else $idDivision = Division::getNewDivision($_GET['new_division'], $_GET['id_dependencia']);
        if($_GET['id_position'] != NULL) $idPosition = $_GET['id_position']; else $idPosition = Position::getNewPosition($_GET['new_position'], $_GET['leader']);
        if($_GET['check']!= NULL) $check = $_GET['check'];

        $LevelPosition = Position::getModelPositionByDivision($idDivision);
        $arraFrom = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $arraTo = Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        //$pedendenciaDivision = DivisionController::actionGetDependencia();
        $pedendenciaDivision = $_GET['codePosition'];
        $startDate = date('Y-m-d',  strtotime(str_replace($arraFrom,$arraTo,$_GET['start_date'])));
        
        if($LevelPosition != NULL)
        {
            if($LevelPosition[0]->leader != 0)
            {
                $createPositionCode = PositionCode::getNewPositionCode($idDivision,$pedendenciaDivision , $idPosition, $_GET['id_employee'], $startDate);
                echo json_encode($createPositionCode);
            }
            else echo json_encode("sinlider");
        }
        else
        {
            $verificarCargo = Position::getModelPosition($idPosition);
            
            if($verificarCargo->leader != 0)
            {
                $createPositionCode = PositionCode::getNewPositionCode($idDivision,$pedendenciaDivision, $idPosition, $_GET['id_employee'], $_GET['start_date']);
                echo json_encode($createPositionCode);
            }
            else echo json_encode("sinlider");
        }
    }

    public function actionAdminPositionCode(){
         $Employee = new Employee;
        
        $modelPositionCodeActives = PositionCode::model()->findAllBySql("SELECT pc.id, pc.position_code, pc.id_position, pc.id_division, d.id_dependency, pc.id_employee, pc.start_date, pc.end_date
                                                                         FROM position_code pc
                                                                         INNER JOIN division as d ON d.id = pc.id_division
                                                                         INNER JOIN position as p ON p.id = pc.id_position
                                                                         INNER JOIN employee as e ON e.id = pc.id_employee
                                                                         WHERE pc.end_date IS NULL
                                                                         ORDER BY pc.position_code ASC;");
        
        $modelPositionCodeInactives = PositionCode::model()->findAllBySql("SELECT pc.id, pc.position_code, pc.id_position, pc.id_division, d.id_dependency, pc.id_employee, pc.start_date, pc.end_date
                                                                           FROM position_code pc
                                                                           INNER JOIN division as d ON d.id = pc.id_division
                                                                           INNER JOIN position as p ON p.id = pc.id_position
                                                                           INNER JOIN employee as e ON e.id = pc.id_employee
                                                                           WHERE pc.end_date IS NOT NULL
                                                                           ORDER BY pc.position_code ASC;");
      
        $this->render('AdminPc',array('modelPositionCodeActives'=>$modelPositionCodeActives,
                                      'modelPositionCodeInactives'=>$modelPositionCodeInactives, 'Employee'=>$Employee));
    }
    
    public function actionGetPositionCode()
    {
        $division = $_GET['id_division'];
        $position = $_GET['id_position'];
        $check = $_GET['check'];
        $employee = $_GET['id_employee'];
        
        $vacantPositionCode = self::actionGetVacantPositionCode();
        
        if($vacantPositionCode == false){
        
            $dependecy = Division::model()->findBySql("select id_dependency from division where id = $division;")->id_dependency;
            $levelPosition = Position::model()->findBySql("select leader from position where id = $position;")->leader;

            if($dependecy == NULL){ $comparador = 'IS'; $dependecy = 'NULL'; }
            else{ $comparador = '='; $dependecy = $dependecy; }

            if($levelPosition == 1){

                $sql = "SELECT pc.position_code as position_code
                    FROM position_code as pc
                    INNER JOIN division as d ON d.id = pc.id_division
                    INNER JOIN position as p ON p.id = pc.id_position
                    WHERE pc.id_division $comparador $dependecy
                    AND p.leader = 1
                    AND pc.end_date IS NULL;";
                
            }elseif($levelPosition == 0){

                $sql = "SELECT pc.position_code as position_code
                    FROM position_code as pc
                    INNER JOIN division as d ON d.id = pc.id_division
                    INNER JOIN position as p ON p.id = pc.id_position
                    WHERE pc.id_division = $division
                    AND p.leader = 1
                    AND pc.end_date IS NULL;";
            }

            $modelStart = PositionCode::model()->findBySql($sql);
            if($modelStart == NULL){
                $modelStart = NULL;
            }else{
                if($modelStart->position_code == NULL){
                    $codePosition = NULL;
                }else{
                    $codePosition = $modelStart->position_code;
                }
            }

            if($modelStart == NULL){
                $position_code = '1';
            }else{
                $sql = "SELECT MAX(cast(regexp_replace(pc.position_code, '$codePosition.' , '') as int))+1 as position_code
                        FROM position_code as pc
                        INNER JOIN division as d ON d.id = pc.id_division
                        INNER JOIN position as p ON p.id = pc.id_position
                        INNER JOIN employee as e ON e.id = pc.id_employee
                        WHERE pc.position_code LIKE ANY ('{\"$codePosition._\", \"$codePosition.__\"}')
                        AND pc.end_date IS NULL;";
                
                $modelEnd = PositionCode::model()->findBySql($sql);
                if($modelEnd == NULL){
                    $modelEnd = NULL;
                    $newNumber = 1;
                }else{
                    if($modelEnd->position_code == NULL){
                        $newNumber = 1;
                    }else{
                        $newNumber = $modelEnd->position_code;
                    }
                }        
  
                $position_code = $codePosition.'.'.$newNumber;
               }
        }else{
            $position_code = $vacantPositionCode;
        }
        
        if($check == true){
            echo json_encode($position_code);
        }else{
            return $position_code;
        }

    }
    
    public function actionGetVacantPositionCode() 
    {
        $division = $_GET['id_division'];
        $position = $_GET['id_position'];
        $employee = $_GET['id_employee'];
        
        $modelEMployee = Employee::model()->find("id = $employee");
        
        if($modelEMployee == NULL){
            return false;
        }else{
            if($modelEMployee->first_name == 'Vacante'){
                return false;
            }else{
                $sql = "SELECT pc.position_code 
                        FROM position_code pc 
                        INNER JOIN division as d ON d.id = pc.id_division 
                        INNER JOIN position as p ON p.id = pc.id_position 
                        INNER JOIN employee as e ON e.id = pc.id_employee 
                        WHERE pc.id_division = $division 
                        AND pc.id_position = $position     
                        AND e.first_name = 'Vacante' 
                        AND pc.end_date IS NULL;";

                $modelPositionCode = PositionCode::model()->findBySql($sql);

                if($modelPositionCode == NULL){
                    $oldPositionCode = NULL;
                }else{
                    if($modelPositionCode->position_code == NULL){
                         $oldPositionCode = NULL;
                    }else{
                         $oldPositionCode = $modelPositionCode->position_code;
                    }
                }

                if($oldPositionCode == NULL){
                    return false;
                }else{
                    return $oldPositionCode;
                }
            }
        }
    }
    
    
    /**
     * Validación para la existencia del empleado en la tabla position_code.
     * @param int $employee, date $startDate.
     * @return boolean true(Empleado Existe), false(Empleado no Existe).
     */
    public function actionCheckNewEmployee()
    { 
        $employee = $_GET['id_employee'];
        $startDate = date('Y-m-d',  strtotime($_GET['start_date']));

        $modelEmployeeExist = PositionCode::model()->findBySql("SELECT * FROM position_code WHERE id_employee = $employee ORDER BY start_date DESC LIMIT 1;");
        $modelEmployee = Employee::model()->find("id = $employee");
        
        if($modelEmployeeExist == NULL){
            echo json_encode(false);
        }else{
            $endDate = $modelEmployeeExist->end_date;
            if($endDate == NULL){
                $employeeName = $modelEmployee->first_name;
                if($employeeName == 'Vacante' || $employeeName == 'No' || $employeeName == 'Departamento'){
                    echo json_encode(false);
                }else{
                    echo json_encode(true);
                }
            }elseif($endDate != NULL && $startDate <= $endDate){
                echo json_encode($endDate);
            }elseif($endDate != NULL && $startDate > $endDate){
                echo json_encode(false);
            }
        }
    }
    
    
    /**
     * Validación para la existencia del lider en la tabla position_code dependiendo de la division.
     * @param int $division, int $position.
     * @return boolean true(Existe un Lider), false(No Existe el Lider).
     */
    public function actionCheckLeaderExist()
    { 
        $division = $_GET['id_division'];
        $check = $_GET['checkLeader'];
        $LevelPosition = Position::getModelPositionByDivision($division);
        
        if($check == "true"){
            $position = $_GET['id_position'];
            $verificarCargo = Position::getModelPosition($position);

            if($LevelPosition != NULL)
            {
                if($LevelPosition[0]->leader != 0)
                    echo json_encode(true);
                else
                    echo json_encode(false);
            }
            else
            {
                if($verificarCargo->leader != 0)
                    echo json_encode(true);
                else
                    echo json_encode(false);
            }
        }elseif($check == "false"){
            if($LevelPosition != NULL)
            {
                if($LevelPosition[0]->leader != 0)
                    echo json_encode(true);
                else
                    echo json_encode(false);
            }else{
                echo json_encode(false);
            }
        }
        
    }
    
    public function actionCheckLeaderByDivision()
    { 
        $division = $_GET['id_division'];
        $LevelPosition = Position::getModelPositionByDivision($division);

        if($LevelPosition != NULL)
        {
            if($LevelPosition[0]->leader != 0)
                echo json_encode(true);
            else
                echo json_encode(false);
        }
        else
        {
            echo json_encode(false);
        }

    }
    
    public function actionCheckLeaderByPosition()
    { 
        $position = $_GET['id_position'];
        $verificarCargo = Position::getModelPosition($position);

        if($verificarCargo->leader != 0){
            echo json_encode(true);
        }else{
            echo json_encode(false);
        }    
    }
    
    public function actionCheckDependencyByDivision() {
        
        $check = $_GET['checkDivision'];
        $division = $_GET['id_division'];
        $position = $_GET['id_position'];

        if($check == 'true'){
            $divisionDependecy = Division::model()->findBySql("select id_dependency from division where id = $division;")->id_dependency;
            $modelPositionCodeDependency = PositionCode::model()->find("id_division = $divisionDependecy");
        }elseif($check == 'false'){
            if(!isset($division)){
                echo json_encode(true);
            }else{
                $modelPositionCodeDependency = PositionCode::model()->find("id_division = $division");
            }
        }
        
        if($position != 'false'){
            $positionName = Position::model()->find("id = $position")->name;
            if($modelPositionCodeDependency == NULL && $positionName == 'Presidente'){
                echo json_encode(true);
            }elseif($modelPositionCodeDependency == NULL && $positionName != 'Presidente'){
                echo json_encode(false);
            }elseif($modelPositionCodeDependency != NULL && $positionName != 'Presidente'){
                echo json_encode(true);
            }
        }else{
            if($modelPositionCodeDependency == NULL){
                echo json_encode(false);
            }else{
                echo json_encode(true);
            }
        }

    }

    public function actionSetEmployeeVacant() {
        $employee = $_GET['id_employee'];
        $division = $_GET['id_division'];
        $position = $_GET['id_position'];

        $modelEmployeeExist = PositionCode::model()->find("id_division = $division 
                                                           AND id_position = $position 
                                                           AND id_employee = $employee 
                                                           AND end_date IS NULL");
        $modelEmployeeVacant = Employee::getVacantId();
        
        if($modelEmployeeExist == NULL){
            echo json_encode(false);
        }else{
            $modelEmployeeExist->id_employee = $modelEmployeeVacant;
            if($modelEmployeeExist->save()){
                echo json_encode(true);
            }else{
                echo json_encode(false);
            }
        }
    }
    
    
    public function actionEmployeeByDivision()
    {
        $dato = '<option value="">Seleccione uno</option>';
    
        $data = PositionCode::getEmployeePositionCode($_POST['departemento']);
     
     
        foreach ($data as $value => $name)
        {
            $dato.= "<option value='$value'>" . CHtml::encode($name) . "</option>";
//            var_dump($name);
        }
        echo $dato;
    }
    
    public function actionUpdatePositionCode() {
        
        $positionCode = $_GET['position_code'];
        $employee = $_GET['id_employee'];
        $starDate = $_GET['start_date'];
        
        $model = PositionCode::model()->find("position_code = '$positionCode' AND end_date IS NULL");
        if($employee != 'EmployeeEmpty'){
            $model->id_employee = $employee;
        }
        
        if($starDate != 'StarDateEmpty'){
            $model->start_date = $starDate;
        }
        
        if($employee != 'EmployeeEmpty' || $starDate != 'StarDateEmpty'){
            if($model->save()){
                echo json_encode(true);
            }else{
                echo json_encode(false);
            }
        }elseif($employee == 'EmployeeEmpty' && $starDate == 'StarDateEmpty'){
            echo json_encode('NotDataUpdate');
        }
    }
    
    public function actionActivePositionCode() {
        
        $id = $_GET['id'];
        $model = PositionCode::model()->findByPk($id);
        $model->end_date = NULL;
        if($model->save()){
            echo json_encode(true);
        }else{
            echo json_encode(false);
        }
    }
    
    public function actionSetEndDate() {
        $employee = $_GET['id_employee'];
        $position = $_GET['id_position'];
        $endDate = date('Y-m-d');
        
        $modelCheckPositionLeader = Position::model()->find("id = $position")->leader;
        $modelEmployeeVacant = Employee::getVacantId();
        $modelEmployeeExist = PositionCode::model()->findBySql("SELECT * FROM position_code WHERE id_employee = $employee ORDER BY start_date DESC LIMIT 1;");
        
        if($modelEmployeeExist == NULL){
            echo json_encode(false);
        }else{
            
            if($modelCheckPositionLeader == '1'){
                $modelNewEmployeePC = new PositionCode;
                $modelNewEmployeePC->id_employee = $modelEmployeeVacant;
                $modelNewEmployeePC->id_position = $position;
                $modelNewEmployeePC->id_division = $modelEmployeeExist->id_division;
                $modelNewEmployeePC->position_code = $modelEmployeeExist->position_code;
                $modelNewEmployeePC->start_date = $endDate;
                if($modelNewEmployeePC->save()){
                    $checkNewEmployee = true;
                }else{
                    $checkNewEmployee = false;
                }
            }else{
                $checkNewEmployee = true;
            }
            
            $modelEmployeeExist->end_date = $endDate;
            if($modelEmployeeExist->save() && $checkNewEmployee == true){
                echo json_encode(true);
            }else{
                echo json_encode(false);
            }
        }
    }
    
    function actionDeletePositionCode() {
        
         $positionCode = $_GET['position_code'];
         $model = PositionCode::model()->find("position_code = '$positionCode' AND end_date IS NULL");
         $employee = $model->id_employee;
         $division = $model->id_division;
         $position = $model->id_position;
         $countDelete = $countArray = 0;
         $arrayReturn = Array();
         
         $modelCheckPositionLeader = Position::model()->find("id = $position")->leader;
         if($modelCheckPositionLeader == 1){
             $serchPC = "position_code LIKE '$positionCode%'";
         }else{
             $serchPC = "position_code = '$positionCode'";
         }
         
         $modelCheck = PositionCode::model()->findAll($serchPC);
         $countAll = count($modelCheck);
         foreach ($modelCheck as $key => $value) {
             $modelDelete = PositionCode::model()->deleteByPk($value->id);
             if($modelDelete != 0){
                 $countDelete = $countDelete + $modelDelete;
             }else{
                 $arrayReturn[$countArray] = $value->position_code;
                 $countArray++;        
             }
         }
         
         if($countDelete == $countAll){
           echo  json_encode(true);
         }else{
             if(count($arrayReturn) > 0){
               echo  json_encode($arrayReturn);
             }else{
               echo  json_encode(false);
             }
         }
    }
    
    
    
}