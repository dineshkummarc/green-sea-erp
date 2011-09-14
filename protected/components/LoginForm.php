<?php
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $type = 0;//登陆类型
	public $saveTime = false;//保存时间

	public $identity;

	/*
	 * 声明的验证规则。
	 * 规则状态，需要用户名和密码，
	 * 密码需要进行身份验证。
	 */
	public function rules()
	{
		return array(
			array('username','required','message'=>'用户名不能为空'),
			array('password','required','message'=>'密码不能为空'),
		);
	}
	/*
	 * 声明属性标签
	 */
	public function attributeLabels()
	{
		return array(
			'username'=>'用户编号、手机号码：',
			'password'=>'密&nbsp&nbsp码：',
			'saveTime'=>'两周内自动登录',
		);
	}
	/*
	 * 登陆
	 */
	public function login($time)
	{
		if($this->identity===null)
		{
			$this->identity=new UserIdentity($this->username,$this->password);
			$this->identity->authenticate();
		}
		if($this->identity->errorCode === UserIdentity::ERROR_NONE)
		{
			Yii::app()->user->login($this->identity,$time);
			return true;
		}
		else if ($this->identity->errorCode === UserIdentity::ERROR_USERNAME_INVALID)
		{
		    $this->addError('username', '用户名不存在');
		}
		else if ($this->identity->errorCode === UserIdentity::ERROR_PASSWORD_INVALID)
		{
		    $this->addError('password', '密码错误');
		}
		return false;
	}
}