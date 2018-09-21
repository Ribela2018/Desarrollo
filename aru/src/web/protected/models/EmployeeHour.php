<?php

/**
 * This is the model class for table "employee_hour".
 *
 * The followings are the available columns in table 'employee_hour':
 * @property integer $id
 * @property integer $id_employee
 * @property integer $id_hours
 *
 * The followings are the available model relations:
 * @property Employee $idEmployee
 * @property Hour $idHours
 */
class EmployeeHour extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
    
        public $typeHour; 
        public $start_time;
        public $end_time;
        public $time_break;
        public $descanso;
        public $descanso1;
        public $date_event;
        public $start_time_first;
        public $end_time_first;
        public $start_break_first;
        public $end_break_first;
        public $email;
        public $email_company;

        public function tableName()
	{
		return 'employee_hour';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_employee, id_hours', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_employee, id_hours', 'safe', 'on'=>'search'),
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
			'idHours' => array(self::BELONGS_TO, 'Hour', 'id_hours'),
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
			'id_hours' => 'Id Hours',
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
		$criteria->compare('id_hours',$this->id_hours);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EmployeeHour the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    
    
      public static function getAllEmployeeByHours() {

            $table = '';
            $columnAction = '';
            $typeHour= new Hour();
            $time_break= new EmployeeHour();
            $arraFrom = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
            $arraTo = Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
            $arrayHeader = Array('CP','Colaborador','División','Posición','Horarios','Opciones');
            $arrayData = Array();
            $arrayHour = Array();
            $counsArrayData = 0;

            $sql = "SELECT (e.first_name || ' ' || e.last_name) as employee, e.id,
                      d.name as division, p.name as position, pc.start_date as start_date_employee, pc.position_code 
                      FROM employee e
                      INNER JOIN position_code pc ON pc.id_employee = e.id
                      INNER JOIN position p ON pc.id_position = p.id
                      INNER JOIN division d ON pc.id_division = d.id
                      WHERE e.first_name != 'Vacante' AND e.first_name != 'No' AND pc.end_date IS NULL
                      /*AND pc.position_code != '1'*/
                      ORDER BY pc.position_code ASC;";
            $modelHourEmployee = Employee::model()->findAllBySql($sql);
            $namePayRoll = 'hoursEmployee';
            $table .= '<table class="table table-striped table-bordered table-hover" id="'.$namePayRoll.'">
                <div style=" margin-left:662px; margin-bottom:20px">
                    <div style="float:left;"  ><label class="control-label letra_empleado" style="margin-top:7px; margin-right:5px">Horarios:  </label></div>
                    <div style="float:left; margin-left:2px"  id="listaHorario"><span>'.CHtml::dropDownList('asignacionHours', $typeHour, Hour::getEmployeeHours(), array("class" => "form-control input-medium select2", "empty" => "Seleccione Una Opción")) .'</span></div>
                    <div><a id="botonAsignacionHorarios"><input type="bottom" id="asignarHorarios" name="asignarHorario" value="Asignar" class="btn green" style="width:85px" /></a></div>
               <div>
               <div>
                    <div style="float:left;"><label class="control-label letra_empleado">Descanso:</label></div>
                    <div style="float:left"><span>'.CHtml::dropDownList('asignacionDescanso', $time_break, self::getHoursBreack(), array("class" => "form-control input-medium select2", "empty" => "Seleccione Una Opción")) .'</span></div>
                    <div style="margin-top:10px"><a id="botonAsignacionDescanso"><input type="bottom" id="asignarDescanso" name="asignarDescanso" value="Asignar" class="btn green" style="width:85px" /></a></div>
               </div>
                          <thead>
                              <tr>
                                  <th class="table-checkbox sorting_disabled">
                                      <input type="checkbox" class="group-checkable" data-set="#'.$namePayRoll.' .checkboxes" id="selectAll"/>
                                  </th>';
                                  foreach ($arrayHeader as $key => $value) {
                                      $table .= "<th>
                                                    $value
                                                </th>";
                                  }
                   $table .= '</tr>
                          </thead>
                          <tbody>';
            
                          foreach ($modelHourEmployee as $key => $value) {
                              $arrayHour = Array();
                              $modelHour= Hour::model()->findAllBySql("SELECT h.id, eh.id_employee, ( to_char(h.start_time, 'HH12:MI am') ||' - '||  to_char(h.end_time, 'HH12:MI am')) as horarios FROM employee_hour eh INNER JOIN hour h ON h.id=eh.id_hours WHERE eh.id_employee=".$value->id.";");
                              if($modelHour != NULL){
                                if(count($modelHour) > 1){
                                    foreach ($modelHour as $key2 => $value2) {
                                        $arrayHour[$key2] = $value2->horarios.' <a id="deleteHourEmployee" style="cursor: pointer;"><div class="idHour" style="display: none;">'.$value2->id.'</div><i class="fa fa-times rojo tooltips" data-original-title="Eliminar Horario"></i></a><br>';
                                    }
                                    $allHours = implode("\n", $arrayHour);
                                }else{
                                    foreach ($modelHour as $key2 => $value2) {
                                        $allHours = $value2->horarios.' <a id="deleteHourEmployee" style="cursor: pointer;"><div class="idHour" style="display: none;">'.$value2->id.'</div><i class="fa fa-times rojo tooltips" data-original-title="Eliminar Horario"></i></a><br>';
                                    }
                                }
                              }else{
                                  $allHours = '';
                              }
                              
                              setlocale(LC_ALL,"es_ES");
//                              if($idPayRoll == 0){
//                                  $dateShow=$value->start_date_employee;
//                                  $columnDate = '<td>'.str_replace($arraTo,$arraFrom,date('d F, Y', strtotime($value->start_date_employee))).'</td>';
//                              }else{
//                                  $dateShow=$value->start_date_employee;
//                                  $columnDate = '<td>'.str_replace($arraTo,$arraFrom,date('d F, Y', strtotime($value->start_date_employee))).'</td>';
////                                  $columnDate = '<td>
////                                                    <input class="form-control input-small date-picker" name="PayrollEmployee[start_date]" id="PayrollEmployee_start_date" type="text" value="'.date('d/m/Y', strtotime($value->start_date_employee)).'"/>    
////                                                </td>';
//                              }
                              
                              $table .= '<tr class="odd gradeX">
                                            <td>
                                                <input type="checkbox" class="checkboxes" value="'.$value->id.'"/>
                                                    <div class="ocultar">
                                                            <div id="nameModalModification">'.$value->employee.'</div>
                                                            <div id="divisionModalModification">"'.$value->division.'"</div>
                                                            <div id="positionModalModification">'.$value->position.'</div>
                                                            
                                                    </div>
                                            </td>
                                            <td>
                                                '.$value->position_code.'
                                            </td>
                                            <td>
                                                '.$value->employee.'
                                            </td>
                                            <td style="width: 410px;">
                                                '.$value->division.'
                                            </td>
                                            <td>
                                                '.$value->position.'
                                            </td>
                                            <td style="width: 150px;">
                                                '.$allHours.'
                                            </td>
                                            <td style="text-align: center;">';
                              
                              if($allHours != ''){
                                $table .= '       <a style="cursor: pointer;" id="updateHourEmployee">
                                                      <i class="fa fa-pencil azul tooltips" data-original-title="Actualizar Horario"></i>
                                                  </a>';
                                $table .= '       <a style="cursor: pointer;" id="asigCalenderHourEmployee">
                                                      <i class="fa fa-calendar verde tooltips" data-original-title="Asignar Horario"></i>
                                                  </a>';
                              }
                              
                              $table .= '   </td>
                                         </tr>';
                          }
                              
               $table .= '</tbody>
                      </table>';
               
               return $table;  
        }
        
        public static function getHoursByEmployee($idEmployee) 
        {
            $arrayHour = Array();
            $model = self::model()->findBySql("SELECT eh.*, h.* FROM employee_hour eh INNER JOIN hour h ON h.id = eh.id_hours WHERE eh.id_employee = $idEmployee AND eh.end_date IS NULL ORDER BY eh.id ASC;");
            if($model != NULL){
                $arrayHour[0] = $model->start_time;
                $arrayHour[1] = $model->end_time;
                $arrayHour[3] = $model->start_break;
                $arrayHour[4] = $model->end_break;
            }else{
                $arrayHour = 'NotHour';
            }
            return $arrayHour;
        }
        
        public static function getStaticHoursByEmployee($idEmployee) 
        {
            $arrayHour = Array();
            $model = self::model()->findBySql("SELECT eh.*, h.* FROM employee_hour eh INNER JOIN hour h ON h.id = eh.id_hours WHERE eh.id_employee = $idEmployee AND eh.end_date IS NULL AND eh.static = TRUE;");
            if($model != NULL){
                $arrayHour[0] = $model->start_time;
                $arrayHour[1] = $model->end_time;
                $arrayHour[3] = $model->start_break;
                $arrayHour[4] = $model->end_break;
            }else{
                $arrayHour = self::getHoursByEmployee($idEmployee);
            }
            return $arrayHour;
        }
        
        public static function getHoursByEmployeeDate($idEmployee,$date) 
        {
            $array = Array();
            if($idEmployee != NULL && $date != NULL){
                $model = self::model()->findBySql("SELECT eh.id, h.start_time, h.end_time
                                                   FROM employee_hour eh
                                                   INNER JOIN hour h ON h.id = eh.id_hours
                                                   INNER JOIN employee_hours_day ehd ON ehd.id_employee_hours = eh.id
                                                   WHERE ehd.start_date = '$date' AND eh.id_employee = $idEmployee;");
                
                
                if($model == NULL){
                    $model = self::model()->findBySql("SELECT eh.id, h.start_time, h.end_time
                                                       FROM employee_hour eh
                                                       INNER JOIN hour h ON h.id = eh.id_hours
                                                       WHERE eh.id_employee = $idEmployee
                                                       ORDER BY eh.id ASC LIMIT 1;");
                    if($model != NULL){
                        $array[0] = $model->start_time;
                        $array[1] = $model->end_time;
                        return $array;
                    }else{
                        return NULL;
                    }
                }else{
                    $array[0] = $model->start_time;
                    $array[1] = $model->end_time;
                    return $array;
                }
            }else{
                return NULL;
            }
        }
        
        public static function getAllHoursByEmployee($idEmployee,$start=NULL,$end=NULL) 
        {
            $arrayHour = Array();
            if($start != NULL){
                $model = self::model()->findAllBySql("SELECT eh.*, h.* 
                                                      FROM employee_hour eh 
                                                      INNER JOIN employee_hours_day ehd ON eh.id = ehd.id_employee_hours 
                                                      INNER JOIN hour h ON h.id = eh.id_hours 
                                                      WHERE eh.id_employee = $idEmployee 
                                                      AND ehd.start_date >= '$start' AND ehd.start_date <= '$end'
                                                      AND eh.end_date IS NULL ORDER BY eh.id ASC;");
            }else{
                $model = self::model()->findAllBySql("SELECT eh.*, h.* 
                                                      FROM employee_hour eh 
                                                      INNER JOIN hour h ON h.id = eh.id_hours 
                                                      WHERE eh.id_employee = $idEmployee 
                                                      AND eh.end_date IS NULL
                                                      ORDER BY eh.id ASC;");
            }
            if($model != NULL){
                $i = 0;
                foreach ($model as $key => $value) {
                    $arrayHour[$i][0] = $value->start_time;
                    $arrayHour[$i][1] = $value->end_time;
                    $arrayHour[$i][3] = $value->start_break;
                    $arrayHour[$i][4] = $value->end_break;
                    $i++;
                }
            }else{
                $arrayHour = 'NotHour';
            }
            return $arrayHour;
        }
        
        
        public static function getHoursBreack()                                        
        {
             return  CHtml::ListData(self::model()->findAllBySql("select id, ( to_char(start_break, 'HH12:MI am')||' - '||to_char(end_break, 'HH12:MI am')) as descanso, (start_break||' - '||end_break) as descanso1  from employee_hour WHERE start_break IS NOT NULL and end_break IS NOT NULL;"),"descanso1","descanso"); 
        }
}
