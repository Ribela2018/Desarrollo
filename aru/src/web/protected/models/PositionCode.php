<?php

/**
 * This is the model class for table "position_code".
 *
 * The followings are the available columns in table 'position_code':
 * @property integer $id
 * @property string $position_code
 * @property integer $id_position
 * @property integer $id_division
 * @property integer $id_employee
 * @property string $start_date
 * @property string $end_date
 *
 * The followings are the available model relations:
 * @property Position $idPosition
 * @property Employee $idEmployee
 * @property Division $idDivision
 */
class PositionCode extends CActiveRecord
{

    public $new_division;
    public $id_dependencia;
    public $id_dependency;
    public $employee;
    public $division;
    public $position;
    public $empleados;
    public $email;
    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'position_code';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	
   
        public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('position_code, id_position, id_division, id_employee, start_date', 'required'),
			array('id_position, id_division, id_employee', 'numerical', 'integerOnly'=>true),
			array('end_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, position_code, id_position, id_division, id_employee, start_date, end_date', 'safe', 'on'=>'search'),
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
			'idPosition' => array(self::BELONGS_TO, 'Position', 'id_position'),
			'idEmployee' => array(self::BELONGS_TO, 'Employee', 'id_employee'),
			'idDivision' => array(self::BELONGS_TO, 'Division', 'id_division'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'position_code' => 'Position Code',
			'id_position' => 'Id Position',
			'id_division' => 'Id Division',
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
		$criteria->compare('position_code',$this->position_code,true);
		$criteria->compare('id_position',$this->id_position);
		$criteria->compare('id_division',$this->id_division);
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
	 * @return PositionCode the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    
    
    /**
     * 
     * funcion para guardar positionCode
     */
    
         public static function getNewPositionCode($idDivision,$positionCode ,$idPosition, $idEmployee, $startDate)
         {
            $modelPositionCode = self::model()->find("id_employee = $idEmployee");
            $PositionCode= new PositionCode;
            $PositionCode->position_code=$positionCode;
            $PositionCode->id_employee=$idEmployee;
            $PositionCode->id_division=$idDivision;
            $PositionCode->id_position=$idPosition;
            $PositionCode->start_date=$startDate;

            $dateFormat = date('Y-m-d',  strtotime($startDate));
            $yesterday = date("Y-m-d", strtotime("-1 day", strtotime($dateFormat)));  
            
            $modelVacantPositionCode = self::model()->findBySql("SELECT pc.* FROM position_code as pc
                                                                INNER JOIN division as d ON d.id = pc.id_division
                                                                INNER JOIN position as p ON p.id = pc.id_position
                                                                INNER JOIN employee as e ON e.id = pc.id_employee
                                                                WHERE id_division = $idDivision 
                                                                AND pc.id_position = $idPosition 
                                                                AND pc.position_code = '$positionCode' 
                                                                AND e.first_name = 'Vacante';");
            $modelEmployee = Employee::model()->find("id = $idEmployee");
            
            
            if($modelPositionCode == NULL){

                if ($PositionCode->save()){

                    if($modelVacantPositionCode != NULL){
                        $modelVacantPositionCode->end_date = $yesterday;
                        $modelVacantPositionCode->save(false);
                    }
                    
                    return TRUE; 
                }else{
                    return FALSE;
                }

            }elseif($modelPositionCode != NULL){
                if($modelPositionCode->end_date == NULL){
                    
                    $employeeName = $modelEmployee->first_name;
                    if($employeeName == 'Vacante'  || $employeeName == 'No' || $employeeName== 'Departamento'){
                        if ($PositionCode->save()){ 
                            
                            return TRUE;
                        } else{ 
                            return FALSE;
                        }
                    }else{
                        return 'EmployeeAlreadyExists';
                    }

                    

                }elseif($modelPositionCode->end_date != NULL && $dateFormat > $modelPositionCode->end_date){

                    if ($PositionCode->save()){ 
                        
                        if($modelVacantPositionCode != NULL){
                            $modelVacantPositionCode->end_date = $yesterday;
                            $modelVacantPositionCode->save(false);
                        }
                        
                        return TRUE;
                    } else{ 
                        return FALSE;
                    }

                }elseif($modelPositionCode->end_date != NULL && $dateFormat <= $modelPositionCode->end_date){

                    return 'EmployeeAlreadyExists';
                }
            }
         }
    
         public static function getEmployeesRRHH() {
             
             $arrayData = Array();
             $arrayData[0] = 'Calle Independencia con Calle Coromoto, Edificio Onnis, Piso 12, Apto 121, Bello Campo - Caracas (Punto de Referencia: Frente al C.C. Bello Campo).';
             $arrayData[1] = '(0212) 2656589 (Mariana Paiva / Gerente), 2634285 (Gabby Gonzalez), 2667823 (Gabriel Pedrón)';
             $arrayData[2] = 'rrhh@etelix.com';
             
             return $arrayData;
         }
         
         public static function getEmployeePositionCode($idDivision)
         {
          
             
             return CHtml::listData(Employee::model()->findAllBySql("SELECT e.id, (e.first_name ||' '|| e.last_name) as empleados 
                                                              FROM employee e 
                                                              INNER JOIN position_code pc ON e.id = pc.id_employee
                                                              WHERE pc.id_division = $idDivision AND end_date IS NULL;"), 'id', 'empleados');
          
         }
         
         
     
}
