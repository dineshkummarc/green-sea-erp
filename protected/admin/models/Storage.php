<?php

/**
 * This is the model class for table "{{storage}}".
 *
 * The followings are the available columns in table '{{storage}}':
 * @property string $id
 * @property string $order_id
 * @property string $admin_id
 * @property string $in_time
 * @property string $out_time
 */
class Storage extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Storage the static model class
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
		return '{{storage}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_id, admin_id, in_time, out_time', 'required'),
			array('order_id, admin_id, in_time, out_time', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, order_id, admin_id, in_time, out_time', 'safe', 'on'=>'search'),
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
			'Order'=>array(self::BELONGS_TO, 'Order', 'order_id')
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
			'admin_id' => 'Admin',
			'in_time' => 'In Time',
			'out_time' => 'Out Time',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('admin_id',$this->admin_id,true);
		$criteria->compare('in_time',$this->in_time,true);
		$criteria->compare('out_time',$this->out_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}