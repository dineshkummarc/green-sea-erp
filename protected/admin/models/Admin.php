<?php

/**
 * This is the model class for table "{{admin}}".
 *
 * The followings are the available columns in table '{{admin}}':
 * @property string $id
 * @property string $name
 * @property string $password
 * @property string $role_id
 * @property string $city_id
 * @property string $login_time
 * @property string $last_ip
 * @property string $login_count
 * @property integer $is_supper
 * @property string $update_time
 * @property integer $status
 */
class Admin extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Admin the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 缓存
	 * @param integer $duration 缓存时间
	 * @param CDbCacheDependency $dependency 缓存依赖条件
	 */
	public function cache($duration = null, $dependency = null)
	{
	    if (empty($duration))
	    {
	        $duration = 3600 * 24;
	    }
	    if (empty($dependency))
	    {
	        $dependency = new CDbCacheDependency("SELECT COUNT(*), MAX(update_time) FROM ".$this->tableName());
	    }
	    return parent::cache($duration, $dependency);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{admin}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, password, role_id, login_time, last_ip, login_count, is_supper, update_time, status', 'required'),
			array('is_supper, status', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>30),
			array('password', 'length', 'max'=>32),
			array('role_id, city_id, login_time, last_ip, login_count, update_time', 'length', 'max'=>10),
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
		    'Role'=>array(self::BELONGS_TO, 'AdminRole', 'role_id')
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
			'password' => 'Password',
			'role_id' => 'Role',
			'city_id' => 'City',
			'login_time' => 'Login Time',
			'last_ip' => 'Last Ip',
			'login_count' => 'Login Count',
			'is_supper' => 'Is Supper',
			'update_time' => 'Update Time',
			'status' => 'Status',
		);
	}
}