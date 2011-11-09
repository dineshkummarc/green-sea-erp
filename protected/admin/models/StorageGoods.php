<?php

/**
 * This is the model class for table "{{storage_goods}}".
 *
 * The followings are the available columns in table '{{storage_goods}}':
 * @property string $id
 * @property string $storage_id
 * @property string $sn
 * @property string $name
 * @property string $shoot_type
 * @property integer $is_shoot
 */
class StorageGoods extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return StorageGoods the static model class
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
		return '{{storage_goods}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('storage_id, sn, name, shoot_type, is_shoot', 'required'),
			array('is_shoot', 'numerical', 'integerOnly'=>true),
			array('storage_id, shoot_type', 'length', 'max'=>10),
			array('sn', 'length', 'max'=>20),
			array('name', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, storage_id, sn, name, shoot_type, is_shoot', 'safe', 'on'=>'search'),
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
		    'ShootType'=>array(self::BELONGS_TO, 'ShootType', 'shoot_type'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'storage_id' => 'Storage',
			'sn' => 'Sn',
			'name' => 'Name',
			'shoot_type' => 'Shoot Type',
			'is_shoot' => 'Is Shoot',
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
		$criteria->compare('storage_id',$this->storage_id,true);
		$criteria->compare('sn',$this->sn,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('shoot_type',$this->shoot_type,true);
		$criteria->compare('is_shoot',$this->is_shoot);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}