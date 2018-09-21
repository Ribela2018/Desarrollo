<?php

/**
 * This is the model class for table "employee_activity_obstacle".
 *
 * The followings are the available columns in table 'employee_activity_obstacle':
 * @property integer $id
 * @property integer $id_employee_activity
 * @property integer $id_obstacle
 * @property string $obstacle_date
 * @property integer $resolved
 * @property string $resolved_date
 * @property integer $id_division
 *
 * The followings are the available model relations:
 * @property EmployeeActivity $idEmployeeActivity
 * @property Obstacle $idObstacle
 * @property Division $idDivision
 */
class EmployeeActivityObstacle extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'employee_activity_obstacle';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_employee_activity, id_obstacle', 'required'),
			array('id_employee_activity, id_obstacle, resolved, id_division', 'numerical', 'integerOnly'=>true),
			array('obstacle_date, resolved_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_employee_activity, id_obstacle, obstacle_date, resolved, resolved_date, id_division', 'safe', 'on'=>'search'),
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
			'idEmployeeActivity' => array(self::BELONGS_TO, 'EmployeeActivity', 'id_employee_activity'),
			'idObstacle' => array(self::BELONGS_TO, 'Obstacle', 'id_obstacle'),
			'idDivision' => array(self::BELONGS_TO, 'Division', 'id_division'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_employee_activity' => 'Id Employee Activity',
			'id_obstacle' => 'Id Obstacle',
			'obstacle_date' => 'Obstacle Date',
			'resolved' => 'Resolved',
			'resolved_date' => 'Resolved Date',
			'id_division' => 'Id Division',
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
		$criteria->compare('id_employee_activity',$this->id_employee_activity);
		$criteria->compare('id_obstacle',$this->id_obstacle);
		$criteria->compare('obstacle_date',$this->obstacle_date,true);
		$criteria->compare('resolved',$this->resolved);
		$criteria->compare('resolved_date',$this->resolved_date,true);
		$criteria->compare('id_division',$this->id_division);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EmployeeActivityObstacle the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
