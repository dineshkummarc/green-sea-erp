<?php

/**
 * This is the model class for table "{{role_assignment}}".
 *
 * The followings are the available columns in table '{{role_assignment}}':
 * @property string $item_name
 * @property string $employ_id
 */
class RoleAssignment extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return RoleAssignment the static model class
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
		return '{{role_assignment}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('item_name, employ_id', 'required'),
			array('item_name', 'length', 'max'=>64),
			array('employ_id', 'length', 'max'=>10),
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
			'item_name' => 'Item Name',
			'employ_id' => 'Employ',
		);
	}
}