<?php

/**
 * This is the model class for table "{{goods_type}}".
 *
 * The followings are the available columns in table '{{goods_type}}':
 * @property string $id
 * @property string $name
 * @property string $price
 */
class GoodsType extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return GoodsType the static model class
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
		return '{{goods_type}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, price', 'required'),
			array('name', 'length', 'max'=>20),
			array('price', 'length', 'max'=>6),
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
			'name' => 'Name',
			'price' => 'Price',
		);
	}
}