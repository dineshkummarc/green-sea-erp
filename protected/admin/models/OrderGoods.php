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
 * @property string $studio_shoot
 * @property string $outdoor_shoot
 * @property string $shoot_notice
 * @property string $width
 * @property integer $square
 * @property integer $artwork
 * @property string $other_comment
 * @property integer $status
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
			array('order_id, sn, season, sex, type, type_name, shoot_type, style, count, status', 'required'),
			array('season, sex, status', 'numerical', 'integerOnly'=>true),
			array('order_id, type, shoot_type, style, count, real_count, shoot_count', 'length', 'max'=>10),
			array('price', 'default', 'value'=>0),
			array('real_count, shoot_count', 'default', 'value'=>0),
			array('sn, type_name', 'length', 'max'=>20),
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
			'studio_shoot' => 'Studio Shoot',
			'outdoor_shoot' => 'Outdoor Shoot',
			'shoot_notice' => 'Shoot Notice',
			'width' => 'Width',
			'square' => 'Square',
			'artwork' => 'Artwork',
			'other_comment' => 'Other Comment',
			'status' => 'Status',
		);
	}

	/**
	 * 获取季节文本
	 */
	public function getSeasonText()
	{
	    switch ($this->season)
	    {
	        case 0:
	            return "不限";
	        case 1:
	            return "春秋";
            case 2:
	            return "夏";
            case 3:
	            return "冬";
            default:
	            return "";
	    }
	}

	/**
	 * 获取性别文本
	 */
	public function getSexText()
	{
	    switch ($this->sex)
	    {
	        case 0:
	            return "不限";
	        case 1:
	            return "男";
            case 2:
	            return "女";
            case 3:
	            return "情侣";
            default:
	            return "";
	    }
	}

	/**
	 * 获取状态信息文本
	 */
	public function getStatusText()
	{
	    switch ($this->status)
	    {
	        case 1:
	            return "在库";
            case 2:
	            return "不在库";
            default:
	            return "";
	    }
	}
}