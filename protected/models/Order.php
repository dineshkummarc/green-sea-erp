<?php

/**
 * This is the model class for table "{{order}}".
 *
 * The followings are the available columns in table '{{order}}':
 * @property string $id
 * @property string $sn
 * @property string $user_id
 * @property string $user_name
 * @property integer $following
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
	    $sql = "UPDATE {{order}} SET status = :status WHERE id = :id";
	    $command = Yii::app()->db->createCommand($sql);
	    $command->execute(array(':status'=>$status, ':id'=>$id));
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
			array('sn, user_id, user_name, total_price, create_time, update_time, square, artwork, retouch, following, shoot_notice, typesetting, diff_color, status', 'required'),
			array('following, square, artwork, retouch, typesetting, diff_color, status', 'numerical', 'integerOnly'=>true),
			array('sn, user_name', 'length', 'max'=>20),
			array('user_id, create_time, update_time, pay_time, receive_time, shoot_time, studio_shoot, outdoor_shoot', 'length', 'max'=>10),
			array('pay_time, receive_time, shoot_time, studio_shoot, outdoor_shoot', 'default', 'value'=>0),
			array('logistics_sn', 'length', 'max'=>50),
			array('example_img, shoot_notice', 'length', 'max'=>200),
			array('total_price', 'length', 'max'=>6),
			array('receive_address, other_comment, example_comment', 'length', 'max'=>255),
			array('example_img', 'FileValidator', 'types'=>'jpg, png, gif', 'wrongType'=>'只允许上传图片',
				'maxSize'=>300 * 1024, 'tooLarge'=>'文件不能大于300K', 'allowEmpty'=>true),
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
		    'User'=>array(self::BELONGS_TO, 'User', 'user_id'),
		    'Goods'=>array(self::HAS_MANY, 'OrderGoods', 'order_id'),
		    'Models'=>array(self::MANY_MANY, 'Models', '{{order_model}}(order_id, model_id)'),
		);
	}

	/**
	 * get SN
	 */
	public function getSn()
	{
	    $user = Yii::app()->user;
	    $nextOrder = $user->getState("nextOrder");
	    $sn = "P";
	    $sn .= substr(strval($user->id + 1000),1,3); // 3位，当前客户ID，左补零
	    $sn .= strlen($nextOrder) < 2 ? "0".$nextOrder : $nextOrder; // 2位，当前客户最后一个订单ID，左补零
	    return $sn;
	}

	/**
	 * 获取状态文本
	 */
	public function getStatusText()
	{
	    $statusList = require(Yii::app()->basePath . "/config/orderType.php");
	    return $statusList[$this->status];
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
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sn' => 'Sn',
			'user_id' => 'User',
			'user_name' => 'User Name',
			'following' => 'Following',
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
			'receive_address' => 'Receive Address',
			'status' => 'Status',
			'memo' => 'Memo',
		);
	}
}