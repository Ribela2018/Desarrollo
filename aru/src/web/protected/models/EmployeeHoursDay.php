<?php

/**
 * This is the model class for table "employee_hours_day".
 *
 * The followings are the available columns in table 'employee_hours_day':
 * @property integer $id
 * @property integer $id_employee_hours
 * @property string $start_date
 *
 * The followings are the available model relations:
 * @property EmployeeHour $idEmployeeHours
 */
class EmployeeHoursDay extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
        public $start_time;
        public $end_time;

        public function tableName()
	{
		return 'employee_hours_day';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_employee_hours', 'numerical', 'integerOnly'=>true),
			array('start_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_employee_hours, start_date', 'safe', 'on'=>'search'),
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
			'idEmployeeHours' => array(self::BELONGS_TO, 'EmployeeHour', 'id_employee_hours'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_employee_hours' => 'Id Employee Hours',
			'start_date' => 'Start Date',
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
		$criteria->compare('id_employee_hours',$this->id_employee_hours);
		$criteria->compare('start_date',$this->start_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EmployeeHoursDay the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public static function getEmployeeHourDate($mes,$year,$idEmployee)
        {
            $consulta= "SELECT h.start_time, h.end_time, ehd.*  
                        FROM employee_hours_day ehd
                        INNER JOIN employee_hour eh ON ehd.id_employee_hours = eh.id 
                        INNER JOIN hour h ON eh.id_hours = h.id 
                        WHERE TO_CHAR(ehd.start_date,'MM')='".$mes."' AND TO_CHAR(ehd.start_date,'YYYY')='".$year."' AND id_employee_hours IN(SELECT id FROM employee_hour WHERE id_employee = $idEmployee)
                        ORDER BY ehd.start_date ASC;";
            $model = self::model()->findAllBySql($consulta);
            
            return $model;
        }
        
        public static function getHourByDay($idEmployee,$day) {
            
            if($day != NULL){
                $model = self::model()->findBySql("SELECT h.* 
                                                   FROM employee_hours_day ehd 
                                                   INNER JOIN employee_hour eh ON ehd.id_employee_hours = eh.id 
                                                   INNER JOIN hour h ON eh.id_hours = h.id
                                                   WHERE eh.id_employee = $idEmployee AND ehd.start_date = '$day';");
            }else{
                $model = NULL;
            }
            if($model != NULL){
                $hour = Array($model->start_time,$model->end_time);
            }else{
                $allHours = EmployeeHour::getStaticHoursByEmployee($idEmployee);
                if($allHours != 'NotHour'){
                    $hour = Array($allHours[0],$allHours[1]);
                }else{
                    $hour = $allHours;
                }
                
            }
            return $hour;
        }
}
