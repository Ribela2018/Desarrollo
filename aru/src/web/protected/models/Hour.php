<?php

/**
 * This is the model class for table "hour".
 *
 * The followings are the available columns in table 'hour':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $start_time
 * @property string $end_time
 *
 * The followings are the available model relations:
 * @property EmployeeHour[] $employeeHours
 */
class Hour extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
    
    public $horarios; 
    public $static;
    public $start_break;
    public $end_break;

    public function tableName()
	{
		return 'hour';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, description', 'length', 'max'=>200),
			array('start_time, end_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, description, start_time, end_time', 'safe', 'on'=>'search'),
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
			'employeeHours' => array(self::HAS_MANY, 'EmployeeHour', 'id_hours'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'description' => 'Description',
			'start_time' => 'Start Time',
			'end_time' => 'End Time',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Hour the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    
     public static function getEmployeeHours() 
      {
           return  CHtml::ListData(self::model()->findAllBySql("select id, ( to_char(start_time, 'HH12:MI am')||' - '||  to_char(end_time, 'HH12:MI am')) as horarios  from hour;"),"id","horarios"); 
      }
      
     public static function getEmployeeHoursByEmployee($idEmployee) 
      {
           return  CHtml::ListData(self::model()->findAllBySql("select eh.id, ( to_char(start_time, 'HH12:MI am')||' - '||  to_char(end_time, 'HH12:MI am')) as horarios  from hour h inner join employee_hour eh on eh.id_hours = h.id where eh.id_employee = $idEmployee;"),"id","horarios"); 
      } 
      
      public static function getLeterHour() {
          
        $arrayLetter = Array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $modelAllHour = self::model()->findAllBySql('SELECT * FROM hour ORDER BY id ASC;');
        foreach ($modelAllHour as $key => $value) {
            $arrayL[$value->id] = $arrayLetter[$key];
        }
        
        return $arrayL;
      }
      
 

}