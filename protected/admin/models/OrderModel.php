<?php

/**
 * This is the model class for table "{{order_model}}".
 *
 * The followings are the available columns in table '{{order_model}}':
 * @property string $order_id
 * @property string $model_id
 */
class OrderModel extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return OrderModel the static model class
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
		return '{{order_model}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_id, model_id', 'required'),
			array('order_id, model_id', 'length', 'max'=>10),
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
			'order_id' => 'Order',
			'model_id' => 'Model',
		);
	}
}