<?php

/**
 * This is the model class for table "{{employ}}".
 *
 * The followings are the available columns in table '{{employ}}':
 * @property string $id
 * @property string $job_number
 * @property string $name
 * @property string $nike_name
 * @property string $email
 * @property string $password
 * @property integer $job_status
 * @property string $last_ip
 * @property string $login_time
 * @property string $login_count
 */
class Employ extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Employ the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{employ}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('job_number, name, nike_name, email, password, job_status, last_ip, login_time, login_count', 'required'),
			array('job_status', 'numerical', 'integerOnly'=>true),
			array('job_number, name, nike_name, login_time, login_count', 'length', 'max'=>10),
			array('email', 'length', 'max'=>30),
			array('password', 'length', 'max'=>32),
			array('last_ip', 'length', 'max'=>15),
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
			'job_number' => 'Job Number',
			'name' => 'Name',
			'nike_name' => 'Nike Name',
			'email' => 'Email',
			'password' => 'Password',
			'job_status' => 'Job Status',
			'last_ip' => 'Last Ip',
			'login_time' => 'Login Time',
			'login_count' => 'Login Count',
		);
	}
}