<?php

/**
 * This is the model class for table "log_aru".
 *
 * The followings are the available columns in table 'log_aru':
 * @property integer $id
 * @property string $create_date
 * @property string $create_hour
 * @property integer $id_user
 * @property string $action_date
 * @property string $action_hour
 * @property integer $id_action_log_aru
 * @property boolean $success
 * @property boolean $movil
 *
 * The followings are the available model relations:
 * @property ActionLogAru $idActionLogAru
 * @property Users $idUser
 */
class LogAru extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'log_aru';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_user, id_action_log_aru', 'numerical', 'integerOnly'=>true),
			array('create_date, create_hour, action_date, action_hour, success, movil', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, create_date, create_hour, id_user, action_date, action_hour, id_action_log_aru, success, movil', 'safe', 'on'=>'search'),
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
			'idActionLogAru' => array(self::BELONGS_TO, 'ActionLogAru', 'id_action_log_aru'),
			'idUser' => array(self::BELONGS_TO, 'Users', 'id_user'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'create_date' => 'Create Date',
			'create_hour' => 'Create Hour',
			'id_user' => 'Id User',
			'action_date' => 'Action Date',
			'action_hour' => 'Action Hour',
			'id_action_log_aru' => 'Id Action Log Aru',
			'success' => 'Success',
			'movil' => 'Movil',
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
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('create_hour',$this->create_hour,true);
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('action_date',$this->action_date,true);
		$criteria->compare('action_hour',$this->action_hour,true);
		$criteria->compare('id_action_log_aru',$this->id_action_log_aru);
		$criteria->compare('success',$this->success);
		$criteria->compare('movil',$this->movil);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LogAru the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    
    /**
     * 
     */
    
    public static function getIdNewLog($idAction, $idUser, $phone, $success)
    {
        $model = new LogAru;
        $model->id_action_log_aru = $idAction;
        $model->id_user = $idUser;
        $model->create_date = date('Y-m-d', time());
        $model->create_hour = date('H:i:s', time());
        $model->success = $success;
        $model->movil = $phone;
        if($model->save())
        {
             return true;
        }
        else
            {
                return false;
            }
    }

}
