<?php

/**
 * This is the model class for table "{{shoot_type}}".
 *
 * The followings are the available columns in table '{{shoot_type}}':
 * @property string $id
 * @property string $name
 * @property string $width
 */
class ShootType extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ShootType the static model class
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
		return '{{shoot_type}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, width', 'required'),
			array('name', 'length', 'max'=>20),
			array('width', 'length', 'max'=>10),
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
			'width' => 'Width',
		);
	}
}