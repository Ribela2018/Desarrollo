<?php
/**
 * @package components
 * @version 1.0
 */
class sendEmployeeHappyBirthdayAutomatico extends CApplicationComponent
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
    public function run()
    {
        $month_current=date('m');
        $day_current=date('d');
     
   
        $correoRRHH="rrhh@sacet.biz";
        $correoSALESOP="salesop@etelix.com";
        $correoSALES="sales@etelix.com";
        $correoAUTO="auto@etelix.com";
        $correoBILLING="billing@etelix.com";
        $correoADMIN="admin@sacet.biz";
        $correoBD="bd@etelix.com";
        $correoLEGAL="legal@etelix.com";
        $correoNOC="noc@etelix.com";
        $correoING="ing@etelix.com";
        $correoPROVISIONING="provisioning@etelix.com";
        $correoMERCADEO="mercadeo@etelix.com";
        $correoCE="comiteejecutivo@etelix.com";
//        $correo="adyp@sacet.biz";

//        $sub="Prueba";
//        $consulta= "SELECT pc.id_employee as id_employee, (e.first_name || ' ' || e.last_name) as employee, d.name as division, p.name as position, pc.start_date 
//                    FROM position_code pc 
//                    INNER JOIN employee e ON e.id = pc.id_employee
//                    INNER JOIN division d ON d.id = pc.id_division
//                    INNER JOIN position p ON p.id = pc.id_position
//                    WHERE pc.end_date IS NULL AND id_employee NOT IN (SELECT id FROM employee WHERE first_name = 'Vacante' OR first_name = 'No')
//                    AND TO_CHAR(date_birth,'MM-DD')='".$month_current."-".$day_current."' 
//               
//                    ORDER BY e.first_name;";
        
        $consulta="SELECT pc.id_employee, (e.first_name || ' ' || e.last_name) as employee, d.name as division, p.name as position, pc.start_date 
                    FROM position_code pc 
                    INNER JOIN employee e ON e.id = pc.id_employee
                    INNER JOIN division d ON d.id = pc.id_division
                    INNER JOIN position p ON p.id = pc.id_position
                    INNER JOIN users us ON us.id_employee=pc.id_employee
                    WHERE pc.end_date IS NULL AND pc.id_employee NOT IN (SELECT id FROM employee WHERE first_name = 'Vacante' OR first_name = 'No')
                    AND TO_CHAR(date_birth,'MM-DD')='".$month_current."-".$day_current."' AND us.id_status!=2

                    ORDER BY e.first_name;";
     

        $model = Employee::model()->findAllBySql($consulta);
        if($model != NULL)
        {
            $nameFile = 'Felicidades en su Cumpleaños - ';
            $nameFilecont = '';
            $arrayEmployee = array();
            foreach($model as $key => $value)
            {
                $nameFilecont.=" " . $value->employee . " (" . $value->position . ") /";
                $arrayEmployee[$key] = Array($value->employee, $value->position);
            }

            $nameFilecont = trim($nameFilecont, '/');
            $namefileBirth = $nameFile . $nameFilecont;
            $html = Yii::app()->reportModule->employeeBirthDayReport($arrayEmployee, $namefileBirth);
            Yii::app()->email->sendEmail($html, $correo, $namefileBirth);
////            Yii::app()->email->sendEmail($html, $correo2, $namefileBirth);
            Yii::app()->email->sendEmail($html, $correoRRHH, $namefileBirth);
            Yii::app()->email->sendEmail($html, $correoSALESOP, $namefileBirth);
            Yii::app()->email->sendEmail($html, $correoSALES, $namefileBirth);
            Yii::app()->email->sendEmail($html, $correoAUTO, $namefileBirth);
            Yii::app()->email->sendEmail($html, $correoBILLING, $namefileBirth);
            Yii::app()->email->sendEmail($html, $correoADMIN, $namefileBirth);
            Yii::app()->email->sendEmail($html, $correoBD, $namefileBirth);
            Yii::app()->email->sendEmail($html, $correoLEGAL, $namefileBirth);
            Yii::app()->email->sendEmail($html, $correoNOC, $namefileBirth);
            Yii::app()->email->sendEmail($html, $correoING, $namefileBirth);
            Yii::app()->email->sendEmail($html, $correoPROVISIONING, $namefileBirth);
            Yii::app()->email->sendEmail($html, $correoMERCADEO, $namefileBirth);
            Yii::app()->email->sendEmail($html, $correoCE, $namefileBirth);
           
         
            
            
        }
       else{
           echo "Sin cumpleaños";
       }
    }


}
