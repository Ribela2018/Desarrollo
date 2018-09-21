<?php

/**
 * This is the model class for table "address_employee".
 *
 * The followings are the available columns in table 'address_employee':
 * @property integer $id
 * @property integer $id_address
 * @property integer $id_employee
 * @property string $start_date
 * @property string $end_date
 *
 * The followings are the available model relations:
 * @property Employee $idEmployee
 * @property Address $idAddress
 */
class AddressEmployee extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'address_employee';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_address, id_employee, start_date', 'required'),
			array('id_address, id_employee', 'numerical', 'integerOnly'=>true),
			array('end_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_address, id_employee, start_date, end_date', 'safe', 'on'=>'search'),
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
			'idAddress' => array(self::BELONGS_TO, 'Address', 'id_address'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_address' => 'Id Address',
			'id_employee' => 'Id Employee',
			'start_date' => 'Start Date',
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
		$criteria->compare('id_address',$this->id_address);
		$criteria->compare('id_employee',$this->id_employee);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('end_date',$this->end_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AddressEmployee the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public static function checkAddressByEmployee($idEmployee,$idAddress)
        {          
            $AddressEmployee = AddressEmployee::model()->find('end_date IS NULL AND id_employee =:idEmploye AND id_address =:idAddress',array(':idEmploye'=>$idEmployee,':idAddress'=>$idAddress));
            if ($AddressEmployee!=NULL)
            {     
                return $AddressEmployee;
            }          
            else {   
                
                return NULL;
            }                  
        }  
        
         public static function loadAddressByEmployee($idEmployee)
        {          
            $AddressEmployee = AddressEmployee::model()->find('end_date IS NULL AND id_employee =:idEmploye ',array(':idEmploye'=>$idEmployee));
            if ($AddressEmployee!=NULL)
            {     
                return $AddressEmployee;
            }          
            else {   
                
                return NULL;
            }                  
        }
}
