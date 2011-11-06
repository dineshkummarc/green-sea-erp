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
	public $shoot_type_list=array();//格式化类型列表
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
     * 物品总和
     */
    public function getGoodsCount()
    {
        $sql = "SELECT SUM( `count` ) FROM {{order_goods}} WHERE order_id = :id";
        $command = Yii::app()->db->createCommand($sql);
        return $command->queryScalar(array(':id'=>$this->id));
    }
    /**
     * 拍摄 性别
     */
    public function getGoodsSex()
    {
        $sql = "SELECT sex FROM {{order_goods}} WHERE order_id = :id";
        $command = Yii::app()->db->createCommand($sql);
        $sex = $command->queryScalar(array(':id'=>$this->id));
        if ($sex == 0)$sex="不限";
        if ($sex == 1)$sex="男";
        if ($sex == 2)$sex="女";
        if ($sex == 3)$sex="情侣";
        return $sex;
    }
    /**
     * 拍摄 类型
     */
    public function getGoodsType()
    {
    	if (empty($this->typeFormat)) {//如果为空则取出
    		$this->shootTypeFormat();
    	}
        $sql = "SELECT type_name,shoot_type FROM {{order_goods}} WHERE order_id = ".$this->id;
        $command = Yii::app()->db->createCommand($sql);
        $typeList = $command->queryAll();
        $list='';
		foreach($typeList as $key=>$type)
		{
//			if (strripos($list,$type['type_name']) == false) {
				if ($key > 0) $list.=' -- ';
				$list .= $type['type_name'].' / '.$this->shoot_type_list[$type['shoot_type']];
//			}
		}
        return $list;
    }
    /**
     * 格式化拍摄 类型
     */
    public function shootTypeFormat()
    {
    	$sql = "SELECT * FROM {{shoot_type}}";
        $command = Yii::app()->db->createCommand($sql);
        $typeList = $command->queryAll();
        foreach($typeList as $type)
        {
			$this->shoot_type_list[$type['id']]=$type['name'];
        }
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
            array('sn, user_id, user_name, total_price, create_time, update_time, square, artwork, retouch, following, width, shoot_notice, typesetting, diff_color, status', 'required'),
            array('following, square, artwork, retouch, typesetting, diff_color, status', 'numerical', 'integerOnly'=>true),
            array('sn, user_name', 'length', 'max'=>20),
            array('user_id, create_time, update_time, pay_time, receive_time, shoot_time, studio_shoot, outdoor_shoot', 'length', 'max'=>10),
            array('pay_time, receive_time, shoot_time, studio_shoot, outdoor_shoot', 'default', 'value'=>0),
            array('logistics_sn', 'length', 'max'=>50),
            array('example_img, shoot_notice', 'length', 'max'=>200),
            array('total_price', 'length', 'max'=>6),
            array('receive_address, other_comment, width, example_comment, other_comment', 'length', 'max'=>255),
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
        $basePath = Yii::app()->basePath;
        if ((int)strripos($basePath,'admin') == false) {
            $statusList = require($basePath . "/config/orderType.php");
        }else{
            $statusList = require($basePath . "/../config/orderType.php");
        }
        return $statusList[$this->status];
    }

	/**
	 * 返回需求 说明
	 * @var unknown_type
	 */
	public static function getShootNotice()
	{
	    $basePath = Yii::app()->basePath;
        if ((int)strripos($basePath,'admin') == false) {
            $shootNotice = require($basePath . "/config/shootnotice.php");
        }else{
            $shootNotice = require($basePath . "/../config/shootnotice.php");
        }
	    return $shootNotice;
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