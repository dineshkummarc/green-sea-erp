<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property string $id
 * @property string $name
 * @property string $mobile_phone
 * @property string $password
 * @property string $score
 * @property string $email
 * @property string $wangwang
 * @property string $qq
 * @property string $page
 * @property string $area_id
 * @property string $create_time
 * @property string $login_time
 * @property string $last_ip
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 添加积分
	 * @param integer $score 积分数量
	 * @param string $reason 添加原因 默认：下单送积分
	 */
	public function cache()
    {
        $duration = 3600 * 24 * 7;
        $dependency = new CDbCacheDependency('SELECT COUNT(*), MAX(update_time) FROM '.$this->tableName());
        return parent::cache($duration, $dependency);
    }


	public static function addScore($score, $reason = "下单送积分")
	{
	    // 更新积分
	    $sql = "UPDATE {{user}} SET score = :socre, update_time = :update_time WHERE id = :user_id";
	    $command = Yii::app()->db->createCommand($sql);
	    $command->bindParam(":socre", $score);
	    $command->bindParam(":update_time", Yii::app()->params['timestamp']);
	    $command->bindParam(":user_id", Yii::app()->user->id);
	    $command->execute();

	    // 保存日志
	    ScoreLog::log($score, $reason);
	}

	public function getArea()
	{

	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, mobile_phone, password, receive_id, area_id, create_time, login_time', 'required'),
			array('name, mobile_phone', 'length', 'max'=>20),
			array('password', 'length', 'max'=>32),
			array('score, qq, receive_id, area_id, create_time, login_time, receive_count', 'length', 'max'=>10),
			array('email, wangwang', 'length', 'max'=>30),
			array('page', 'length', 'max'=>100),
			array('last_ip', 'length', 'max'=>15),
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
		    'ReceiveAddress'=>array(self::BELONGS_TO, 'UserReceive', 'receive_id', 'select'=>'receive_name, phone, mobile_phone, area_id, street, postalcode'),
			'ReceiveAddresses'=>array(self::HAS_MANY, 'UserReceive', 'user_id', 'select'=>'receive_name, phone, mobile_phone, area_id, street, postalcode'),
		    'Area'=>array(self::BELONGS_TO, 'Area', 'area_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'mobile_phone' => 'Mobile Phone',
			'password' => 'Password',
			'score' => 'Score',
			'email' => 'Email',
			'wangwang' => 'Wangwang',
			'qq' => 'Qq',
			'page' => 'Page',
			'area_id' => 'Area',
			'create_time' => 'Create Time',
			'login_time' => 'Login Time',
			'last_ip' => 'Last Ip',
		);
	}
}