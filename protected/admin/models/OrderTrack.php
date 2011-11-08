<?php

/**
 * This is the model class for table "{{order_track}}".
 *
 * The followings are the available columns in table '{{order_track}}':
 * @property string $id
 * @property string $order_id
 * @property string $admin_id
 * @property string $storage_id
 * @property string $storage_admin_id
 * @property string $photographer_id
 * @property string $photographer_id_2
 * @property string $retouch_id
 * @property string $retouch_id_2
 * @property string $deliver_id
 */
class OrderTrack extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return OrderTrack the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * 新建返回id
	 */
	public function getOrderTrackId($order_id)
	{
		$sql = "select id FROM {{order_track}} WHERE order_id = :order_id";
		$command = Yii::app()->db->createCommand($sql);
		$id = $command->queryScalar(array('order_id'=>$order_id));
		if ($id == null || $id == "") {
			$orderTrack = new OrderTrack;
			$orderTrack->order_id = $order_id;
			$orderTrack->admin_id = 0;
			$orderTrack->storage_id = 0;
			$orderTrack->storage_admin_id = 0;
			$orderTrack->photographer_id = 0;
			$orderTrack->photographer_id_2 = 0;
			$orderTrack->retouch_id = 0;
			$orderTrack->retouch_id_2 = 0;
			$orderTrack->deliver_id = 0;
			$orderTrack->save();
			return $orderTrack->id;
		}else{
			return $id;
		}
	}
	/**
	 * 返回仓储
	 */
	public function getStorage()
	{
		$sql = "select in_time,id,out_time FROM {{storage}} WHERE order_id = ".$this->order_id;
		$command = Yii::app()->db->createCommand($sql);
		return $command->queryRow();
	}
	/**
	 * 返回仓储数量
	 */
	public function getStorageGoodsCount($id)
	{
		$sql = "select count('id') FROM {{storage_goods}} WHERE storage_id = :storage_id";
		$command = Yii::app()->db->createCommand($sql);
		return $command->queryScalar(array(':storage_id'=>$id));
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{order_track}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_id, admin_id, storage_id, storage_admin_id, photographer_id, photographer_id_2, retouch_id, retouch_id_2, deliver_id', 'required'),
			array('order_id, admin_id, storage_id, storage_admin_id, photographer_id, photographer_id_2, retouch_id, retouch_id_2, deliver_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, order_id, admin_id, storage_id, storage_admin_id, photographer_id, photographer_id_2, retouch_id, retouch_id_2, deliver_id', 'safe', 'on'=>'search'),
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
			'Order'=>array(self::BELONGS_TO, 'Order', 'order_id'),
			'Admin'=>array(self::BELONGS_TO, 'Admin', 'admin_id'),
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
			'storage_id' => 'Storage',
			'storage_admin_id' => 'Storage Admin',
			'photographer_id' => 'Photographer',
			'photographer_id_2' => 'Photographer Id 2',
			'retouch_id' => 'Retouch',
			'retouch_id_2' => 'Retouch Id 2',
			'deliver_id' => 'Deliver',
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
		$criteria->compare('storage_id',$this->storage_id,true);
		$criteria->compare('storage_admin_id',$this->storage_admin_id,true);
		$criteria->compare('photographer_id',$this->photographer_id,true);
		$criteria->compare('photographer_id_2',$this->photographer_id_2,true);
		$criteria->compare('retouch_id',$this->retouch_id,true);
		$criteria->compare('retouch_id_2',$this->retouch_id_2,true);
		$criteria->compare('deliver_id',$this->deliver_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}