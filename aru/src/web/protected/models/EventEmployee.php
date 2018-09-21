<?php

/**
 * This is the model class for table "event_employee".
 *
 * The followings are the available columns in table 'event_employee':
 * @property integer $id
 * @property integer $id_employee
 * @property string $date
 * @property string $hour_event
 * @property integer $id_type_event
 * @property integer $id_location
 *
 * The followings are the available model relations:
 * @property Employee $idEmployee
 * @property TypeEvent $idTypeEvent
 * @property Location $idLocation
 * @property Guard
 */
class EventEmployee extends CActiveRecord
{
    public $confir_pass;
    public $date_event;
    public $time;
    public $difstart;
    public $difbreak;
    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'event_employee';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_employee, id_type_event, id_location, guard', 'numerical', 'integerOnly'=>true),
			array('date, hour_event', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_employee, date, hour_event, id_type_event, id_location, guard', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idEmployee' => array(self::BELONGS_TO, 'Employee', 'id_employee'),
			'idTypeEvent' => array(self::BELONGS_TO, 'TypeEvent', 'id_type_event'),
			'idLocation' => array(self::BELONGS_TO, 'Location', 'id_location'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_employee' => 'Id Employee',
			'date' => 'Date',
			'hour_event' => 'Hour Event',
			'id_type_event' => 'Id Type Event',
			'id_location' => 'Id Location',
			'guard' => 'Guard',
		
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('id_employee',$this->id_employee);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('hour_event',$this->hour_event,true);
		$criteria->compare('id_type_event',$this->id_type_event);
		$criteria->compare('id_location',$this->id_location);
                $criteria->compare('guard',$this->guard);
	

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EventEmployee the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

       /**
        * funcion para consultar todos los ultimos eventos de declaracion de un empleado, retorna FALSE si no hay eventos declarados y sino retorna un arreglo con id de eventos, hora de eventos y fecha
        * @param type $id
        * @param type $date
        * @return boolean
        */
        public static function getWorkday($id,$date)
        {
            $eventos = array();
            $model = self::model()->findAll('id_employee=:id AND date>=:date  ORDER BY id_type_event ASC limit 1', array(':id' => $id, ':date' => $date));  
            if ($model != NULL) {
                foreach ($model as $value) {
                    $eventos[] = array(
                        'event' => $value->id_type_event,
                        'hour' => $value->hour_event,
                        'date' => $value->date
                    );
                }
                return $eventos;
            } else {
                return false;
            }
        }
       public static function getHoursEmployee($id,$date)
        {
            $eventos = array();
            $modelEvent = TypeEvent::model()->findAll();
            $count = 0;
            foreach ($modelEvent as $key => $value) {
                $model = self::model()->find('id_employee=:id AND date>=:date AND id_type_event=:id_type ORDER BY id_type_event ASC', array(':id' => $id, ':date' => $date, ':id_type' => $value->id));  
                if($model != NULL){
                    $typeEvent = $model->id_type_event;
                    $hourEvent = $model->hour_event;
                    $dateEvent = $model->date;
                    $eventos[$count] = array(
                        'event' => $typeEvent,
                        'hour' => $hourEvent,
                        'date' => $dateEvent
                    );
                }else{
                    $eventos[$count] = NULL;
                }
                $count++;
            }
            
            return $eventos;
        }
        
        public static function getWorkdayEmployee($id,$date)
        {
            $eventos = array();
            $modelEvent = TypeEvent::model()->findAll("id!=5");
            $count = 0;
            foreach ($modelEvent as $key => $value) {
                
                if($value->id == 1){
                    $model = self::model()->findBySql('SELECT * FROM event_employee WHERE id_employee=:id AND date=:date AND id_type_event=:id_type ORDER BY date DESC, hour_event DESC;', array(':id' => $id, ':date' => $date, ':id_type' => 1));  
                    if($model != NULL){
                        $idIJ = $model->id;
                    }
                }else{
                    if(isset($idIJ)){
                    $tomorrowDayEvent = date("Y-m-d", strtotime("+1 day", strtotime($date)));

                    $idNextFE =  EventEmployee::model()->findBySql("SELECT * 
                                                                FROM event_employee 
                                                                WHERE id_employee = $id AND id > $idIJ AND id_type_event = 1
                                                                AND date = '$tomorrowDayEvent';");
                    if($idNextFE != NULL){
                        $idNextFE = $idNextFE->id;
                        $model = self::model()->findBySql("SELECT * 
                                                            FROM event_employee 
                                                            WHERE id_employee = $id AND id > $idIJ AND id < $idNextFE AND id_type_event = $value->id
                                                            AND (date >= '$date' AND date <= '$tomorrowDayEvent')
                                                            ORDER BY date ASC, hour_event ASC LIMIT 1;"); 
                    }else{
                        $model = self::model()->findBySql("SELECT * 
                                                            FROM event_employee 
                                                            WHERE id_employee = $id AND id > $idIJ AND id_type_event = $value->id
                                                            AND (date >= '$date' AND date <= '$tomorrowDayEvent')
                                                            ORDER BY date ASC, hour_event ASC LIMIT 1;"); 
                    }

                    }else{
                        $model = NULL;
                    }
                }

                if($model != NULL){
                    $typeEvent = $model->id_type_event;
                    $hourEvent = date("H:i", strtotime($model->hour_event));
                    $dateEvent = $model->date;
                    $eventos[$count] = array(
                        'event' => $typeEvent,
                        'hour' => $hourEvent,
                        'date' => $dateEvent
                    );
                }else{
                    $eventos[$count] = array(
                        'event' => $value->id,
                        'hour' => 'Empty',
                        'date' => 'Empty'
                    );
                }
                $count++;
            }
            
            return $eventos;
        }
        
        public static function getWorkdayotra($id,$date)
        {
            $eventos = array();
     
            $rangofecha = strtotime ( '+1 day' , strtotime ( $date ) ) ;
            $rangofecha = date ( 'Y-m-d' , $rangofecha );

            $consulta="select * from event_employee dd , (select id from event_employee dd where dd.date='".$date."' and dd.id_employee='".$id."' and id_type_event=1 order by id limit 1) x
			where id_employee='".$id."' and dd.date>='".$date."' and dd.date<='".$rangofecha."' and dd.id>=x.id and guard is not null;";
            $model = self::model()->findAllBySql($consulta);  
        
            if ($model != NULL) {
                foreach ($model as $value) {
                    $eventos[] = array(
                        'event' => $value->id_type_event,
                        'hour' => $value->hour_event,
                        'date' => $value->date
                    );
                }
                return $eventos;
            } else {
                return false;
            }
        }
        public static function getWorkdayprueba($id,$date)
        {
            $eventos = array();
            $model = self::model()->findAll('id_employee=:id AND date>=:date', array(':id' => $id, ':date' => $date));  
            if ($model != NULL) {
                foreach ($model as $value) {
                    $eventos[] = array(
                        'event' => $value->id_type_event,
                        'hour' => $value->hour_event,
                        'date' => $value->date
                    );
                }
                return $eventos;
            } else {
                return false;
            }
        }
        public static function getWorkdaynormal($id,$date)
        {
            $rangofecha = strtotime ( '+1 day' , strtotime ( $date ) ) ;
            $rangofecha = date ( 'Y-m-j' , $rangofecha );
            $eventos = array();
            $consulta="select * from event_employee dd where id_employee=".$id." and dd.date>='".$date."' and dd.date<='".$rangofecha."' and guard is null";
            $model = self::model()->findAllBySql($consulta);  
            if ($model != NULL) {
                foreach ($model as $value) {
                    $eventos[] = array(
                        'event' => $value->id_type_event,
                        'hour' => $value->hour_event,
                        'date' => $value->date
                    );
                }
                return $eventos;
            } else {
                return false;
            }
        }
        public static function getWorkdaylistadoUltimaDeclare($id,$date)
        {
            $eventos = array();
            $consulta="select * from event_employee dd , (select id from event_employee dd where  dd.id_employee=".$id." and id_type_event=1 order by id DESC limit 1) x
			where id_employee=".$id." and dd.date<='".$date."' and dd.id>=x.id  order by dd.id ASC;";
            $model = self::model()->findAllBySql($consulta);  
            if ($model != NULL) {
                
                foreach ($model as $value) {
                    $eventos[] = array(
                        'event' => $value->id_type_event,
                        'hour' => $value->hour_event,
                        'date' => $value->date
                    );
                }
                return $eventos;
            } else {
                return false;
            }
       
        }
    
    /**
     * funcion que recibe el id del empleado, selecciona la maxima fecha y el maximo tipo evento retornando un arreglo de nombre del evento y el id del evento
     * @param type $id
     * @return type
     */
    public static function getSearchStatus($id)
    {   
        $evento=false;
        $consulta="select  ev.id_employee ,ev.date, ev.hour_event, ev.id_type_event
                        from
                  
                                        employee e, users u, event_employee ev, type_event t,
                                        (select id_employee, MAX(date) as date
                                        from event_employee 
                                        group by id_employee ) x,

                                        (select id_employee, date, MAX(id_type_event) as hour
                                        from event_employee
                                        group by id_employee, date
                                        order by id_employee) y

                                        where x.id_employee=y.id_employee and x.date = y.date and
                                        x.id_employee = e.id and u.id_employee = e.id and u.id_status NOT IN(2,3) and 
                                        ev.id_employee=e.id and ev.date=x.date and ev.id_type_event=y.hour and ev.id_type_event = t.id and e.id=".$id." ";
        $consut=self::model()->findBySql($consulta);
        if($consut!=NULL)
        {
            $evento=array(
                'name'=>$consut->idTypeEvent->name,
                'id_type_event'=>$consut->id_type_event
                    );
        }
       
        return $evento;
   }
   
   /**
    * funcion que recibe como parametro el id del tipo de evento y retorna un estilo
    * @param type $id_event
    * @return type
    */
   public static function getStilo($id_event)
   {
       if ($id_event==1){ return $stilo="label-success";}
       if ($id_event==2){ return $stilo="label-warning";}
       if ($id_event==3){ return $stilo="label-info";}
       if ($id_event==4){ return $stilo="label-danger";}
       if ($id_event==5){ return $stilo="label-default";}
   }
   
   
    /**
     * funcion que recibe id del empleado y retorna por medio de la fecha los ultimos eventos declarados de un empleado
     * @param type $id
     * @return null
     */
        

        public static function getEventosHoras($id)
        {
            $date=date('Ymd');
            $model=  EventEmployee::getMaxDateMinHour($id);
            if($model){
//                $eventosOld=EventEmployee::getWorkday($id, $model->date);
//                $validate_hour=EventEmployee::getValidate_hour($eventosOld[0]['hour'], $eventosOld[0]['date']);

                 $eventos=EventEmployee::getWorkdaylistadoUltimaDeclare($id, $model->date);

            }else{
                $eventos[]=NULL;
            }
            return $eventos;

        }
        
        
        /**
         * funcion que retorna el calculo de horas. recibe como paramatro la primera hora de declaracion y retorna el calculo de horas y dias
         * @param type $start
         * @param type $date
         * @return boolean
         */
        
        public static function getValidate_hour($start, $date)
        {
          
            $calculo_dias= DateManagement::getValidate_hour($start, $date, 16);
              
            $hourclient=DateManagement::gethourcliente();
          
            $fecha=date('Ymd');
            $calculo= strtotime($calculo_dias[0]." ".$calculo_dias[1]);
            $actual= strtotime($fecha." ".$hourclient);
//            var_dump("fecha calculada ".$calculo_dias[0]);
//            var_dump("hora calcualda ".$calculo_dias[1]);
//            var_dump("fecha actual ".$fecha);
//            var_dump("hora actual ".$hourclient);
       
            
          
            if ($actual<=$calculo)
                {
                    return true;
                }

                else{
                    return false;
                }
        }     
        

    /**
        * funcion que retorna los id de los empleados que cumplen con la condicion 16 horas
        * @param type $id
        * @param type $date
        * @param type $hour
        * @return type
        * 
        * 
        */     
            
      public static function getfiltro($id, $date, $hour)
        {
         $calculo_dias= DateManagement::getFiltroEmployee($hour, $date, 16);
         $hourclient=DateManagement::gethourcliente();
         $filtroId=NULL;
         $fecha=date('Ymd');
         
        $calculo= strtotime($calculo_dias[0]. " ". $calculo_dias[1]);
        $actual= strtotime($fecha ." ". $hourclient );

         if ($actual <= $calculo)
             {
             $filtroId=$id;
                    
             }
         else {
                 return false;
              }
             
             return $filtroId;
 
        }
        
        /**
         * funcion para retornar la maxima fecha con la minima hora, con id de eventos tipo 1
         * @param type $id
         * @return boolean
         * 
         * 
         */
        
    public static function getMaxDateMinHour($id){
        $model=  EventEmployee::model()->findBySql("select  e.id ,ev.date, ev.hour_event, ev.id_type_event
                    from
                    employee e, users u, event_employee ev, type_event t,
                    (select id_employee, MAX(date) as date
                    from event_employee
                    where  id_type_event = 1
                    group by id_employee ) x,

                    (select id_employee, date, MIN(hour_event) as hour
                    from event_employee
                    where id_type_event = 1
                    group by id_employee, date
                    order by id_employee) y

                    where x.id_employee=y.id_employee and x.date = y.date and
                    x.id_employee = e.id and u.id_employee = e.id and u.id_status = 1 and 
                    ev.id_employee=e.id and ev.date=x.date and ev.hour_event=y.hour and ev.id_type_event = t.id and e.id=".$id." ");
        if($model!=NULL){
            return $model;
        }else{
            return false;
        }
    }
    
    public static function getColorByHours($modelEvent,$startHourEmployee,$endHourEmployee,$date,$export) 
    {
        $arrayData = Array();
        $tableContent = '';
        $style = '';
        
        $arrayL = Hour::getLeterHour();
        
        if($startHourEmployee != 'NotAsingStart' || $endHourEmployee != 'NotAsingEnd'){
        
            $starHourF = date("H:i", strtotime($startHourEmployee ."+ 15 minutes"));
            $endHourF = date("H:i", strtotime($endHourEmployee ."+ 15 minutes"));
            $journy = date('H:i', strtotime('-1 hour', (strtotime('00:00:00') + strtotime($endHourEmployee) - strtotime($startHourEmployee))));
            $today = date("l",strtotime($date));
            $hourStartWork = $modelEvent[0]['hour'];
            $hourStartBreak = $modelEvent[1]['hour'];
            $hourEndBreak = $modelEvent[2]['hour'];
            $hourEndWork = $modelEvent[3]['hour'];

            if($hourStartWork == 'Empty' || $hourEndWork == 'Empty'){
                if($hourStartWork == 'Empty' && $hourEndWork == 'Empty'){
                    if($today == 'Saturday'){
                        $tableContent .= "S";
                        $style = 'background-color: #D2D2B4;';
                    }elseif ($today == 'Sunday') {
                        $style = 'background-color: #D2D2B4;';
                        $tableContent .= "D";
                    }else{
                        $style = 'background-color: #F95F5F;';
                        $tableContent .= 'ND/A';
                    }
                }elseif($hourStartWork != 'Empty' && $hourEndWork == 'Empty'){
                    $style = 'background-color: #F95F5F;';
                    $tableContent .= 'NC';
                }elseif($hourStartWork == 'Empty' && $hourEndWork != 'Empty'){
                    $style = 'background-color: #F95F5F;';
                    $tableContent .= 'NC';
                }
            }elseif($hourStartWork != 'Empty' && $hourEndWork != 'Empty'){
                
                $journyEmployee = date('H:i', strtotime('-1 hour', (strtotime('00:00:00') + strtotime($hourEndWork) - strtotime($hourStartWork))));
                if($journyEmployee >= $journy){
                    if($journyEmployee  == $journy){
                        $style = 'background-color: #18D000;';
                        $tableContent .= 'SC';
                    }
                    if($journyEmployee > $journy && $journyEmployee < ($journy+2)){
                        $style = 'background-color: #FFE600;';
                        $tableContent .= 'SCME2';
                    }
                    if($journyEmployee >= ($journy+2)){
                        $style = 'background-color: #6DD7FB;';
                        $tableContent .= 'SCMA2';
                    }
                }elseif($journyEmployee < $journy){
                    $style = 'background-color: #F95F5F;';
                    $tableContent .= 'NC';
                }
//                if($journyEmployee >= $journy){
//                    if((date("H:i", strtotime($startHourEmployee)) >= $hourStartWork || $starHourF >= $hourStartWork) && $hourEndWork == date("H:i", strtotime($endHourEmployee))){
//                        $style = 'background-color: #18D000;';
//                        $tableContent .= 'SC';
//                    }
//                    if((date("H:i", strtotime($startHourEmployee)) >= $hourStartWork || $starHourF >= $hourStartWork) && $hourEndWork > date("H:i", strtotime($endHourEmployee))){
//                        $style = 'background-color: #18D000;';
//                        $tableContent .= 'SCHOT';
//                    }
//                    if($hourStartWork > $starHourF || $hourEndWork < date("H:i", strtotime($endHourEmployee))){
//                        $style = 'background-color: #18D000;';
//                        $tableContent .= 'SCHDE';
//                    }
//                }elseif($journyEmployee < $journy){
//                    $style = 'background-color: #FFE600;';
//                    $tableContent .= 'NC';
//                }
            }

            $arrayData[0] = $style;
            if($tableContent == 'S' || $tableContent == 'D'){
                $arrayData[1] = $tableContent;
            }elseif($tableContent == 'ND/A'){
                $arrayData[1] = 'ND';
            }else{
                if($hourEndWork == 'Empty'){
                    if(is_bool($export) && $export == FALSE){
                        $arrowGreen = "<i class='fa fa-arrow-circle-right' style='font-size: 11px;color: green;'></i> ";
//                        $arrowRed = "<i class='fa fa-arrow-circle-left' style='font-size: 11px;color: red;'></i>";
                        $content = $tableContent."\n"."<div style='font-size: 11px;width: 100%;'>$arrowGreen ".date('h:i a', strtotime($hourStartWork))."</div>";
                        $leter = Hour::model()->find("start_time = '$startHourEmployee' AND end_time = '$endHourEmployee'")->id;                        
                        $content .= "\n".'('.$arrayL[$leter].')';
                    }elseif(is_bool($export) && $export == TRUE){
                        $arrowGreen = "E";
//                        $arrowRed = "S";
                        $content = $tableContent."\n".$arrowGreen." ".date('h:i a', strtotime($hourStartWork));
                        $leter = Hour::model()->find("start_time = '$startHourEmployee' AND end_time = '$endHourEmployee'")->id;                        
                        $content .= "\n".'('.$arrayL[$leter].')';
                    }elseif(!is_bool($export) && $export == 'PDF'){
                        $arrowGreen = "<font style='color: green;font-size: 20px;'>E</font>";
//                        $arrowRed = "<font style='color: red;font-size: 20px;'>S</font>";
                        $content = $tableContent."\n"."<div style='font-size: 20px;width: 100%;'>$arrowGreen ".date('h:i a', strtotime($hourStartWork))."</div>";
                        $leter = Hour::model()->find("start_time = '$startHourEmployee' AND end_time = '$endHourEmployee'")->id;                        
//                        $content .= "\n".'('.$arrayL[$leter].')';
                    }   

                    $arrayData[1] = $content;
                    
                }elseif($hourEndWork != 'Empty'){
                    if(is_bool($export) && $export == FALSE){
                        $arrowGreen = "<i class='fa fa-arrow-circle-right' style='font-size: 11px;color: green;'></i> ";
                        $arrowRed = "<i class='fa fa-arrow-circle-left' style='font-size: 11px;color: red;'></i>";
                        $content = $tableContent."\n"."<div style='font-size: 11px;width: 100%;'>$arrowGreen ".date('h:i a', strtotime($hourStartWork))."</div>\n <div style='font-size: 11px;width: 100%'>$arrowRed ".date('h:i a', strtotime($hourEndWork)).'</div>';
                        $leter = Hour::model()->find("start_time = '$startHourEmployee' AND end_time = '$endHourEmployee'")->id;                        
                        $content .= "\n".'('.$arrayL[$leter].')';
                    }elseif(is_bool($export) && $export == TRUE){
                        $arrowGreen = "E";
                        $arrowRed = "S";
                        $content = $tableContent."\n".$arrowGreen." ".date('h:i a', strtotime($hourStartWork))."\n$arrowRed ".date('h:i a', strtotime($hourEndWork));
                        $leter = Hour::model()->find("start_time = '$startHourEmployee' AND end_time = '$endHourEmployee'")->id;                        
                        $content .= "\n".'('.$arrayL[$leter].')';
                    }elseif(!is_bool($export) && $export == 'PDF'){
                        $arrowGreen = "<font style='color: green;font-size: 20px;'>E</font>";
                        $arrowRed = "<font style='color: red;font-size: 20px;'>S</font>";
                        $content = $tableContent."\n"."<div style='font-size: 20px;width: 100%;'>$arrowGreen ".date('h:i a', strtotime($hourStartWork))."</div>\n <div style='font-size: 20px;width: 100%'>$arrowRed ".date('h:i a', strtotime($hourEndWork)).'</div>';
                        $leter = Hour::model()->find("start_time = '$startHourEmployee' AND end_time = '$endHourEmployee'")->id;                        
//                        $content .= "\n".'('.$arrayL[$leter].')';
                    }   

                    if($modelEvent[0]['date'] == $modelEvent[3]['date']){
                        $arrayData[1] = $content;
                    }else{
                        $arrayData[1] = $content;
                    }
                }
            }
        
        }else{
            
            $style = 'background-color: #2A35FF;';
            $tableContent .= 'No Asignado';
            
            $arrayData[0] = $style;
            $arrayData[1] = $tableContent;
            
        }
        
        return $arrayData;
    }
}
