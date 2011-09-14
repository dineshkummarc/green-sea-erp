<?php

/**
 * This is the model class for table "{{model_styles}}".
 *
 * The followings are the available columns in table '{{model_styles}}':
 * @property string $model_id
 * @property string $style_id
 */
class ModelStyles extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ModelStyles the static model class
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
		return '{{model_styles}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('model_id, style_id', 'required'),
			array('model_id, style_id', 'length', 'max'=>10),
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
			'model_id' => 'Model',
			'style_id' => 'Style',
		);
	}
}