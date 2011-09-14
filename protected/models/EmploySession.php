<?php

/**
 * This is the model class for table "{{employ_session}}".
 *
 * The followings are the available columns in table '{{employ_session}}':
 * @property string $id
 * @property string $expire
 * @property string $data
 */
class EmploySession extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return EmploySession the static model class
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
		return '{{employ_session}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, expire, data', 'required'),
			array('id', 'length', 'max'=>32),
			array('expire', 'length', 'max'=>10),
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
			'expire' => 'Expire',
			'data' => 'Data',
		);
	}
}