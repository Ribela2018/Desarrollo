<?php

/**
 * This is the model class for table "payroll_employee".
 *
 * The followings are the available columns in table 'payroll_employee':
 * @property integer $id
 * @property string $start_date
 * @property string $end_date
 * @property integer $id_employee
 * @property integer $id_payroll
 *
 * The followings are the available model relations:
 * @property Employee $idEmployee
 * @property Payroll $idPayroll
 */
class PayrollEmployee extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
    
        public $employee;
        public $division;
        public $position;
        public $position_code;
        public $start_date_employee; 
        public $end_date_employee; 
        public $currency;
        public $payroll;




        public function tableName()
	{
		return 'payroll_employee';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('start_date, id_employee, id_payroll', 'required'),
			array('id_employee, id_payroll, salary, id_currency', 'numerical', 'integerOnly'=>true),
			array('end_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, start_date, end_date, id_employee, id_payroll, salary, id_currency', 'safe', 'on'=>'search'),
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
			'idPayroll' => array(self::BELONGS_TO, 'Payroll', 'id_payroll'),
                        'remunerations' => array(self::HAS_MANY, 'Remuneration', 'id_payroll'),
                        'idCurrency' => array(self::BELONGS_TO, 'Currency', 'id_currency'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'start_date' => 'Start Date',
			'end_date' => 'End Date',
			'id_employee' => 'Id Employee',
			'id_payroll' => 'Id Payroll',
                        'salary' => 'Salary',
                        'id_currency' => 'Id Currency',
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
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('end_date',$this->end_date,true);
		$criteria->compare('id_employee',$this->id_employee);
		$criteria->compare('id_payroll',$this->id_payroll);
                $criteria->compare('salary',$this->salary);
                $criteria->compare('id_currency',$this->id_currency);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PayrollEmployee the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getAllEmployeeByPayRoll($idPayRoll) {

            $table = '';
            $columnAction = '';
            $arraFrom = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
            $arraTo = Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
            $arrayHeader = Array('CP','Colaborador','División','Posición','Fecha de Ingreso');
            $arrayData = Array();
            $counsArrayData = 0;
                
            if($idPayRoll != 0){
                $namePayRoll = Payroll::model()->find("id = $idPayRoll")->name;
                $sql = "SELECT (e.first_name || ' ' || e.last_name) as employee,  e.id as id,
                          d.name as division, p.name as position, pre.start_date as start_date_employee, pc.position_code 
                          FROM payroll_employee pre 
                          INNER JOIN payroll pr ON pr.id = pre.id_payroll
                          INNER JOIN employee e ON e.id = pre.id_employee
                          INNER JOIN position_code pc ON pc.id_employee = e.id
                          INNER JOIN position p ON pc.id_position = p.id
                          INNER JOIN division d ON pc.id_division = d.id
                          WHERE pre.id_payroll = $idPayRoll AND (pre.end_date IS NULL OR pre.end_date >= '".date('Y-m-d', time())."');";
                $modelPayRollEmployee = self::model()->findAllBySql($sql);
                $arrayHeader[4] = 'Fecha en N&oacute;mina'; 
                $arrayHeader[5] = 'Acciones'; 
                $columnAction = '<td></td>';
                
            }else{
                $namePayRoll = 'AllEmployee';
                $sql = "SELECT (e.first_name || ' ' || e.last_name) as employee, e.id,
                          d.name as division, p.name as position, pc.start_date as start_date_employee, pc.position_code 
                          FROM employee e
                          INNER JOIN position_code pc ON pc.id_employee = e.id
                          INNER JOIN position p ON pc.id_position = p.id
                          INNER JOIN division d ON pc.id_division = d.id
                          WHERE e.first_name != 'Vacante' AND e.first_name != 'No' AND pc.end_date IS NULL
                          ORDER BY pc.position_code ASC;";
                $modelPayRollEmployee = Employee::model()->findAllBySql($sql);
                
            }

            $namePayRoll = 'sample_';
            $table .= '<table class="table table-striped table-bordered table-hover" id="'.$namePayRoll.$idPayRoll.'">
                          <thead>
                              <tr>
                                  <th class="table-checkbox sorting_disabled">
                                      <input type="checkbox" class="group-checkable" data-set="#'.$namePayRoll.$idPayRoll.' .checkboxes" id="selectAll"/>
                                  </th>';
                                  foreach ($arrayHeader as $key => $value) {
                                      $table .= "<th>
                                                    $value
                                                </th>";
                                  }
                   $table .= '</tr>
                          </thead>
                          <tbody>';
            
                          foreach ($modelPayRollEmployee as $key => $value) {
                              
//                              $arrayData[$counsArrayData][0] = '<input type="checkbox" value="'.$value->id.'">';
//                              $arrayData[$counsArrayData][1] = $value->position_code;
//                              $arrayData[$counsArrayData][2] = $value->employee;
//                              $arrayData[$counsArrayData][3] = $value->division;
//                              $arrayData[$counsArrayData][4] = $value->position;
//                              $arrayData[$counsArrayData][5] = date('d/m/Y', strtotime($value->start_date_employee));
//                              $arrayData[$counsArrayData][6] = '';
//                              $counsArrayData++;
                              setlocale(LC_ALL,"es_ES");
                              if($idPayRoll == 0){
                                  $dateShow=$value->start_date_employee;
                                  $columnDate = '<td>'.str_replace($arraTo,$arraFrom,date('d F, Y', strtotime($value->start_date_employee))).'</td>';
                              }else{
                                  $dateShow=$value->start_date_employee;
                                  $columnDate = '<td>'.str_replace($arraTo,$arraFrom,date('d F, Y', strtotime($value->start_date_employee))).'</td>';
//                                  $columnDate = '<td>
//                                                    <input class="form-control input-small date-picker" name="PayrollEmployee[start_date]" id="PayrollEmployee_start_date" type="text" value="'.date('d/m/Y', strtotime($value->start_date_employee)).'"/>    
//                                                </td>';
                              }
                              
                              $table .= '<tr class="odd gradeX">
                                            <td>
                                                <input type="checkbox" class="checkboxes" value="'.$value->id.'"/>
                                                    <div class="ocultar">
                                                            <div id="nameModalModification">'.$value->employee.'</div>
                                                            <div id="divisionModalModification">"'.$value->division.'"</div>
                                                            <div id="positionModalModification">'.$value->position.'</div>
                                                            <div id="dateModalModification">'.$dateShow.'</div>
                                                    </div>
                                            </td>
                                            <td>
                                                '.$value->position_code.'
                                            </td>
                                            <td>
                                                '.$value->employee.'
                                            </td>
                                            <td>
                                                '.$value->division.'
                                            </td>
                                            <td>
                                                '.$value->position.'
                                            </td>
                                            '.$columnDate.'

                                            '.$columnAction.'

                                         </tr>';
                          }
                              
               $table .= '</tbody>
                      </table>';
               
               return $table;
               
               
        }
      
        public function getDataPayRollEmployee($idEmployee,$idPayRoll) {
            
            $arrayData = Array();
            $arraFrom = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
            $arraTo = Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
            $sqlEmployee = "SELECT (e.first_name || ' ' || e.last_name) as employee, e.id,
                            d.name as division, p.name as position, pc.start_date as start_date_employee, pc.position_code 
                            FROM position_code pc
                            INNER JOIN employee e  ON pc.id_employee = e.id
                            INNER JOIN position p ON pc.id_position = p.id
                            INNER JOIN division d ON pc.id_division = d.id
                            WHERE e.id = $idEmployee AND pc.end_date IS NULL
                            ORDER BY pc.position_code ASC;";

            $modelEmployee = PositionCode::model()->findBySql($sqlEmployee);

            if($modelEmployee != NULL){
                $arrayData[0] = $modelEmployee->employee;
                $arrayData['id'] = $modelEmployee->id;
                $arrayData[1] = $modelEmployee->position;
                $arrayData[2] = $modelEmployee->division;
                $arrayData[3] = $modelEmployee->start_date;
            }

            $sqlPayRoll = "SELECT pre.start_date as start_date_employee, pre.end_date as end_date_employee, pre.salary as salary, c.id as id_currency, pr.name as payroll, c.name as currency
                              FROM payroll_employee pre 
                              INNER JOIN payroll pr ON pr.id = pre.id_payroll
                              INNER JOIN currency c ON c.id = pre.id_currency
                              INNER JOIN employee e ON e.id = pre.id_employee
                              INNER JOIN position_code pc ON pc.id_employee = e.id
                              INNER JOIN position p ON pc.id_position = p.id
                              INNER JOIN division d ON pc.id_division = d.id
                              WHERE pre.id_employee = $idEmployee AND pre.id_payroll = $idPayRoll AND (pre.end_date IS NULL OR pre.end_date >= '".date('Y-m-d', time())."');";
            $modelPayRollEmployee = PayrollEmployee::model()->findBySql($sqlPayRoll);
            if($modelPayRollEmployee != NULL){
                $arrayData[4] = str_replace($arraTo,$arraFrom,date('d F, Y', strtotime($modelPayRollEmployee->start_date_employee)));
                if($modelPayRollEmployee->end_date_employee == NULL){
                    $arrayData[5] = $modelPayRollEmployee->end_date_employee;
                }else{
                    $arrayData[5] = str_replace($arraTo,$arraFrom,date('d F, Y', strtotime($modelPayRollEmployee->end_date_employee)));
                }
                
                $arrayData[6] = number_format($modelPayRollEmployee->salary,2);
                $arrayData[7] = $modelPayRollEmployee->id_currency;
                $arrayData[8] = $modelPayRollEmployee->payroll;
                $arrayData[9] = $modelPayRollEmployee->currency;
            }
            
            return $arrayData;
            
        }

}
