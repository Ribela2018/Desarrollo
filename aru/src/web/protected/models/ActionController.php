<?php

/**
 * This is the model class for table "action_controller".
 *
 * The followings are the available columns in table 'action_controller':
 * @property integer $id
 * @property integer $id_controller
 * @property integer $id_action
 *
 * The followings are the available model relations:
 * @property ActionRol[] $actionRols
 * @property Action $idAction
 * @property Controller $idController
 */
class ActionController extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'action_controller';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_controller, id_action', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_controller, id_action', 'safe', 'on'=>'search'),
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
			'actionRols' => array(self::HAS_MANY, 'ActionRol', 'id_action_controller'),
			'idAction' => array(self::BELONGS_TO, 'Action', 'id_action'),
			'idControllers' => array(self::BELONGS_TO, 'Controllers', 'id_controller'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_controller' => 'Id Controller',
			'id_action' => 'Id Action',
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
		$criteria->compare('id_controller',$this->id_controller);
		$criteria->compare('id_action',$this->id_action);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ActionController the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        /**
         * Metodo encargado de buscar los actions de un controlador
         * @param string $class
         * @param int $IdRol
         * @return array
         */
        public function getAControllers($class, $IdRol)
        {
            $array=array();
            $declaredClasses = get_declared_classes();
               
            //if(!in_array($class, $declaredClasses)) Yii::import("application.controllers." . $class, true);
    
            $reflection = new ReflectionClass($class); 
            $methods = $reflection->getMethods();
    
            foreach($methods as $key => $method)
            {
                if(preg_match('/^action+\w{2,}/',$method->name))
                {
                    $array[] = $method->name;
                }
            }
            return $array;
               
            
                
                
                
//                 $ActionByControlloresRol = self::model()->findAllBySql($consulta);
                
                
                
//                $consulta="select * from action_controller, 
//                           (select id_action_controller from action_rol where id_rol=".$IdRol." group by id_action_controller) as x 
//                            where id =x.id_action_controller and id_controller=".$IdControllers." order by id_action asc";
//                $ActionByControlloresRol = self::model()->findAllBySql($consulta);
//               
//              
//                //var_dump($AllAction);
//             return $ActionByControlloresRol;
         }
}
