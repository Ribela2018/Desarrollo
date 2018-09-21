<?php

/**
 * This is the model class for table "employee".
 *
 * The followings are the available columns in table 'employee':
 * @property integer $id
 * @property integer $id_supervisor
 * @property integer $id_education
 * @property integer $id_marital_status
 * @property string $first_name
 * @property string $last_name
 * @property string $date_birth
 * @property string $identity_card
 * @property string $email_personal
 * @property string $email_company
 * @property string $skype
 * @property string $cellphone
 * @property string $home_phone
 * @property string $extension_numeric
 * @property integer $id_gender
 * @property integer $id_nationality
 * @property string $second_name
 * @property string $second_last_name
 * @property string $image_rute
 * @property string $office_phone
 * @property string $contratacion
 *
 * The followings are the available model relations:
 * @property EducationEmployee[] $educationEmployees
 * @property EventEmployee[] $eventEmployees
 * @property Users[] $users
 * @property ChildrenEmployee[] $childrenEmployees
 * @property EmergencyEmployee[] $emergencyEmployees
 * @property LanguageEmployee[] $languageEmployees
 * @property Employee $idSupervisor
 * @property Employee[] $employees
 * @property LevelEducation $idEducation
 * @property MaritalStatus $idMaritalStatus
 * @property Gender $idGender
 * @property Nationality $idNationality
 * @property Address[] $addresses
 */
class Employee extends CActiveRecord
{
    public $country;
    public $state;
    public $city;
    public $line1;
    public $line2;
    public $zip;
    public $cod_phone;
    public $cp;
    public $codeDependence;
    public $empleados;
    public $employee;
    public $division;
    public $position;
    public $position_code;
    public $start_date_employee; 
    public $nombre; 
    public $id_type_event; 
    public $date_event; 
    public $hour_event; 
    public $name_event; 
    public $cedula;
    public $fecha;
    public $fechaComi;
    
    public $status;
    public $addressed;
    public $reason;
    public $date_solicitude;
    public $id_solicitude;
    public $nationality;
    public $dateadmission;
    public $observation;
    public $contratacion;
    public $signature_email;
    public $id_employee;
    public $dirigidoPDF;
    public $constancy;
    public $fechaingreso;
    public $users_id;
    public $employee_id;
    public $employee_name;
    public $user_rol_id;
    public $positionSigner;
    public $params;
    







    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'employee';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_supervisor, id_education, id_marital_status, id_gender, id_nationality', 'numerical', 'integerOnly'=>true),
			array('first_name, last_name, date_birth, identity_card, email_personal, email_company, skype, cellphone, home_phone, extension_numeric, second_name, second_last_name,office_phone', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_supervisor, id_education, id_marital_status, first_name, last_name, date_birth, identity_card, email_personal, email_company, skype, cellphone, home_phone, extension_numeric, id_gender, id_nationality, second_name, second_last_name, image_rute', 'safe', 'on'=>'search'),
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
			'educationEmployees' => array(self::HAS_MANY, 'EducationEmployee', 'id_employee'),
			'eventEmployees' => array(self::HAS_MANY, 'EventEmployee', 'id_employee'),
			'users' => array(self::HAS_MANY, 'Users', 'id_employee'),
			'childrenEmployees' => array(self::HAS_MANY, 'ChildrenEmployee', 'id_employee'),
			'emergencyEmployees' => array(self::HAS_MANY, 'EmergencyEmployee', 'id_employee'),
			'languageEmployees' => array(self::HAS_MANY, 'LanguageEmployee', 'id_employee'),
			'idSupervisor' => array(self::BELONGS_TO, 'Employee', 'id_supervisor'),
			'employees' => array(self::HAS_MANY, 'Employee', 'id_supervisor'),
			'idEducation' => array(self::BELONGS_TO, 'LevelEducation', 'id_education'),
			'idMaritalStatus' => array(self::BELONGS_TO, 'MaritalStatus', 'id_marital_status'),
			'idGender' => array(self::BELONGS_TO, 'Gender', 'id_gender'),
			'idNationality' => array(self::BELONGS_TO, 'Nationality', 'id_nationality'),
			'addresses' => array(self::HAS_MANY, 'Address', 'id_employee'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_supervisor' => 'Id Supervisor',
			'id_education' => 'Id Education',
			'id_marital_status' => 'Id Marital Status',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'date_birth' => 'Date Birth',
			'identity_card' => 'Identity Card',
			'email_personal' => 'Email Personal',
			'email_company' => 'Email Company',
			'skype' => 'Skype',
			'cellphone' => 'Cellphone',
			'home_phone' => 'Home Phone',
			'extension_numeric' => 'Extension Numeric',
			'id_gender' => 'Id Gender',
			'id_nationality' => 'Id Nationality',
			'second_name' => 'Secon Name',
			'second_last_name' => 'Secon Last Name',
			'image_rute' => 'Imagen',
                        'office_phone' => 'Office Phone',
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
		$criteria->compare('id_supervisor',$this->id_supervisor);
		$criteria->compare('id_education',$this->id_education);
		$criteria->compare('id_marital_status',$this->id_marital_status);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('date_birth',$this->date_birth,true);
		$criteria->compare('identity_card',$this->identity_card,true);
		$criteria->compare('email_personal',$this->email_personal,true);
		$criteria->compare('email_company',$this->email_company,true);
		$criteria->compare('skype',$this->skype,true);
		$criteria->compare('cellphone',$this->cellphone,true);
		$criteria->compare('home_phone',$this->home_phone,true);
		$criteria->compare('extension_numeric',$this->extension_numeric,true);
		$criteria->compare('id_gender',$this->id_gender);
		$criteria->compare('id_nationality',$this->id_nationality);
		$criteria->compare('second_name',$this->second_name,true);
		$criteria->compare('second_last_name',$this->second_last_name,true);
		$criteria->compare('image_rute',$this->image_rute,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Employee the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public static function getEmployee ($idUser)
        {
              $idEmployee = Users::getIdEmployee($idUser);
              if ($idEmployee!=NULL) return self::model()->findByPk($idEmployee); else return NULL;
        }
        
        public static function getIdEmployeeByUser ($idUser)
        {
              $idEmployee = Users::getIdEmployee($idUser);
              if ($idEmployee!=NULL) return self::model()->findByPk($idEmployee)->id; else return NULL;
        }
        
        public static function getImage($idUser){
            $idEmployee = Users::getIdEmployee($idUser);
            if ($idEmployee!=NULL) return self::model()->findByPk($idEmployee)->image_rute; else return NULL;
        }
        /**
         * 
         * 
         */
        
         public static function getMaritalStatus() {
            return  CHtml::ListData(MaritalStatus::model()->findAll(),"id","name");
        }
       
        public static function getCurrency() {
            return  CHtml::ListData(Currency::model()->findAll(),"id","name"); 
        }
        
        public static function getLanguaje() {
            return  CHtml::ListData(Language::model()->findAll(),"id","name"); 
        }
        
        public static function getNationality() {
            return  CHtml::ListData(Nationality::model()->findAll(),"id","name"); 
        }
        
        
        public static function getStatusEmployees($type,$limit=null,$offset=null)
        {    
           
            $conditions=null;
            $consulta="select e.*
                        from
                        employee e, users u, event_employee ev, type_event t,
                        (select id_employee, MAX(date) as date
                        from event_employee 
                        group by id_employee ) x,

                        (select id_employee, date, MAX(hour_event) as hour
                        from event_employee
                        group by id_employee, date
                        order by id_employee) y

                        where x.id_employee=y.id_employee and x.date = y.date and
                        x.id_employee = e.id and u.id_employee = e.id and u.id_status NOT IN(2,3) and 
                        ev.id_employee=e.id and ev.date=x.date and ev.hour_event=y.hour and ev.id_type_event = t.id  ";
            switch ($type) {
                case "active":
                    $consulta.=" and t.id IN (1,3) ORDER BY e.first_name";
                    break;
                case "inactive":
                    $consulta.=" and t.id IN (2,4) ORDER BY e.first_name";
                    break;
            }
            if($limit!=null && $offset!=null) $conditions="LIMIT ".$limit." OFFSET ".$offset;
            $employeeall=self::model()->findAllBySql($consulta);
           
            return $employeeall;
        }
        
        public static function getDynamicEmployee($idEmployee){
            $dynamicEmployee=self::model()->findByPk($idEmployee);
            return $dynamicEmployee;
        }
        
        /**
         * funcion que consulta los empleados para listar nombre y apallidos para la tabla de eventos 
         * @return type
         */
        public static function getHourEvent()
        {
//           return self::model()->findAllBySql("select e.* from employee e, users u where u.id_employee = e.id and u.id_status NOT IN(2,3) ORDER BY e.first_name");


           return self::model()->findAllBySql("select e.*,p.name as position from employee e, users u, position_code pc, position p where u.id_employee = e.id and pc.id_employee = e.id and pc.id_position = p.id and u.id_status NOT IN(2,3) ORDER BY e.first_name");


        }
        
        /**
         * funcion para crear los encabezados de las tablas activos e inactivos
         * @param type $id
         * @return string
         */
        public static function createOption( $id)
        {
            $opciones="<tr><th>Colaborador</th><th>Cargo</th><th colspan='2'>Status</th></tr>";
            return $opciones;
        }
        
        
       
        public static function getEmployeeAll()
        {
            $consult ="select e.id, (e.first_name||' '|| e.last_name) as empleados from employee e, users u where e.id=u.id_employee order by first_name ";
            return  CHtml::ListData(self::model()->findAllBySql($consult),"id","empleados"); 
        }
        
          
        
        public static function getEmployeeAllWithOutPresident()
        {
            $consult ="select e.id, (e.first_name||' '|| e.last_name) as empleados from employee e, users u where e.id=u.id_employee AND u.id_employee NOT IN (SELECT id FROM employee WHERE first_name = 'Vacante' OR first_name = 'No' OR first_name = 'Leandro') order by first_name ";
            return  CHtml::ListData(self::model()->findAllBySql($consult),"id","empleados"); 
        }
        
        public static function getVacantId() {
            $modelEmployeeVacant = self::model()->find("first_name = 'Vacante'");
            if($modelEmployeeVacant == NULL){
                $modelNewEmployeeVacant = new Employee;
                $modelNewEmployeeVacant->first_name = 'Vacante';
                $modelNewEmployeeVacant->last_name = 'vacante';
                if($modelNewEmployeeVacant->save()){
                    return $modelNewEmployeeVacant->id;
                }else{
                    return false;
                }
                
            }else{
                return $modelEmployeeVacant->id;
            }
        }
        
        
        public static function getBirthDate($mes)
        {
            
            $consulta= "SELECT e.first_name, e.last_name,e.date_birth, us.id_status FROM employee  e, users  us  WHERE TO_CHAR(date_birth,'MM')='".$mes."' AND us.id_employee=e.id ANd us.id_status!=2 ORDER BY date_birth ASC;";
            $model = self::model()->findAllBySql($consulta);
            
            return $model;
        }
        
        
        public static function getAllEmployeeByDirectory()
        {
             $sql=" SELECT (e.first_name||' '||e.last_name) as nombre, p.name as position, d.name as division, e.cellphone, e.extension_numeric, e.skype, e.email_company, e.id, pc.position_code, e.corporation_phone, e.office_phone FROM employee e  
						INNER JOIN position_code pc ON e.id = pc.id_employee
						INNER JOIN position p ON p.id = pc.id_position
						INNER JOIN division d ON d.id = pc.id_division
                        WHERE pc.end_date IS NULL AND e.first_name!='Vacante'
                        ORDER BY pc.position_code ASC;";
             $allPhone='';
             $extensionNum='';
             $idRol=Yii::app()->user->getState('rol');
             if ($idRol==7 || $idRol==5 || $idRol==6)
                 {
                    $tituloAccion='Opc.';
                    $optTelf="<a href='#' id='optionDirectori' name='optionDirectori' class='tooltips' data-original-title='Asignar Telefóno Corporativo'><i class='fa fa-phone-square '></i></a>";
                    $opctionDelete="<i class='fa fa-times rojo tooltips' data-original-title='Eliminar número telefónico'></i>";
                 }
             else{
                    $tituloAccion=' ';
                    $optTelf=" ";
                    $opctionDelete=" ";
             }
             $modelPositionCodeActives = self::model()->findAllBySql($sql);
             $tabla='';
             $tabla.=" <div class='table-responsive'><table class='table table-striped table-bordered table-hover dataTable no-footer table-export' id='tableDirectoryPhone' name='tableDirectoryPhone'>
                                    <thead class='flip-content'>
                                        <tr>
                                            <th class='fondotablesgris ocultar'>Ids</th>
                                            <th class='fondotablesgris'>Colaborador</th>
                                            <th class='fondotablesgris'>Posición/División</th>
                                            <th class='fondotablesgris'>Telf. Corp (M&oacutevil)</th>
                                            <th class='fondotablesgris'>Telf. Oficina</th>
                                            <th class='fondotablesgris'>Ext.</th>
                                            <th class='fondotablesgris'>Telf. Personal</th>
                                            <th class='fondotablesgris'>Skype</th>
                                            <th class='fondotablesgris'>Correo</th>
                                            <th class='fondotablesgris'>".$tituloAccion."</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                                       
                                        foreach($modelPositionCodeActives as $key => $value)
                                        {
                                            $arrayTelfOffice = Array();
                                            $arrayTelfCorp = Array();
                                            if ($value->corporation_phone!=NULL)
                                                {
                                                   $employeePhoneCorp=$value->corporation_phone;
                                                   $porcionesCorp = explode(",", $employeePhoneCorp);
                                                   $contadorCorp = count(explode(",", $employeePhoneCorp));
                                                   for($i = 0; $contadorCorp > $i; $i++)
                                                    {
                                                       
                                                        $arrayTelfCorp[$i]=$porcionesCorp[$i].'<a href="#" id="positionPhoneCorp"><div class="telfCorp ocultar">corporativo</div><div class="positionPhone ocultar">'.$i.'</div>'.$opctionDelete.'</a>';
                                                    }
                                                     
                                                  $allPhone=implode("\n", $arrayTelfCorp);
                                                }
                                                else{
                                                    $allPhone='';
                                                }
                                               if ($value->extension_numeric!=NULL)
                                                   { 
                                                        $extensionNum = trim( $value->extension_numeric, '_');
                                                   }
                                                   
                                                   else
                                                       {
                                                        $extensionNum='';
                                                       }
                                                
                                                
                                                
                                            if ($value->office_phone!=NULL)
                                                {
                                                   $employeePhoneOffice=$value->office_phone;
                                                   $porcionesOffice = explode(",", $employeePhoneOffice);
                                                   $contadorOffice = count(explode(",", $employeePhoneOffice));
                                                   
                                                   for($i = 0; $contadorOffice > $i; $i++)
                                                    {
                                                        $arrayTelfOffice[$i]=$porcionesOffice[$i].'<a href="#" id="positionPhoneCorp"><div class="telfCorp ocultar">oficina</div><div class="positionPhone ocultar">'.$i.'</div>'.$opctionDelete.'</a>';
                                                    }
                                                     
                                                  $allPhoneOffice=implode("\n", $arrayTelfOffice);
                                                }
                                                else{
                                                    $allPhoneOffice='';
                                                }
                                            
                                            
                                            $tabla.= "<tr>"
                                                    . "<td class='ocultar'>$value->position_code"
//                                                    
                                                    . "</td>"
                                                    . "<td>".ucfirst($value->nombre)
                                                    . "<div class='ocultar'>"
                                                        ."<div id='nameModalModification'>$value->nombre</div>"
                                                        ."<div id='idEmployee'>$value->id</div>"
                                                        ."<div id='position'>$value->position</div>"
                                                    . "</div>"
                                                    . "</td>"
                                                    . "<td>$value->position/$value->division</td>"
                                                    . "<td>$allPhone</td>"
                                                    . "<td style='width: 150px;'>$allPhoneOffice</td>"
                                                    . "<td>$extensionNum</td>"
                                                    . "<td>$value->cellphone</td>"
                                                    . "<td>$value->skype</td>"
                                                    . "<td>$value->email_company</td>"
                                                    . "<td>".$optTelf."</td>"
                                                    . "</tr>";
                                        }
             $tabla.= '</tbody>
                        </table></div>';
               
               return $tabla;  
             
        }
       
   
        
         
        public static function getAllEmployeeByDirectoryPark()
        {
             $sql=" SELECT (e.first_name||' '||e.last_name) as nombre, p.name as position, d.name as division, e.cellphone, e.extension_numeric, e.skype, e.email_company, e.id, pc.position_code, e.corporation_phone, e.office_phone FROM employee e  
						INNER JOIN position_code pc ON e.id = pc.id_employee
						INNER JOIN position p ON p.id = pc.id_position
						INNER JOIN division d ON d.id = pc.id_division
                        WHERE pc.end_date IS NULL AND e.first_name!='Vacante'
                        ORDER BY pc.position_code ASC;";
             $allPhone='';
             $extensionNum='';
             $idRol=Yii::app()->user->getState('rol');
             if ($idRol==7 || $idRol==5 || $idRol==6)
                 {
                    $tituloAccion='Opc.';
                    $optTelf="<a href='#' id='optionDirectori' name='optionDirectori' class='tooltips' data-original-title='Asignar Telefóno Corporativo'><i class='fa fa-phone-square '></i></a>";
                    $opctionDelete="<i class='fa fa-times rojo tooltips' data-original-title='Eliminar número telefónico'></i>";
                 }
             else{
                    $tituloAccion=' ';
                    $optTelf=" ";
                    $opctionDelete=" ";
             }
             $modelPositionCodeActives = self::model()->findAllBySql($sql);
             $tabla='';
             $tabla.=" <table class='table table-striped table-bordered table-hover dataTable no-footer' id='tableDirectoryPhone' name='tableDirectoryPhone'>
                                    <thead class='flip-content'>
                                        <tr>
                                            <th class='fondotablesgris ocultar'>Ids</th>
                                            <th class='fondotablesgris'>Colaborador</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                                       
                                        foreach($modelPositionCodeActives as $key => $value)
                                        {
                                            $arrayTelfOffice = Array();
                                            $arrayTelfCorp = Array();
                                            if ($value->corporation_phone!=NULL)
                                                {
                                                   $employeePhoneCorp=$value->corporation_phone;
                                                   $porcionesCorp = explode(",", $employeePhoneCorp);
                                                   $contadorCorp = count(explode(",", $employeePhoneCorp));
                                                   for($i = 0; $contadorCorp > $i; $i++)
                                                    {
                                                       
                                                        $arrayTelfCorp[$i]=$porcionesCorp[$i].'<a href="#" id="positionPhoneCorp"><div class="telfCorp ocultar">corporativo</div><div class="positionPhone ocultar">'.$i.'</div>'.$opctionDelete.'</a>';
                                                    }
                                                     
                                                  $allPhone=implode("\n", $arrayTelfCorp);
                                                }
                                                else{
                                                    $allPhone='';
                                                }
                                               if ($value->extension_numeric!=NULL)
                                                   { 
                                                        $extensionNum = trim( $value->extension_numeric, '_');
                                                   }
                                                   
                                                   else
                                                       {
                                                        $extensionNum='';
                                                       }
                                                
                                                
                                                
                                            if ($value->office_phone!=NULL)
                                                {
                                                   $employeePhoneOffice=$value->office_phone;
                                                   $porcionesOffice = explode(",", $employeePhoneOffice);
                                                   $contadorOffice = count(explode(",", $employeePhoneOffice));
                                                   
                                                   for($i = 0; $contadorOffice > $i; $i++)
                                                    {
                                                        $arrayTelfOffice[$i]=$porcionesOffice[$i].'<a href="#" id="positionPhoneCorp"><div class="telfCorp ocultar">oficina</div><div class="positionPhone ocultar">'.$i.'</div>'.$opctionDelete.'</a>';
                                                    }
                                                     
                                                  $allPhoneOffice=implode("\n", $arrayTelfOffice);
                                                }
                                                else{
                                                    $allPhoneOffice='';
                                                }
                                            
                                            
                                            $tabla.= "<tr>"
                                                    . "<td class='ocultar'>$value->position_code"
//                                                    
                                                    . "</td>"
                                                    . "<td>$value->nombre"
                                                    . "<div class='ocultar'>"
                                                        ."<div id='nameModalModification'>$value->nombre</div>"
                                                        ."<div id='idEmployee'>$value->id</div>"
                                                        ."<div id='position'>$value->position</div>"
                                                    . "</div>"
                                                    . "</td>"
                                                    . "</tr>";
                                        }
             $tabla.= '</tbody>
                        </table>';
               
               return $tabla;  
             
        }
    public static function getEmployees(){
        $sql = "SELECT e.id, e.first_name || ' ' || e.last_name AS first_name FROM employee e INNER JOIN users u ON u.id_employee=e.id WHERE u.id_status NOT IN (2) ORDER BY first_name";
        $employees = self::model()->findAllBySql($sql);
        
        if($employees==null){
            return false;
        }else{
            return $employees;
        }
    }
    
    public static function getEmployeesArray(){
        $sql = "SELECT e.id, e.first_name || ' ' || e.last_name AS first_name FROM employee e INNER JOIN users u ON u.id_employee=e.id WHERE u.id_status NOT IN (2) ORDER BY first_name";
    
        $employees = self::model()->findAllBySql($sql);
        if($employees!=null){
            $array = [];
            foreach ($employees as $value) {
                $employee['id'] = $value->id;
                $employee['text'] = $value->first_name;
                $array[] = $employee;
            }
            
            return $array;
        }else{
            return false;
        }
    }
    public static function getSignersList(){
        $signersQuery = "SELECT u.id AS users_id, e.id AS employee_id, concat(e.first_name, ' ', e.last_name)AS employee_name ,  u.id_rol, p.spanish_name AS position
                         FROM employee e
                            INNER JOIN users u On u.id_employee = e.id
                            INNER JOIN position_code pc ON pc.id_employee = e.id AND pc.end_date IS NULL
                            INNER JOIN position p ON pc.id_position = p.id 
                         WHERE u.id_rol IN( 4 ,5 , 6)
                            AND u.id_status = 1
                            AND u.id NOT IN(5, 69,9, 122, 72, 202, 16, 15 ,62)";
        return Employee::model()->findAllBySql($signersQuery);
    }
    
       
}
