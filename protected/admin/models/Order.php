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
 * @property string $total_price
 * @property string $create_time
 * @property string $update_time
 * @property string $pay_time
 * @property string $receive_time
 * @property string $shoot_time
 * @property string $example_img
 * @property string $example_comment
 * @property integer $square
 * @property integer $artwork
 * @property integer $retouch
 * @property integer $following
 * @property string $studio_shoot
 * @property string $outdoor_shoot
 * @property string $other_comment
 * @property string $width
 * @property string $shoot_notice
 * @property integer $typesetting
 * @property integer $diff_color
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
	    $command->execute();
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
			array('sn, user_id, user_name, total_price, create_time, update_time, square, artwork, retouch, outdoor_shoot, width, shoot_notice, typesetting, diff_color, status', 'required'),
			array('square, artwork, retouch, following, typesetting, diff_color, status', 'numerical', 'integerOnly'=>true),
			array('sn, user_name', 'length', 'max'=>20),
			array('user_id, create_time, update_time, pay_time, receive_time, shoot_time, studio_shoot, outdoor_shoot', 'length', 'max'=>10),
			array('logistics_sn', 'length', 'max'=>50),
			array('total_price', 'length', 'max'=>6),
			array('example_img, shoot_notice', 'length', 'max'=>200),
			array('example_comment, other_comment, width, receive_address, memo', 'length', 'max'=>255),
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
			'User'=>array(self::BELONGS_TO, 'User', 'user_id', 'select'=>'t.wangwang'),
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
			'total_price' => 'Total Price',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'pay_time' => 'Pay Time',
			'receive_time' => 'Receive Time',
			'shoot_time' => 'Shoot Time',
			'example_img' => 'Example Img',
			'example_comment' => 'Example Comment',
			'square' => 'Square',
			'artwork' => 'Artwork',
			'retouch' => 'Retouch',
			'following' => 'Following',
			'studio_shoot' => 'Studio Shoot',
			'outdoor_shoot' => 'Outdoor Shoot',
			'other_comment' => 'Other Comment',
			'width' => 'Width',
			'shoot_notice' => 'Shoot Notice',
			'typesetting' => 'Typesetting',
			'diff_color' => 'Diff Color',
			'receive_address' => 'Receive Address',
			'status' => 'Status',
			'memo' => 'Memo',
		);
	}
}