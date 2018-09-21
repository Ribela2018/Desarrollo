<?php

/**
 * This is the model class for table "structure_category_description".
 *
 * The followings are the available columns in table 'structure_category_description':
 * @property integer $id
 * @property string $name
 * @property string $date_create
 * @property integer $id_structure_category
 *
 * The followings are the available model relations:
 * @property Formulate[] $formulates
 * @property Remuneration[] $remunerations
 */
class StructureCategoryDescription extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
    
        public $category;
        public $value;
        public $id_category;


        public function tableName()
	{
		return 'structure_category_description';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_structure_category', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>200),
			array('date_create', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, date_create, id_structure_category', 'safe', 'on'=>'search'),
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
			'formulates' => array(self::HAS_MANY, 'Formulate', 'id_structure_category_description'),
			'remunerations' => array(self::HAS_MANY, 'Remuneration', 'id_structure_category_description'),
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
			'date_create' => 'Date Create',
			'id_structure_category' => 'Id Structure Category',
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
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('id_structure_category',$this->id_structure_category);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StructureCategoryDescription the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getIdByName($name,$category=NULL) {
            $nameFormat = ucfirst($name);
            $model = self::model()->find("name = '$nameFormat'");
            if($model != NULL){
                return $model->id;
            }else{
                $modelNew = new StructureCategoryDescription;
                $modelNew->name = $nameFormat;
                $modelNew->date_create = date('Y-m-d',time());
                $modelNew->id_structure_category = $category;
                $modelNew->modificated = TRUE;
                if($modelNew->save()){
                    return $modelNew->id;
                }
            }
            
        }
}
