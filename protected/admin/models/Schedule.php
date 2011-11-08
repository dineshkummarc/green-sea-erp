<?php

/**
 * This is the model class for table "{{schedule}}".
 *
 * The followings are the available columns in table '{{schedule}}':
 * @property string $id
 * @property string $order_id
 * @property string $model_id
 * @property string $workgroup_id
 * @property string $schedule_time
 * @property string $shoot_time
 * @property string $memo
 */
class Schedule extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Schedule the static model class
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
		return '{{schedule}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_id, shoot_time, stylist_id, model_id', 'required'),
			array('order_id, shoot_time, stylist_id, model_id', 'length', 'max'=>10),
			array('shoot_site', 'length', 'max'=>50),
			array('shoot_info', 'length', 'max'=>255),
			array('memo', 'safe'),
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
			'Order'=>array(self::BELONGS_TO, 'Order', 'order_id', 'select'=>'sn,user_name'),
			'Admin'=>array(self::BELONGS_TO, 'Admin', 'stylist_id', 'select'=>'name,role_id'),
			'Model'=>array(self::BELONGS_TO, 'Model', 'model_id', 'select'=>'nick_name'),
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
			'shoot_time' => 'Shoot Time',
			'shoot_stie' => 'Shoot Site',
			'shoot_info' => 'Shoot Info',
			'stylist_id' => 'Stylist ID',
			'model_id' => 'Model ID',
			'memo' => 'Memo',
		);
	}
}