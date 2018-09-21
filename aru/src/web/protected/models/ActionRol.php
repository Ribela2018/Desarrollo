<?php

/**
 * This is the model class for table "action_rol".
 *
 * The followings are the available columns in table 'action_rol':
 * @property integer $id
 * @property integer $id_rol
 * @property integer $id_action_controller
 *
 * The followings are the available model relations:
 * @property Rol $idRol
 * @property ActionController $idActionController
 */
class ActionRol extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'action_rol';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_rol, id_action_controller', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_rol, id_action_controller', 'safe', 'on'=>'search'),
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
			'idRol' => array(self::BELONGS_TO, 'Rol', 'id_rol'),
			'idActionController' => array(self::BELONGS_TO, 'ActionController', 'id_action_controller'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_rol' => 'Id Rol',
			'id_action_controller' => 'Id Action Controller',
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
		$criteria->compare('id_rol',$this->id_rol);
		$criteria->compare('id_action_controller',$this->id_action_controller);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ActionRol the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        
        public static function getActionRol($id)
        {
            $consulta="select id_action_controller from action_rol where id_rol=".$id."";
            $model=  self::model()->findAllBySql($consulta);
            if ($model!=NULL) return $model; else return false;
        }
}
