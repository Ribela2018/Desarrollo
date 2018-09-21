<?php
/**
 * @package components
 * @version 1.0
 */
class sendNotificationHourAutomatico extends CApplicationComponent
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
        date_default_timezone_set("America/Caracas"); 
        $hour = date('H:i');
        $day = date('L');
        $date = date('Y-m-d');
        $sql = "SELECT eh.id, eh.id_employee, coalesce(u.email,'Empty') as email, coalesce(e.email_company,'Empty') as email_company,
                        (e.first_name || ' ' || e.last_name) as employee, 
                        coalesce(h.start_time::text,'Empty') as start_time, 
                        coalesce(eh.start_break::text,'Empty') as start_break, 
                        coalesce((CASE					    
					WHEN (z.id_type_event IS NOT NULL AND z.id_type_event = 2 AND eh.id_employee = z.id_employee)
					   THEN (x.hour_event::interval + '01:00:00'::interval)::text
					ELSE   
					   eh.end_break::text 
                                  END),'Empty') as end_break, 
                                  
                        coalesce((CASE					    
					WHEN (z.id_type_event IS NOT NULL AND z.id_type_event IN(1,2,3) AND eh.id_employee = z.id_employee)
					   THEN (((CASE
							WHEN (y.hour_event::time > h.start_time::time)
							   THEN (y.hour_event::interval - h.start_time::interval)::interval
							ELSE   
							   '00:00:00'::interval
						   END) + (a.hour_event::interval - z.hour_event::interval) - ('01:00:00'::interval) + (h.end_time::interval))::time)::text
					ELSE   
					   h.end_time::text
                                  END),'Empty') as end_time, 
                        
                        coalesce((CASE                
				  WHEN (y.id_type_event IS NOT NULL AND y.id_type_event = 1 AND eh.id_employee = y.id_employee)
				    THEN 'Empty'::text
				  ELSE  
				    (h.start_time::interval - '00:15:00'::interval)::text
                                  END),'Empty') as start_time_first, 
                        coalesce((CASE 
				  WHEN (z.id_type_event IS NOT NULL AND z.id_type_event = 2 AND eh.id_employee = z.id_employee)
				    THEN 'Empty'::text
				  ELSE  
				    (eh.start_break::interval - '00:15:00'::interval)::text
                                  END),'Empty') as start_break_first, 
                        coalesce((CASE 
				  WHEN (a.id_type_event IS NOT NULL AND a.id_type_event = 3 AND eh.id_employee = a.id_employee)
				    THEN 'Empty'::text
				  ELSE  
					(CASE					    
					    WHEN (z.id_type_event IS NOT NULL AND z.id_type_event = 2 AND eh.id_employee = z.id_employee)
					       THEN ((x.hour_event::interval + '01:00:00'::interval) - '00:15:00'::interval)::text
					    ELSE   
					      (eh.end_break::interval - '00:15:00'::interval)::text 
					END)
                                  END),'Empty') as end_break_first,          
                        coalesce((CASE 
				  WHEN (b.id_type_event IS NOT NULL AND b.id_type_event = 4 AND eh.id_employee = b.id_employee)
				    THEN 'Empty'::text
				  ELSE
					(CASE					    
						WHEN (z.id_type_event IS NOT NULL AND z.id_type_event IN(1,2,3) AND eh.id_employee = z.id_employee)
						   THEN (((CASE
								WHEN (y.hour_event::time > h.start_time::time)
								   THEN (y.hour_event::interval - h.start_time::interval)::interval
								ELSE   
								   '00:00:00'::interval
							 END) + (a.hour_event::interval - z.hour_event::interval) - ('01:00:00'::interval) + (h.end_time::interval) - '00:15:00'::interval)::time)::text
						ELSE   
						   h.end_time::text
					 END)::text
                                  END),'Empty') as end_time_first
                 FROM employee_hour eh
                 INNER JOIN employee e ON e.id = eh.id_employee
                 INNER JOIN position_code pc ON e.id = pc.id_employee
                 INNER JOIN hour h ON h.id = eh.id_hours
                 LEFT JOIN users u ON u.id_employee = e.id
                 LEFT JOIN
			(SELECT ee.id_type_event, ee.hour_event, ee.id_employee 
			 FROM event_employee ee 
			 WHERE ee.id_type_event = 2 AND ee.date = current_date) as x
                 ON eh.id_employee = x.id_employee
		 LEFT JOIN
			(SELECT ee.id, ee.id_type_event, ee.id_employee, ee.hour_event, ee.date
			 FROM event_employee ee
			 INNER JOIN (SELECT MAX(ee.id) as id, ee.id_type_event, ee.id_employee 
			             FROM event_employee ee 
			             WHERE ee.date = current_date AND ee.id_type_event = 1
				     GROUP BY ee.id_type_event, ee.id_employee
				     ORDER BY ee.id_employee, ee.id_type_event) as x 
			 ON x.id = ee.id
			 ORDER BY ee.id_employee, ee.id_type_event) as y
                 ON eh.id_employee = y.id_employee
                 LEFT JOIN
			(SELECT ee.id, ee.id_type_event, ee.id_employee, ee.hour_event, ee.date
			 FROM event_employee ee
			 INNER JOIN (SELECT MAX(ee.id) as id, ee.id_type_event, ee.id_employee 
			             FROM event_employee ee 
			             WHERE ee.date = current_date AND ee.id_type_event = 2
				     GROUP BY ee.id_type_event, ee.id_employee
				     ORDER BY ee.id_employee, ee.id_type_event) as x 
			 ON x.id = ee.id
			 ORDER BY ee.id_employee, ee.id_type_event) as z
                 ON eh.id_employee = z.id_employee
                 LEFT JOIN
			(SELECT ee.id, ee.id_type_event, ee.id_employee, ee.hour_event, ee.date
			 FROM event_employee ee
			 INNER JOIN (SELECT MAX(ee.id) as id, ee.id_type_event, ee.id_employee 
			             FROM event_employee ee 
			             WHERE ee.date = current_date AND ee.id_type_event = 3
				     GROUP BY ee.id_type_event, ee.id_employee
				     ORDER BY ee.id_employee, ee.id_type_event) as x 
			 ON x.id = ee.id
			 ORDER BY ee.id_employee, ee.id_type_event) as a
                 ON eh.id_employee = a.id_employee
                 LEFT JOIN
			(SELECT ee.id, ee.id_type_event, ee.id_employee, ee.hour_event, ee.date
			 FROM event_employee ee
			 INNER JOIN (SELECT MAX(ee.id) as id, ee.id_type_event, ee.id_employee 
			             FROM event_employee ee 
			             WHERE ee.date = current_date AND ee.id_type_event = 4
				     GROUP BY ee.id_type_event, ee.id_employee
				     ORDER BY ee.id_employee, ee.id_type_event) as x 
			 ON x.id = ee.id
			 ORDER BY ee.id_employee, ee.id_type_event) as b
                 ON eh.id_employee = b.id_employee
                 INNER JOIN (select case extract(dow from current_date)
					    when 1 then 'Lunes'
					    when 2 then 'Martes'
					    when 3 then 'Miercoles'
					    when 4 then 'Jueves'
					    when 5 then 'Viernes'
					    when 6 then 'Sabado'
					    else 'Domingo'
				       end ) as diasSemana ON diasSemana.case!='Sabado' AND diasSemana.case!='Domingo'
				 AND (SELECT start_date FROM country_holiday WHERE start_date=CURRENT_DATE) IS NULL
				 WHERE h.start_time IS NOT NULL AND  h.end_time IS NOT NULL AND pc.end_date IS NULL AND e.id NOT IN(160,168,196,208,213,245,202)
				 ORDER BY eh.id_employee;";
        $modelHour = EmployeeHour::model()->findAllBySql($sql);
        
        if($modelHour != NULL){
            foreach ($modelHour as $key => $value) {
                $correo = $value->email;
                $validateMail = strpos($correo, '@');
                if($correo != 'Empty' && $correo != "''" && $correo != NULL && $validateMail == TRUE){
                    $correo = $value->email;
                }else{
                    $correo = $value->email_company;
                    $validateMail = strpos($correo, '@');
                    if($correo != 'Empty' && $correo != "''" && $correo != NULL && $validateMail == TRUE){
                        $correo = $value->email_company;
                    }else{
                        $correo = 'Empty';
                    }
                }
                $body = '';
                $subject = '';
                if($day != 'Saturday' && $day != 'Sunday'){
                    if($value->start_time_first != 'Empty' && date('H:i', strtotime($value->start_time_first)) == $hour){
                        $body = 'Estimado colaborador, le recordamos que debe declarar su inicio de jornada laboral a las '.date('H:i', strtotime($value->start_time));
                        $subject = 'Recordatorio de Declaración / Inicio de Jornada';
                    }
                    if($value->start_break_first != 'Empty' && date('H:i', strtotime($value->start_break_first)) == $hour){
                        $body = 'Estimado colaborador, le recordamos que debe declarar su inicio de descanso a las '.date('H:i', strtotime($value->start_break));
                        $subject = 'Recordatorio de Declaración / Inicio de Descanso';
                    }
                    if($value->end_break_first != 'Empty' && date('H:i', strtotime($value->end_break_first)) == $hour){
                        $body = 'Estimado colaborador, le recordamos que debe declarar su fin de descanso a las '.date('H:i', strtotime($value->end_break));
                        $subject = 'Recordatorio de Declaración / Fin de Descanso';
                    }
                    if($value->end_time_first != 'Empty' && date('H:i', strtotime($value->end_time_first)) == $hour){
                        $body = 'Estimado colaborador, le recordamos que debe declarar su fin de jornada laboral a las '.date('H:i', strtotime($value->end_time));
                        $subject = 'Recordatorio de Declaración / Fin de Jornada';
                    }
                    if($body != '' && $subject != '' && $correo != 'Empty'){
                        Yii::app()->email->sendEmail($body,$correo,$subject,NULL);
                    }
                }
            }
        }
    }
}
