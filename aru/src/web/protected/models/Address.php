<?php

/**
 * This is the model class for table "address".
 *
 * The followings are the available columns in table 'address':
 * @property integer $id
 * @property integer $id_city
 * @property string $address_line_1
 * @property string $address_line_2
 * @property string $zip
 *
 * The followings are the available model relations:
 * @property City $idCity
 * @property AddressEmployee[] $addressEmployees
 */
class Address extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'address';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_city, address_line_1', 'required'),
			array('id_city', 'numerical', 'integerOnly'=>true),
			array('address_line_1, address_line_2', 'length', 'max'=>250),
			array('zip', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_city, address_line_1, address_line_2, zip', 'safe', 'on'=>'search'),
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
			'idCity' => array(self::BELONGS_TO, 'City', 'id_city'),
			'addressEmployees' => array(self::HAS_MANY, 'AddressEmployee', 'id_address'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_city' => 'Id City',
			'address_line_1' => 'Address Line 1',
			'address_line_2' => 'Address Line 2',
			'zip' => 'Zip',
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
		$criteria->compare('id_city',$this->id_city);
		$criteria->compare('address_line_1',$this->address_line_1,true);
		$criteria->compare('address_line_2',$this->address_line_2,true);
		$criteria->compare('zip',$this->zip,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Address the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public static function getAddressByEmployee($id)
        {          
            $AddressEmployee = AddressEmployee::model()->find('end_date IS NULL AND id_employee =:id',array(':id'=>$id));
            if ($AddressEmployee!=NULL) return self::model()->findByPk($AddressEmployee->id_address); else return new self;                 
        }
        
        public static function checkAddress($Address)
        {
            $Address = self::model()->find('address_line_1 =:line1 AND address_line_2 =:line2 AND zip =:zip AND id_city =:id_city',array(':line1'=>$Address['address_line_1'],':line2'=>$Address['address_line_2'],':zip'=>$Address['zip'],':id_city'=>$Address['id_city']));            
            if ($Address!=NULL) return $Address->id; else return NULL;  
        } 
        
        public static function validAddressForm($Address)
        {
            if ($Address['address_line_1']!=NULL && $Address['zip']!=NULL && $Address['id_city']!=NULL) return TRUE; else return NULL;  
        }
        
        
        public static function employee($id_address)
        {
            $Address=  self::model()->find('id=:id_address', array(':id_address'=>$id_address));
            return $Address;
        }
        
        
        public static function newAddress($address_1, $address_2, $zip, $id_city ){
            $NewAddress = new Address;
            $NewAddress->address_line_1 = $_POST['Address']['address_line_1'];
            $NewAddress->address_line_2 = $_POST['Address']['address_line_2'];
            $NewAddress->zip = $_POST['Address']['zip'];
            $NewAddress->id_city = $_POST['Address']['id_city'];
            if($NewAddress->save()){
            $idAddress = $NewAddress->id;
            return $idAddress;
            }
        }
}
