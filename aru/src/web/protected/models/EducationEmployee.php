<?php

/**
 * This is the model class for table "education_employee".
 *
 * The followings are the available columns in table 'education_employee':
 * @property integer $id
 * @property integer $id_employee
 * @property integer $id_course
 * @property integer $id_profession
 * @property string $date_start
 * @property string $date_end
 * @property string $date_start_cur
 * @property string $date_end_cur
 *
 * The followings are the available model relations:
 * @property Employee $idEmployee
 * @property Courses $idCourse
 */
class EducationEmployee extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'education_employee';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_employee, id_course, id_profession', 'numerical', 'integerOnly'=>true),
			array('date_start, date_end, date_start_cur, date_end_cur', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_employee, id_course, id_profession, date_start, date_end, date_start_cur, date_end_cur', 'safe', 'on'=>'search'),
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
			'idCourse' => array(self::BELONGS_TO, 'Courses', 'id_course'),
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
			'id_course' => 'Id Course',
			'id_profession' => 'Id Profession',
			'date_start' => 'Date Start',
			'date_end' => 'Date End',
			'date_start_cur' => 'Date Start Cur',
			'date_end_cur' => 'Date End Cur',
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
		$criteria->compare('id_course',$this->id_course);
		$criteria->compare('id_profession',$this->id_profession);
		$criteria->compare('date_start',$this->date_start,true);
		$criteria->compare('date_end',$this->date_end,true);
		$criteria->compare('date_start_cur',$this->date_start_cur,true);
		$criteria->compare('date_end_cur',$this->date_end_cur,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EducationEmployee the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
