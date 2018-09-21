<?php

/**
 * This is the model class for table "formulate".
 *
 * The followings are the available columns in table 'formulate':
 * @property integer $id
 * @property string $description
 * @property string $start_date
 * @property integer $id_structure_category_description
 *
 * The followings are the available model relations:
 * @property StructureCategoryDescription $idStructureCategoryDescription
 */
class Formulate extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'formulate';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_structure_category_description', 'numerical', 'integerOnly'=>true),
			array('description', 'length', 'max'=>200),
			array('start_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, description, start_date, id_structure_category_description', 'safe', 'on'=>'search'),
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
			'idStructureCategoryDescription' => array(self::BELONGS_TO, 'StructureCategoryDescription', 'id_structure_category_description'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'description' => 'Description',
			'start_date' => 'Start Date',
			'id_structure_category_description' => 'Id Structure Category Description',
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
		$criteria->compare('description',$this->description,true);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('id_structure_category_description',$this->id_structure_category_description);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Formulate the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
