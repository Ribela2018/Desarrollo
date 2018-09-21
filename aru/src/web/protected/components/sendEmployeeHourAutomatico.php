<?php
/**
 * @package components
 * @version 1.0
 */

ini_set('memory_limit', '-1');
class sendEmployeeHourAutomatico extends CApplicationComponent
{

    public function init() 
    {
       
    }

    /**
     * Ejecuta el comando de ciclo de ingreso automatico
     * @access public
     * @param date $dateSet
     * @author Ramon Ramirez
     */
    public function run($dateSet=null)
    {
        $data = $dataAll = Array();
        $sqlEmployee = "SELECT pc.id_employee as id_employee, e.email_company as email, (e.first_name || ' ' || e.last_name) as employee, d.name as division, p.name as position, pc.start_date 
                        FROM position_code pc 
                        INNER JOIN employee e ON e.id = pc.id_employee
                        INNER JOIN division d ON d.id = pc.id_division
                        INNER JOIN position p ON p.id = pc.id_position
                        WHERE pc.end_date IS NULL AND id_employee NOT IN (SELECT id FROM employee WHERE first_name = 'Vacante' OR first_name = 'No')
                        ORDER BY btrsort(pc.position_code);";    
        $modelEmplotee = PositionCode::model()->findAllBySql($sqlEmployee);  
        $date = date('Y-m-d', time());
        $today = date("l",strtotime($date));
        if($today == 'Monday'){
            $start = date('Y-m-d', strtotime('monday last week'));
            $end = date('Y-m-d', strtotime('sunday last week'));
        }elseif($today == 'Tuesday' || $today == 'Wednesday' || $today == 'Thursday' || $today == 'Friday'){
            $start = date("Y-m-d", strtotime("-1 day", strtotime($date)));  
            $end = date("Y-m-d", strtotime("-1 day", strtotime($date)));          
        }

        if($today == 'Monday' || $today == 'Tuesday' || $today == 'Wednesday' || $today == 'Thursday' || $today == 'Friday'){
            foreach ($modelEmplotee as $key => $value) {
                $count = 0;
                $countId = 0;
                $idEmployee = $value->id_employee;
                $nameEmployee = $value->employee;
                $nameDivision = $value->division;
                $namePosition = $value->position;
                $startDate = $value->start_date;
                $correo = $value->email;
                if($correo == '' || $correo == "''"){
                    $correoModel = Users::model()->find("id_employee = $idEmployee");
                    if($correoModel != NULL){
                        $correo = $correoModel->email;
                    }else{
                        $correo = NULL;
                    }
                }
                $modelAllHours = EmployeeHour::getAllHoursByEmployee($idEmployee);
                if($correo != NULL){
                    $validateMail = strpos($correo, '@');
                }
                if($modelAllHours != 'NotHour' && $validateMail == TRUE){
                    $dataAll[0] = $start;
                    $dataAll[1] = $end;
                    $dataAll[2] = $idEmployee;
                    $data = json_encode($dataAll);
                    $nameFile = $nameEmployee.' ('.$namePosition.')';

                    $files['employeeHoursReport']['name']=$nameFile;
                    $files['employeeHoursReport']['body']=Yii::app()->reportModule->employeeHoursReport($data,$nameFile);
                    $files['employeeHoursReport']['pdf']=Yii::app()->reportModule->employeeHoursReport($data,$nameFile);
                    $files['employeeHoursReport']['dir']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.'ARU Reporte de Jornada Laboral - '.$nameFile.".pdf"; 
                    $nameFile = 'ARU Reporte de Jornada Laboral - '.$nameFile;

                    foreach($files as $key => $file)
                    { 
                        ini_set('max_execution_time', 180);
                        ExportController::genPDF($file['pdf'],$nameFile,true);
                        Yii::app()->email->sendEmail($file['body'][1],$correo,$nameFile,$file['dir']);
                    }
                }
            }  
        }
    }
}
