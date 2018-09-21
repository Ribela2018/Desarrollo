<?php

/**
 * This is the model class for table "language_employee".
 *
 * The followings are the available columns in table 'language_employee':
 * @property integer $id
 * @property integer $id_employee
 * @property integer $id_language
 * @property integer $reading
 * @property integer $writing
 * @property integer $hearing
 *
 * The followings are the available model relations:
 * @property Employee $idEmployee
 * @property Language $idLanguage
 * @property KnowledgeLevel $reading0
 * @property KnowledgeLevel $writing0
 * @property KnowledgeLevel $hearing0
 */
class LanguageEmployee extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'language_employee';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_employee, id_language, reading, writing, hearing', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_employee, id_language, reading, writing, hearing', 'safe', 'on'=>'search'),
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
			'idLanguage' => array(self::BELONGS_TO, 'Language', 'id_language'),
			'reading0' => array(self::BELONGS_TO, 'KnowledgeLevel', 'reading'),
			'writing0' => array(self::BELONGS_TO, 'KnowledgeLevel', 'writing'),
			'hearing0' => array(self::BELONGS_TO, 'KnowledgeLevel', 'hearing'),
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
			'id_language' => 'Id Language',
			'reading' => 'Reading',
			'writing' => 'Writing',
			'hearing' => 'Hearing',
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
		$criteria->compare('id_language',$this->id_language);
		$criteria->compare('reading',$this->reading);
		$criteria->compare('writing',$this->writing);
		$criteria->compare('hearing',$this->hearing);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LanguageEmployee the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
