<?php

/**
 * This is the model class for table "city".
 *
 * The followings are the available columns in table 'city':
 * @property integer $id
 * @property string $name
 * @property string $country
 * @property string $state
 * @property integer $id_state
 *
 * The followings are the available model relations:
 * @property State $idState
 * @property Address[] $addresses
 */
class City extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
  
	public function tableName()
	{
		return 'city';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, country, state', 'required'),
			array('id_state', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>35),
			array('country', 'length', 'max'=>3),
			array('state', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, country, state, id_state', 'safe', 'on'=>'search'),
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
			'idState' => array(self::BELONGS_TO, 'State', 'id_state'),
			'addresses' => array(self::HAS_MANY, 'Address', 'id_city'),
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
			'country' => 'Country',
			'state' => 'State',
			'id_state' => 'Id State',
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
		$criteria->compare('country',$this->country,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('id_state',$this->id_state);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return City the static model class
	 */

	public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public static function getListCityState($state)
    {
        return CHtml::listData(self::model()->findAll('id_state=:state', array(':state' => $state)), 'id', 'name');
    }


     public static function getCity($id_state)

    {

       return CHtml::listData(self::model()->findAll('id_state=:state', array(':state' => $id_state)), 'id', 'name');
    }

}
