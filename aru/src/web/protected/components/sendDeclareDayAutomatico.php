<?php
/**
 * @package components
 * @version 1.0
 */
class sendDeclareDayAutomatico extends CApplicationComponent
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
        $arrayL = Hour::getLeterHour();
        $newData = $allHours = Array();
        $arrayJD = Array(168,196,208,245,213,202);
        $sqlEmployee = "SELECT pc.id_employee as id_employee, (e.first_name || ' ' || e.last_name) as employee, d.name as division, p.name as position, pc.start_date 
                        FROM position_code pc 
                        INNER JOIN employee e ON e.id = pc.id_employee
                        INNER JOIN division d ON d.id = pc.id_division
                        INNER JOIN position p ON p.id = pc.id_position
                        WHERE pc.end_date IS NULL AND id_employee NOT IN (SELECT id FROM employee WHERE first_name = 'Vacante' OR first_name = 'No')
                        ORDER BY btrsort(pc.position_code);";    
        $modelEmplotee = PositionCode::model()->findAllBySql($sqlEmployee);  
        $start = date('Y-m-d', strtotime('monday last week'));
        $end = date('Y-m-d', strtotime('sunday last week'));

        foreach ($modelEmplotee as $key => $value) {
//
            $count = 0;
            $countId = 0;
            $idEmployee = $value->id_employee;
            $nameEmployee = $value->employee;
            $nameDivision = $value->division;
            $namePosition = $value->position;
            $startDate = $value->start_date;

            $modelHours = EmployeeHour::getHoursByEmployee($idEmployee);
            if($modelHours != 'NotHour'){
                $arrayIds[$idEmployee] = $idEmployee;
                $modelAllHours = EmployeeHour::getAllHoursByEmployee($idEmployee,$start,$end) ;
                if($modelAllHours != 'NotHour'){
                    foreach ($modelAllHours as $keyA => $hour) {

                        $startHourEmployee = $hour[0];
                        $endHourEmployee = $hour[1];
                        $startHourEmployeeF = date('h:i a', strtotime($startHourEmployee));
                        $endHourEmployeeF = date('h:i a', strtotime($endHourEmployee));
                        $allHourFinish = Array();
                        $all = EmployeeHour::getAllHoursByEmployee($idEmployee); 
                        foreach ($all as $keyB => $hour2) {
                            $leter = Hour::model()->find("start_time = '$hour2[0]' AND end_time = '$hour2[1]'")->id;
                            $allHourFinish[$keyB] = date('h:i a', strtotime($hour2[0])).' - '.date('h:i a', strtotime($hour2[1]))." (".$arrayL[$leter].")";
                        }

                        for($date=$start;$date<=$end;$date = date("Y-m-d", strtotime($date ."+ 1 days"))){
                            $allHourFinishDate[$date] = EmployeeHoursDay::getHourByDay($idEmployee,$date);
                        }
                    }
                }else{
                    $modelHoursA = EmployeeHour::getStaticHoursByEmployee($idEmployee);
                    $startHourEmployee = $modelHoursA[0];
                    $endHourEmployee = $modelHoursA[1];
                    $startHourEmployeeF = date('h:i a', strtotime($startHourEmployee));
                    $endHourEmployeeF = date('h:i a', strtotime($endHourEmployee));
                    $allHourFinish = Array();
                    $all = EmployeeHour::getAllHoursByEmployee($idEmployee); 
                    foreach ($all as $keyB => $hour2) {
                        $leter = Hour::model()->find("start_time = '$hour2[0]' AND end_time = '$hour2[1]'")->id;
                        $allHourFinish[$keyB] = date('h:i a', strtotime($hour2[0])).' - '.date('h:i a', strtotime($hour2[1]))." (".$arrayL[$leter].")";
                    }

                    for($date=$start;$date<=$end;$date = date("Y-m-d", strtotime($date ."+ 1 days"))){
                        $allHourFinishDate[$date] = Array($startHourEmployee,$endHourEmployee);
                    }    
                }

                $startHourEmployee = $modelHours[0];
                $endHourEmployee = $modelHours[1];
                $hours = implode("\n", array_unique($allHourFinish));


                for($date=$start;$date<=$end;$date = date("Y-m-d", strtotime($date ."+ 1 days"))){
                    ini_set('max_execution_time', 180);
                    $mEE = new EventEmployee;
                    $arrayData[0] = NULL; 
                    $arrayData[1] = NULL; 
                    $allHours[$idEmployee][0] = Array($nameEmployee,$nameDivision,$namePosition,$startDate,$hours);
                    if(in_array($idEmployee, $arrayJD)){
                        $today = date("l",strtotime($date));
                        if($today == 'Saturday'){
                            $arrayData[1] = "S"; 
                            $arrayData[0] = 'background-color: #D2D2B4;'; 
                        }elseif ($today == 'Sunday') {
                            $arrayData[1] = "D"; 
                            $arrayData[0] = 'background-color: #D2D2B4;'; 
                        }
                        $modelEvent = NULL;
                        $allHours[$idEmployee][1][$count] = $arrayData;
                    }else{
                        $modelEvent = $mEE->getWorkdayEmployee($idEmployee,$date);
                        $allHours[$idEmployee][1][$count] = $mEE->getColorByHours($modelEvent,$allHourFinishDate[$date][0],$allHourFinishDate[$date][1],$date,TRUE);
                    }
                    $count++;
                }
                $countId++;
            }
        }

        $newData[0] = $arrayIds;
        $newData[1] = Array($start,$end);
        $newData[2] = $allHours;
        $body = 'ARU Reporte de Jornada Laboral Desde '.$start.' Hasta '.$end;
        $nameFile = $body;
        $dir = Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$nameFile.".xlsx";
        Yii::app()->report->declareDayEmployee($newData,$nameFile,$dir); 
        Yii::app()->email->sendEmail($body,'rrhh@sacet.biz',$nameFile,$dir);
        
        Yii::app()->report->declareDayEmployee($newData,$nameFile,$dir); 
        Yii::app()->email->sendEmail($body,'gestionli@etelix.com',$nameFile,$dir);
        
        Yii::app()->report->declareDayEmployee($newData,$nameFile,$dir); 
        Yii::app()->email->sendEmail($body,'angelos@sacet.biz',$nameFile,$dir);

        Yii::app()->report->declareDayEmployee($newData,$nameFile,$dir); 
        Yii::app()->email->sendEmail($body,'alvaroquintana@sacet.biz',$nameFile,$dir);
    }
}
