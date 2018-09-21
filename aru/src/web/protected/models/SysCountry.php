<?php

/**
 * This is the model class for table "sys_country".
 *
 * The followings are the available columns in table 'sys_country':
 * @property integer $id
 * @property string $name
 * @property string $capital
 * @property string $abr
 * @property string $abr_short
 * @property integer $currency_id
 * @property integer $zone_id
 * @property string $flag
 * @property string $call_prefix
 * @property string $coordinates
 *
 * The followings are the available model relations:
 * @property SysZone $zone
 */
class SysCountry extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sys_country';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'required'),
			array('id, currency_id, zone_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('capital, flag', 'length', 'max'=>64),
			array('abr', 'length', 'max'=>3),
			array('abr_short', 'length', 'max'=>256),
			array('call_prefix', 'length', 'max'=>4),
			array('coordinates', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, capital, abr, abr_short, currency_id, zone_id, flag, call_prefix, coordinates', 'safe', 'on'=>'search'),
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
			'zone' => array(self::BELONGS_TO, 'SysZone', 'zone_id'),
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
			'capital' => 'Capital',
			'abr' => 'Abr',
			'abr_short' => 'Abr Short',
			'currency_id' => 'Currency',
			'zone_id' => 'Zone',
			'flag' => 'Flag',
			'call_prefix' => 'Call Prefix',
			'coordinates' => 'Coordinates',
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
		$criteria->compare('capital',$this->capital,true);
		$criteria->compare('abr',$this->abr,true);
		$criteria->compare('abr_short',$this->abr_short,true);
		$criteria->compare('currency_id',$this->currency_id);
		$criteria->compare('zone_id',$this->zone_id);
		$criteria->compare('flag',$this->flag,true);
		$criteria->compare('call_prefix',$this->call_prefix,true);
		$criteria->compare('coordinates',$this->coordinates,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->ribela;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SysCountry the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
