<?php
/**
 * @package components
 * @version 1.0
 */
class sendNotificationHolidayAutomatico extends CApplicationComponent
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
       $date_today=date('Y-m-d');
       $date_tomorrow=strtotime('+1 day', strtotime($date_today));
       $date_tomorrow=date('Y-m-d',$date_tomorrow);

//      $date_today='2015-01-01'; 
//      $date_tomorrow='2015-01-02';
       
       $sql="SELECT h.id , ch.start_date as start_date  , ch.end_date as end_date, h.name as name_holiday , c.name as name_country FROM country_holiday ch 
        INNER JOIN holiday h on h.id=ch.id_holiday 
        INNER JOIN country c on c.code=ch.cod_country 
        WHERE ch.start_date>='{$date_today}' and ch.start_date<='{$date_tomorrow}'
        order by ch.start_date ASC";

        $model = CountryHoliday::model()->findAllBySql($sql);
        $id_holiday=0;
        $addCountry=array();
        $addCountry2=array();
        $cont=$cont2=0;
        if($model!=NULL)
        {
          foreach ($model as $key => $value)
          {
             $data[$key]['id']=$value->id;
             $data[$key]['start_date']=$value->start_date;
             $data[$key]['end_date']=$value->end_date;
             $data[$key]['name_holiday']=$value->name_holiday;
             $data[$key]['name_country']=$value->name_country;
        
             if ($value->start_date==$date_today )
             {
                $addCountry[$cont]['name_country']=$value->name_country;
                $addCountry[$cont]['name_holiday']=$value->name_holiday;
                $addCountry[$cont]['end_date']=$value->end_date;
                $addCountry[$cont]['start_date']=$value->start_date;
                $addCountry[$cont]['id']=$value->id;

                $cont++;
             }
             else{
                if($value->start_date==$date_tomorrow)
                 {
                    $addCountry2[$cont2]['name_country']=$value->name_country;
                    $addCountry2[$cont2]['name_holiday']=$value->name_holiday;
                    $addCountry2[$cont2]['end_date']=$value->end_date;
                    $addCountry2[$cont2]['start_date']=$value->start_date;
                    $addCountry2[$cont2]['id']=$value->id;
                    $cont2++;
                 }
                 else
                  echo "LA NADA";
             }
          }

          if($cont!=0)
          {
            $textHoy="hoy";
            list($html,$country,$dia,$holiday)= Yii::app()->reportModule->employeeHolidayReport($addCountry,$textHoy);
            $arrayFromDia = Array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
            $arrayToDia = Array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");    

            $nameFile = $holiday." en".$country ;
//            Yii::app()->email->sendEmail($html,'corporacion@etelix.com',$nameFile);
//            Yii::app()->email->sendEmail($html,'Socios@etelix.com',$nameFile);
                Yii::app()->email->sendEmail($html, 'rrhh@sacet.biz', $nameFile);
                Yii::app()->email->sendEmail($html, 'salesop@etelix.com', $nameFile);
                Yii::app()->email->sendEmail($html, 'sales@etelix.com', $nameFile);
                Yii::app()->email->sendEmail($html, 'auto@etelix.com', $nameFile);
                Yii::app()->email->sendEmail($html, 'billing@etelix.com', $nameFile);
                Yii::app()->email->sendEmail($html, 'admin@sacet.biz', $nameFile);
                Yii::app()->email->sendEmail($html, 'bd@etelix.com', $nameFile);
                Yii::app()->email->sendEmail($html, 'legal@etelix.com', $nameFile);
                Yii::app()->email->sendEmail($html, 'noc@etelix.com', $nameFile);
                Yii::app()->email->sendEmail($html, 'ing@etelix.com', $nameFile);
                Yii::app()->email->sendEmail($html, 'provisioning@etelix.com', $nameFile);
                Yii::app()->email->sendEmail($html, 'mercadeo@etelix.com', $nameFile);
                Yii::app()->email->sendEmail($html, 'comiteejecutivo@etelix.com', $nameFile);
            }
          if($cont2!=0)
          {
            $textHoy="de mañana";
            list($html,$country,$dia,$holiday)= Yii::app()->reportModule->employeeHolidayReport($addCountry2,$textHoy);
            $arrayFromDia = Array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
            $arrayToDia = Array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
   
            $nameFile = $holiday." en".$country ;
            Yii::app()->email->sendEmail($html,'rrhh@sacet.biz',$nameFile);
            Yii::app()->email->sendEmail($html,'salesop@etelix.com',$nameFile);
            Yii::app()->email->sendEmail($html,'sales@etelix.com',$nameFile);
            Yii::app()->email->sendEmail($html,'auto@etelix.com',$nameFile);
            Yii::app()->email->sendEmail($html,'billing@etelix.com',$nameFile);
            Yii::app()->email->sendEmail($html,'admin@sacet.biz',$nameFile);
            Yii::app()->email->sendEmail($html,'bd@etelix.com',$nameFile);
            Yii::app()->email->sendEmail($html,'legal@etelix.com',$nameFile);
            Yii::app()->email->sendEmail($html,'noc@etelix.com',$nameFile);
            Yii::app()->email->sendEmail($html,'ing@etelix.com',$nameFile);
            Yii::app()->email->sendEmail($html,'provisioning@etelix.com',$nameFile);
            Yii::app()->email->sendEmail($html,'mercadeo@etelix.com',$nameFile);
            Yii::app()->email->sendEmail($html,'comiteejecutivo@etelix.com',$nameFile);
          }
    }else{
        echo "NO ENCONTRO NINGUN REGISTRO";
    }   
  }
}
