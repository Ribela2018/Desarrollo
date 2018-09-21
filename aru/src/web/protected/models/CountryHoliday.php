<?php

/**
 * This is the model class for table "country_holiday".
 *
 * The followings are the available columns in table 'country_holiday':
 * @property integer $id
 * @property integer $id_holiday
 * @property string $start_date
 * @property string $cod_country
 * @property string $end_date
 *
 * The followings are the available model relations:
 * @property Holiday $idHoliday
 * @property Country $codCountry
 */
class CountryHoliday extends CActiveRecord
{
    
      public $name;
      public $name_holiday;
      public $name_country;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'country_holiday';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_holiday', 'numerical', 'integerOnly'=>true),
			array('start_date, cod_country, end_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_holiday, start_date, cod_country, end_date', 'safe', 'on'=>'search'),
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
			'idHoliday' => array(self::BELONGS_TO, 'Holiday', 'id_holiday'),
			'codCountry' => array(self::BELONGS_TO, 'Country', 'cod_country'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_holiday' => 'Id Holiday',
			'start_date' => 'Start Date',
			'cod_country' => 'Cod Country',
			'end_date' => 'End Date',
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
		$criteria->compare('id_holiday',$this->id_holiday);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('cod_country',$this->cod_country,true);
		$criteria->compare('end_date',$this->end_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CountryHoliday the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
