<?php

/**
 * This is the model class for table "{{admin_role_item}}".
 *
 * The followings are the available columns in table '{{admin_role_item}}':
 * @property string $id
 * @property string $name
 * @property string $parent_id
 * @property string $description
 * @property string $update_time
 */
class AdminRoleItem extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return AdminRoleItem the static model class
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
	    if ($duration === null)
	        $duration = 3600 * 12 * 7;
        if ($dependency === null)
	        $dependency = new CDbCacheDependency("SELECT COUNT(*), MAX(update_time) FROM ".$this->tableName());
	    return parent::cache($duration, $dependency);
	}

	// 是否授权
    public function getIsAssign($id)
	{
	    $sql = "SELECT COUNT(*) FROM {{admin_role_child}} WHERE `item_id` = :id AND role_id = :roleId";
	    $command = Yii::app()->db->createCommand($sql);
	    return $command->queryScalar(array(":id"=>$this->id, ":roleId"=>$id));
	}

	// 是否继承
	public function getIsInherit()
	{
        $sql = "SELECT COUNT(*) FROM {{admin_role_child}} WHERE `item_id` = :id";
	    $command = Yii::app()->db->createCommand($sql);
	    $result = $command->queryScalar(array(":id"=>$this->parent_id));
	    if ($result === false ){
	    	return null;
	    }else{
	    	$sql = "SELECT description FROM {{admin_role_item}} WHERE id = :Id";
	    	$command = Yii::app()->db->createCommand($sql);
	    	$result = $command->queryScalar(array(":Id"=>$this->parent_id));
	    	return $result;
	    }
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{admin_role_item}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rule, parent_id, description, update_time', 'required'),
			array('rule', 'length', 'max'=>50),
			array('parent_id, update_time', 'length', 'max'=>10),
			array('description', 'length', 'max'=>100),
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
		    'Role'=>array(self::MANY_MANY, 'AdminRole', '{{admin_role_child}}(item_id, role_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'rule' => 'Rule',
			'parent_id' => 'Parent',
			'description' => 'Description',
			'update_time' => 'Update Time',
		);
	}
}