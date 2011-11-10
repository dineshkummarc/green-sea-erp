<?php

/**
 * This is the model class for table "{{area}}".
 *
 * The followings are the available columns in table '{{area}}':
 * @property string $id
 * @property string $name
 * @property string $parent_id
 * @property string $level
 */
class Area extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Area the static model class
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
		return '{{area}}';
	}
	public function getAreaLevelAll($id)
	{
		$list = array();
		if ($id == 0)
		{
			$sql = "select id,name,parent_id FROM {{area}} WHERE parent_id = 0";
			$command = Yii::app()->db->createCommand($sql);
			$list['1'] = $command->queryAll();
			return $list;
		}
		$sql = "select id,name,parent_id FROM {{area}} WHERE id = ".$id;
		$command = Yii::app()->db->createCommand($sql);
		$area_3 = $command->queryRow();

		$sql = "select id,name,parent_id FROM {{area}} WHERE id = ".$area_3['parent_id'];
		$command = Yii::app()->db->createCommand($sql);
		$area_2 = $command->queryRow();

		$sql = "select id,name,parent_id FROM {{area}} WHERE id = ".$area_2['parent_id'];
		$command = Yii::app()->db->createCommand($sql);
		$area_1 = $command->queryRow();

		$sql = "SELECT id,name,parent_id FROM {{area}} WHERE parent_id = ".$area_3['parent_id'];
		$command = Yii::app()->db->createCommand($sql);
		$list['3'] = $command->queryAll();

		$sql = "select id,name,parent_id FROM {{area}} WHERE parent_id = ".$area_2['parent_id'];
		$command = Yii::app()->db->createCommand($sql);
		$list['2'] = $command->queryAll();

		$sql = "select id,name,parent_id FROM {{area}} WHERE parent_id = ".$area_1['parent_id'];
		$command = Yii::app()->db->createCommand($sql);
		$list['1'] = $command->queryAll();

		$list['default']['1'] = $area_1['id'];
		$list['default']['2'] = $area_2['id'];
		$list['default']['3'] = $area_3['id'];
		return $list;
	}
	public function cache()
	{
	    $duration = 3600 * 24 * 30;
	    $dependency = new CDbCacheDependency("SELECT COUNT(*) FROM " . $this->tableName());
	    return parent::cache($duration, $dependency);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, parent_id, level', 'required'),
			array('name', 'length', 'max'=>20),
			array('parent_id, level', 'length', 'max'=>10),
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
		    'Parent'=>array(self::BELONGS_TO, 'Area', 'parent_id'),
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
			'parent_id' => 'Parent',
			'level' => 'Level',
		);
	}

	// 根据地区等级获取地区
	public function findAreaByLevel($level = 1)
	{
	    $area = $this->cache()->findAllByAttributes(array("level"=>$level));
	    $result = array();
	    foreach ($area as $key=>$val)
	    {
	        $result[$val->id]['id'] = $val->id;
	        $result[$val->id]['name'] = $val->name;
	        $result[$val->id]['parent_id'] = $val->parent_id;
	    }
	    return $result;
	}

	// 获取地区全称
    public function getFullArea()
	{
	    if ($this->getIsNewRecord())
	    {
	        return "";
	    }
	    $address = "";
	    $parent = $this->Parent;
        while ($parent !== null)
        {
            $address = $parent->name . "，" . $address;
            $parent = $parent->Parent;
        }
        $address .= $this->name . "，";
        return $address;
	}
}