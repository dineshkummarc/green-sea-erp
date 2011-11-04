<?php

/**
 * This is the model class for table "{{user_receive}}".
 *
 * The followings are the available columns in table '{{user_receive}}':
 * @property string $id
 * @property string $user_id
 * @property string $receive_name
 * @property string $phone
 * @property string $mobile_phone
 * @property string $area_id
 * @property string $postalcode
 * @property integer $enable
 */
class UserReceive extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserReceive the static model class
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
		return '{{user_receive}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, receive_name, area_id, street', 'required'),
			array('user_id, receive_name, area_id', 'length', 'max'=>10),
			// 非空验证
			array('receive_name', 'required', 'message'=>'收货人不能为空'),
			array('street', 'required', 'message'=>'详细地址不能为空'),
			// 首尾空格过滤
			array('receive_name, postalcode, phone, mobile_phone', 'filter', 'filter'=>'trim'),
			// 邮编格式验证
			array('postalcode', 'match', 'pattern'=>'/^[0-9]{6}$/', 'message'=>'邮编格式错误'),
			// 电话号码格式验证
			array('phone', 'match', 'pattern'=>'/^(\(\d{3,4}\)|\d{3,4}-)?\d{7,8}$/', 'message'=>'电话号码格式错误'),
			// 手机号码格式验证
			array('mobile_phone', 'match', 'pattern'=>'/^0{0,1}(13[0-9]|15[0-9])[0-9]{8}$/', 'message'=>'手机号码格式错误'),
			// 过滤JS脚本，防止跨站攻击
			array('receive_name, street', 'filter', 'filter'=>'StringFilter::JavascriptFilter')
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
		    'Area'=>array(self::BELONGS_TO, 'Area', 'area_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'receive_name' => 'Receive Name',
			'phone' => 'Phone',
			'mobile_phone' => 'Mobile Phone',
			'area_id' => 'Area',
			'postalcode' => 'Postalcode',
			'enable' => 'Enable',
		);
	}

	public function getFullAddress()
	{
	    return $this->Area->getFullArea() . "，" . $this->street . "，" . $this->postalcode;
	}

}