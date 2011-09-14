<?php

/**
 * This is the model class for table "{{workgroup}}".
 *
 * The followings are the available columns in table '{{workgroup}}':
 * @property string $id
 * @property string $name
 * @property string $cameraman_id
 * @property string $cameraman_name
 * @property string $stylist_id
 * @property string $stylist_name
 * @property string $designer_id
 * @property string $designer_name
 */
class Workgroup extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Workgroup the static model class
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
		return '{{workgroup}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, cameraman_id, cameraman_name, stylist_id, stylist_name, designer_id, designer_name', 'required'),
			array('name, cameraman_id, cameraman_name, stylist_id, stylist_name, designer_id, designer_name', 'length', 'max'=>10),
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
			'cameraman_id' => 'Cameraman',
			'cameraman_name' => 'Cameraman Name',
			'stylist_id' => 'Stylist',
			'stylist_name' => 'Stylist Name',
			'designer_id' => 'Designer',
			'designer_name' => 'Designer Name',
		);
	}
}