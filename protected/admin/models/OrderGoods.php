<?php

/**
 * This is the model class for table "{{order_goods}}".
 *
 * The followings are the available columns in table '{{order_goods}}':
 * @property string $id
 * @property string $order_id
 * @property string $sn
 * @property integer $season
 * @property integer $sex
 * @property string $type
 * @property string $type_name
 * @property string $shoot_type
 * @property string $style
 * @property string $count
 * @property string $real_count
 * @property string $shoot_count
 * @property string $price
 * @property integer $status
 * @property string $memo
 */
class OrderGoods extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return OrderGoods the static model class
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
	        $dependency = new CDbCacheDependency("SELECT COUNT(*) FROM ".$this->tableName());
	    }
	    return parent::cache($duration, $dependency);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{order_goods}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_id, sn, season, sex, type, type_name, shoot_type, style, count, real_count, shoot_count, price, status', 'required'),
			array('season, sex, status', 'numerical', 'integerOnly'=>true),
			array('order_id, type, shoot_type, style, count, real_count, shoot_count', 'length', 'max'=>10),
			array('sn, type_name', 'length', 'max'=>20),
			array('price', 'length', 'max'=>6),
			array('memo', 'length', 'max'=>255),
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
		    'Type'=>array(self::BELONGS_TO, 'GoodsType', 'type'),
		    'ShootType'=>array(self::BELONGS_TO, 'ShootType', 'shoot_type'),
		    'Style'=>array(self::BELONGS_TO, 'Style', 'style'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'order_id' => 'Order',
			'sn' => 'Sn',
			'season' => 'Season',
			'sex' => 'Sex',
			'type' => 'Type',
			'type_name' => 'Type Name',
			'shoot_type' => 'Shoot Type',
			'style' => 'Style',
			'count' => 'Count',
			'real_count' => 'Real Count',
			'shoot_count' => 'Shoot Count',
			'price' => 'Price',
			'status' => 'Status',
			'memo' => 'Memo',
		);
	}
}