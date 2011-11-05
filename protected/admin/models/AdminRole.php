<?php

/**
 * This is the model class for table "{{admin_role}}".
 *
 * The followings are the available columns in table '{{admin_role}}':
 * @property string $id
 * @property string $name
 * @property string $update_time
 * @property integer $status
 */
class AdminRole extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return AdminRole the static model class
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
		return '{{admin_role}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, name, update_time, status', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('id, update_time', 'length', 'max'=>10),
			array('name', 'length', 'max'=>20),
		);
	}
	
	/**
	 * 获取当前角色组管理员总数
	 */
	public function getAdminCount()
	{
	    $count = Admin::model()->cache()->count(array("condition"=>"role_id = ".$this->id));
        return $count;
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
			'name' => 'Name',
			'update_time' => 'Update Time',
			'status' => 'Status',
		);
	}
}