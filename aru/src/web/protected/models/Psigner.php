<?php

/**
 * This is the model class for table "psigner".
 *
 * The followings are the available columns in table 'psigner':
 * @property integer $id
 * @property integer $id_employee
 * @property string $start_date
 * @property string $end_date
 */
class Psigner extends CActiveRecord
{
      public $signer_name;
      public $signer_position;
      public $signer_id;
//      public $positionSigner;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'psigner';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_employee', 'numerical', 'integerOnly'=>true),
			array('start_date, end_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_employee, start_date, end_date', 'safe', 'on'=>'search'),
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
	 * @return Psigner the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        public static function getSignerPosition(){
            $params = [];
            $positionQuery = "SELECT  e.first_name  || ' ' || e.last_name AS signer_name, spanish_name AS signer_position , e.id AS signer_id
                                FROM psigner signer
                                INNER JOIN employee e ON e.id = signer.id_employee
                                INNER JOIN users u ON u.id_employee = signer.id_employee
                                INNER JOIN position_code pc ON pc.id_employee = signer.id_employee AND pc.end_date IS NULL
                                INNER JOIN position p ON pc.id_position = p.id
                                WHERE signer.end_date IS NULL
                                  AND spanish_name IS NOT NULL";
            $signer = Psigner::model()->findBySql($positionQuery);
    //        print_r($signer);
            $params["signer_id"] = $signer->signer_id;
            $params["signer_name"] = $signer->signer_name;
            $params["signer_position"] = $signer->signer_position;
            return $params;
        }  
}

