<?php

/**
 * This is the model class for table "{{user_contact}}".
 *
 * The followings are the available columns in table '{{user_contact}}':
 * @property string $id
 * @property string $user_id
 * @property string $content
 */
class UserContact extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserContact the static model class
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
		return '{{user_contact}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, content', 'required'),
			array('user_id', 'length', 'max'=>10),
			array('content', 'filter', 'filter'=>'StringFilter::JavascriptFilter'),
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
			'user_id' => 'User',
			'content' => 'Content',
		);
	}
}