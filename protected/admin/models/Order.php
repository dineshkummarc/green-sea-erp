<?php

/**
 * This is the model class for table "{{order}}".
 *
 * The followings are the available columns in table '{{order}}':
 * @property string $id
 * @property string $sn
 * @property string $user_id
 * @property string $user_name
 * @property string $logistics_sn
 * @property string $payment_url
 * @property string $total_price
 * @property string $create_time
 * @property string $update_time
 * @property string $pay_time
 * @property string $receive_time
 * @property string $shoot_time
 * @property string $example_img
 * @property string $example_comment
 * @property string $retouch_demand
 * @property string $retouch_demand_data
 * @property string $shoot_notice
 * @property string $shoot_notice_data
 * @property string $other_comment
 * @property string $shoot_scene
 * @property string $shoot_scene_data
 * @property string $receive_address
 * @property integer $status
 * @property string $memo
 */
class Order extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Order the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 修改状态
	 * @param integer $id
	 * @param integer $status
	 */
	public static function changeStatus($id, $status)
	{
	    $sql = "UPDATE {{order}} SET status = :status";
	    $command = Yii::app()->db->createCommand($sql);
	    $command->bindParam(":status", $status, PDO::PARAM_INT);
	    $command->excute();
	}

	/**
	 * (non-PHPdoc)
	 * @see CActiveRecord::cache()
	 */
    public function cache()
    {
        $duration = 3600 * 24 * 7;
        $dependency = new CDbCacheDependency('SELECT COUNT(*), MAX(update_time) FROM '.$this->tableName());
        return parent::cache($duration, $dependency);
    }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{order}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, user_name, total_price, create_time, update_time, receive_address, status, following', 'required'),
			array('status, following', 'numerical', 'integerOnly'=>true),
			array('following', 'default', 'value'=>0),
			array('sn, user_name', 'length', 'max'=>20),
			array('sn', 'default', 'value'=>$this->getSn()),
			array('user_id, create_time, update_time, pay_time, receive_time, shoot_time, shoot_scene', 'length', 'max'=>10),
			array('logistics_sn', 'length', 'max'=>50),
			array('payment_url, example_img', 'length', 'max'=>200),
			array('logistics_sn, payment_url, example_img', 'default', 'value'=>''),
			array('total_price', 'length', 'max'=>6),
			array('receive_address', 'length', 'max'=>255),
			array('example_comment, retouch_demand, retouch_demand_data, shoot_notice, shoot_notice_data, other_comment, shoot_scene_data, memo', 'safe'),
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
		    'Goods'=>array(self::HAS_MANY, 'OrderGoods', 'order_id'),
		    'Models'=>array(self::MANY_MANY, 'Models', '{{order_model}}(order_id, model_id)'),
		);
	}

	/**
	 * get SN
	 */
	public function getSn()
	{
	    return Yii::app()->params['timestamp'];
	}

	/**
	 * 获取状态文本
	 */
	public function getStatusText()
	{
	    switch($this->status)
	    {
	        case 1:
	            return "未付款";
            case 2:
	            return "已付款、未收货";
            case 3:
	            return "已付款、已收货、待排程";
            case 4:
	            return "已付款、已收货、已排程";
            case 5:
	            return "拍摄中";
            case 6:
	            return "拍摄完成、修图中";
            case 7:
	            return "修图完成、可下载";
            case 8:
	            return "货物待寄出";
            case 9:
	            return "货物已寄出";
            case 10:
                return "确认收货";
            default:
                return "";
	    }
	}

	/**
	 * 获取模特名称
	 */
	public function getModelsName()
	{
	    $result = "";
	    foreach ($this->Models as $model)
	    {
	        $result .= $model->niki_name . " ";
	    }
	    return $result;
	}

	/**
	 * 拍摄注意事项数据序列化形式
	 */
	public function getShootSceneData()
	{
	    return @unserialize($this->shoot_scene_data);
	}

	/**
	 * 拍摄注意事项数据序列化形式
	 */
	public function getShootNoticeData()
	{
	    return @unserialize($this->shoot_notice_data);
	}

	/**
	 * 修图要求数据序列化形式
	 */
	public function getRetouchDemandData()
	{
	    return @unserialize($this->retouch_demand_data);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sn' => 'Sn',
			'user_id' => 'User',
			'user_name' => 'User Name',
			'logistics_sn' => 'Logistics Sn',
			'payment_url' => 'Payment Url',
			'total_price' => 'Total Price',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'pay_time' => 'Pay Time',
			'receive_time' => 'Receive Time',
			'shoot_time' => 'Shoot Time',
			'example_img' => 'Example Img',
			'example_comment' => 'Example Comment',
			'retouch_demand' => 'Retouch Demand',
			'retouch_demand_data' => 'Retouch Demand Data',
			'shoot_notice' => 'Shoot Notice',
			'shoot_notice_data' => 'Shoot Notice Data',
			'other_comment' => 'Other Comment',
			'shoot_scene' => 'Shoot Scene',
			'shoot_scene_data' => 'Shoot Scene Data',
			'receive_address' => 'Receive Address',
			'status' => 'Status',
			'memo' => 'Memo',
		);
	}
}