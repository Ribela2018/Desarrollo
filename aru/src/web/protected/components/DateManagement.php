<?php
/**
 * clase con metodos estaticos para administrar las fechas
 * @version 0.6.1
 * @package components
 */
class DateManagement
{
	
    /**
     * funcion para el calculo de las 16 horas, recibe como parametro hora de inicio de session, fecha y plus, retorna el calculo de las horas y la fecha calculada
     * @param type $start
     * @param type $date
     * @param type $plusHours
     * @return string|boolean
     */
    public static function getValidate_hour($start, $date, $plusHours) {
        //echo $date;
        if ($start != null) {
            $hours = explode(":", $start);
            $hour = $hours[0];
            $min = $hours[1];
            $sec = $hours[2];
            $maxHour = $hour + $plusHours;

            if ($maxHour > 24) {
 
                $nextHour = $maxHour - 24;
                $nuevaFecha = strtotime('+1 day', strtotime($date));
                $nuevaFecha = date('Y-m-d', $nuevaFecha);
                 
                $fechaHora[] = $nuevaFecha;

                if ($nextHour < 10) {
                    $fechaHora[] = "0" . $nextHour . ":" . $min . ":" . $sec;
                    return $fechaHora;
                } else {
                    $fechaHora[] = $nextHour . ":" . $min . ":" . $sec;
                    return $fechaHora;
                }
            } else {
                 
                $fechaHora[] = $date;
                $fechaHora[] = $maxHour . ":" . $min . ":" . $sec;
          
                return $fechaHora;
            }
        } else {
            return false;
        }
    }

    /**
     * funcion para capturar la hora del cliente
     * @return type
     */
    public static function gethourcliente(){
            
            date_default_timezone_set("America/Caracas"); 
            $hour = date('H:i:s',time() - 3600*date('I')); 
            return $hour;
        }
     
        
        /**
         * funcion para calcular las 16 horas, recibe como parametros la hora de inicio de jornada, fecha y un plus de 16 horas para realizar el calculo
         * @param type $start
         * @param type $date
         * @param type $plusHours
         * @return string|boolean
         */
        
         public static function getFiltroEmployee($start, $date, $plusHours) {
        
        if ($start != null) {
            $hours = explode(":", $start);
            $hour = $hours[0];
            $min = $hours[1];
            $sec = $hours[2];
            $maxHour = $hour + $plusHours;

            if ($maxHour > 24) {

                $nextHour = $maxHour - 24;
               
                $nuevaFecha = strtotime('+1 day', strtotime($date));
                $nuevaFecha = date('Y-m-d', $nuevaFecha);
                $fechaHora[] = $nuevaFecha;

                if ($nextHour < 10) {
                    $fechaHora[] = "0" . $nextHour . ":" . $min . ":" . $sec;
                    return $fechaHora;
                } else {
                    $fechaHora[] = $nextHour . ":" . $min . ":" . $sec;
                    return $fechaHora;
                }
            } else {
                $fechaHora[] = $date;
                $fechaHora[] = $maxHour . ":" . $min . ":" . $sec;
                
                return $fechaHora;
            }
        } else {
            return false;
        }
    }

     /**
         * funcion para calcular las 16, para mostrarla en la vista HORA DE DECLARACION
         * @param type $hour
         *
         * @return 
         */

     public static function getFirstEvent($hour)
     {
            $hours = explode(":", $hour);
            $hour = $hours[0];
            $min = $hours[1];
            $sec = $hours[2];
            $maxHour = $hour + 16;
            var_dump($maxHour);
     }
        
      
}
?>