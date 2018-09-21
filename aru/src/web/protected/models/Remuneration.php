<?php

/**
 * This is the model class for table "remuneration".
 *
 * The followings are the available columns in table 'remuneration':
 * @property integer $id
 * @property integer $value
 * @property integer $id_payroll
 * @property integer $id_structure_category_description
 *
 * The followings are the available model relations:
 * @property StructureCategoryDescription $idStructureCategoryDescription
 * @property PayrollEmployee $idPayroll
 */
class Remuneration extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
         
        public $categoryValue;
        public $category; 
        public $modificated;
        public $currency;

        public function tableName()
	{
		return 'remuneration';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('value, id_payroll, id_structure_category_description', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, value, id_payroll, id_structure_category_description', 'safe', 'on'=>'search'),
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
			'idPayroll' => array(self::BELONGS_TO, 'PayrollEmployee', 'id_payroll'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'value' => 'Value',
			'id_payroll' => 'Id Payroll',
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
		$criteria->compare('value',$this->value);
		$criteria->compare('id_payroll',$this->id_payroll);
		$criteria->compare('id_structure_category_description',$this->id_structure_category_description);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Remuneration the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getArrayRemunerationCategory($idEmployee,$idPayRoll,$idCategory,$currency) {
            
            $arrayCategory = Array();
            
            if($currency == true){
                $value = "(r.value || ' ' || c.name)";
                $sqlCategory = "SELECT scd.name as category, r.value as value, c.name as currency
                               FROM remuneration r
                               INNER JOIN payroll_employee pre ON pre.id = r.id_payroll
                               INNER JOIN currency c ON c.id = pre.id_currency
                               INNER JOIN structure_category_description scd ON scd.id = r.id_structure_category_description
                               INNER JOIN structure_category sc ON sc.id = scd.id_structure_category
                               WHERE sc.id = $idCategory AND pre.id_payroll = $idPayRoll  AND pre.id_employee = $idEmployee AND (pre.end_date IS NULL OR pre.end_date >= '".date('Y-m-d', time())."') 
                               ORDER BY r.value DESC;";
                $modelCategory = self::model()->findAllBySql($sqlCategory);
                foreach ($modelCategory as $key => $value) {
                    $arrayCategory[trim($value->category)] = number_format($value->value,2).' '.$value->currency;    
                }
            }else{
                $value = 'r.value';
                $sqlCategory = "SELECT scd.name as category, 0 as value, scd.id as id_category
                               FROM structure_category_description scd
                               INNER JOIN structure_category sc ON sc.id = scd.id_structure_category
                               WHERE sc.id = $idCategory;";
                $modelCategory = StructureCategoryDescription::model()->findAllBySql($sqlCategory);
                foreach ($modelCategory as $key => $value) {
                    $arrayCategory[trim($value->category)] = self::getValueByCategory($value->id_category,$idEmployee,$idPayRoll);    
                }
            }

           return $arrayCategory;
           
        }
        
        public function getRemunerationByEmployee() {
           $Employee=Employee::getEmployee(Yii::app()->user->id);
           $idEmployee = $Employee->id;
           $arrayCategoryGeneral = Array();
           $arrayCategory = Array();
           $arrayReturn = Array();
           $countArray = 0;
           
           
           $sqlPayRoll = "SELECT pr.name as payroll, pr.id as id_payroll 
                          FROM payroll_employee pre
                          INNER JOIN payroll pr ON pre.id_payroll = pr.id
                          WHERE pre.id_employee = $idEmployee AND pre.end_date IS NULL;";
           $modelPayRoll = PayrollEmployee::model()->findAllBySql($sqlPayRoll);
           foreach ($modelPayRoll as $key => $value) {
               
               $arrayReturn[$value->payroll] = NULL;
               
               $modelCatgory = StructureCategory::model()->findAll("name != 'Información Colaboradores'");
               foreach ($modelCatgory as $key2 => $value2) {
                   $arrayReturn[trim($value->payroll)][trim($value2->name)] = Remuneration::getArrayRemunerationCategory($idEmployee,$value->id_payroll,$value2->id,true);
               }

           }

           return $arrayReturn;
           
       }
  
       public function getModificatedByCategory($nameCategory) {
           $modelCatgory = StructureCategoryDescription::model()->find("name = '$nameCategory'");
           if($modelCatgory != NULL){
               return $modelCatgory->modificated;
           }else{
               return false;
           }
       }

       
       public function getValueByCategory($idCategory,$idEmployee,$idPayRoll) {
           $sql = "SELECT r.value, c.name
                   FROM remuneration r
                   INNER JOIN payroll_employee pre ON pre.id = r.id_payroll
                   INNER JOIN currency c ON c.id = pre.id_currency
                   INNER JOIN structure_category_description scd ON scd.id = r.id_structure_category_description
                   WHERE scd.id = $idCategory AND pre.id_payroll = $idPayRoll  AND pre.id_employee = $idEmployee AND (end_date IS NULL OR end_date >= '".date('Y-m-d', time())."');";
           $model = Remuneration::model()->findBySql($sql);
           if($model != NULL){
               return number_format($model->value,2);
           }else{
               return false;
           }
       }

}
