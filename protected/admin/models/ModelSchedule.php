<?php

/**
 * This is the model class for table "{{model_schedule}}".
 *
 * The followings are the available columns in table '{{model_schedule}}':
 * @property string $date
 * @property string $model_id
 * @property integer $scheduled
 * @property string $max_count
 * @property string $min_count
 * @property string $cur_count
 *
 * The followings are the available model relations:
 * @property Models $model
 */
class ModelSchedule extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ModelSchedule the static model class
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
		return '{{model_schedule}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date, model_id, scheduled, max_count, min_count, cur_count', 'required'),
			array('scheduled', 'numerical', 'integerOnly'=>true),
			array('model_id, max_count, min_count, cur_count', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('date, model_id, scheduled, max_count, min_count, cur_count', 'safe', 'on'=>'search'),
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
			'Model' => array(self::BELONGS_TO, 'Models', 'model_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'date' => 'Date',
			'model_id' => 'Model',
			'scheduled' => 'Scheduled',
			'max_count' => 'Max Count',
			'min_count' => 'Min Count',
			'cur_count' => 'Cur Count',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('date',$this->date,true);
		$criteria->compare('model_id',$this->model_id,true);
		$criteria->compare('scheduled',$this->scheduled);
		$criteria->compare('max_count',$this->max_count,true);
		$criteria->compare('min_count',$this->min_count,true);
		$criteria->compare('cur_count',$this->cur_count,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}