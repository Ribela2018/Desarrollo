<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property integer $id_employee
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $activeKey
 * @property boolean $superuser
 * @property integer $id_rol
 * @property string $create_at
 * @property string $lastvist_at
 * @property integer $id_status
 *
 * The followings are the available model relations:
 * @property Rol $idRol
 * @property Employee $idEmployee
 * @property StatusEmployee $idStatus
 * @property ConnectingTrace[] $connectingTraces
 */
class Users extends CActiveRecord
{
         public $validar_pass;
         public $pass;
         public $confir_pass;
        
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_employee, id_rol, id_status', 'numerical', 'integerOnly'=>true),
			array('username, password, email, activeKey, superuser, create_at, lastvist_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_employee, username, password, email, activeKey, superuser, id_rol, create_at, lastvist_at, id_status', 'safe', 'on'=>'search'),
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
			'idEmployee' => array(self::BELONGS_TO, 'Employee', 'id_employee'),
			'idStatus' => array(self::BELONGS_TO, 'StatusEmployee', 'id_status'),
			'connectingTraces' => array(self::HAS_MANY, 'ConnectingTrace', 'id_user'),
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
			'username' => 'Username',
			'password' => 'Password',
			'email' => 'Email',
			'activeKey' => 'Active Key',
			'superuser' => 'Superuser',
			'id_rol' => 'Id Rol',
			'create_at' => 'Create At',
			'lastvist_at' => 'Lastvist At',
			'id_status' => 'Id Status',
                        'employee' => 'Nombre del Colaborador',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('activeKey',$this->activeKey,true);
		$criteria->compare('superuser',$this->superuser);
		$criteria->compare('id_rol',$this->id_rol);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('lastvist_at',$this->lastvist_at,true);
		$criteria->compare('id_status',$this->id_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        
        
   /**
         * 
         * funcion para verificar el pass actual
         */
        
        
        public function getPass($pass){
           
                $id=Yii::app()->user->id;

                if ($pass!=NULL){
                     $verificar_pass=self::model()->find('id=:id AND password=:pass',array(':id'=>$id, ':pass'=>$pass));
                     return $verificar_pass;
                }
                else{
                    return false; 
                }
        }
        
        public static function getIdEmployee($idUser)
        {
            $model = self::model()->findByPk($idUser);
            if ($model!=NULL) return $model->id_employee; else return NULL;          
        }
        
        public static function assignEmployee($idUser,$idEmployee,$emailEmployee=NULL)  
        {
            $model = self::model()->findByPk($idUser);
            $model->id_employee = $idEmployee;
            if($emailEmployee != NULL){
                $model->email = $emailEmployee;
            }
            $model->save();
        }
        
        public static function updateStatus($idUser)  
        {
            $model = self::model()->findByPk($idUser);
            $model->id_status = 1;
            $model->save();
        }
        
        public static function getUsers($idUser){
            $model=self::model()->findByPk($idUser);
            return $model; 
        }
        
        public static function getEmployeeNameByIdUser($idUser) {
            
            $model = self::model()->findBySql("SELECT (e.first_name ||' '|| e.last_name) as id_employee FROM users u INNER JOIN employee e ON e.id = u.id_employee WHERE u.id = $idUser;");
            if($model == NULL){
                return 'Sin Empleado';
            }else{
                if($model->id_employee == NULL){
                    return 'Sin Empleado';
                }else{
                    return $model->id_employee;
                }
            }
            
        }
        
        public function getNewPassword()
        { 
            $string = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"; 
            for($pass='', $n=strlen($string)-1; strlen($pass) < 16 ; ) {
                $x = rand(0,$n);
                $pass.= $string[$x];
            }
            return $pass;
        }
        
        public function validateUsernameDinamyc($name, $log=NULL) 
        {
            $count = 0;
            if($log != NULL){
                $count = $log;
            }
            $name = strtolower($name);
            $string = $name;
            $string = str_replace(array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),$string);
            $string = str_replace(array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),$string);
            $string = str_replace(array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),$string);
            $string = str_replace(array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),$string);
            $string = str_replace(array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),$string);
            $string = str_replace(array('ñ', 'Ñ', 'ç', 'Ç'),array('n', 'N', 'c', 'C',),$string);
            $string = str_replace(array("\\", "¨", "º", "-", "~","#", "@", "|", "!", "\"","·", "$", "%", "&", "/","(", ")", "?", "'", "¡","¿", "[", "^", "`", "]","+", "}", "{", "¨", "´",">", "< ", ";", ",", ":","."),'',$string);

            if(strpos(trim($string),' ')){
                $string2 = explode(' ', $string);
                $nameFinal = $string2[0];
                for($i=0;$i<=$count;$i++){
                    $nameFinal .= $string2[1][$i];
                }
            }else{
                $nameFinal = 'Empty';
            }
            $model = self::model()->find("username = '$nameFinal'");
            if($model != NULL){
                $count++;
                return self::validateUsernameDinamyc($name,$count);
            }else{
                return $nameFinal;
            }
        }
}
