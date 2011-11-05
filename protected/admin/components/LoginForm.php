<?php
class LoginForm extends CFormModel
{
    public $name;
    public $password;
    public $verifyCode;

    private $_identity;

    public function rules()
    {
        return array(
            array('name', 'required', 'message'=>'管理账户不能为空'),
            array('password', 'required', 'message'=>'管理密码不能为空'),
            array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements(), 'message'=>'验证码不能为空'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'name' => '管理账户：',
            'password' => '管理密码：',
            'verifyCode'=>'验证码：',
        );
    }

    public function login()
    {
        if ($this->_identity === null)
        {
            $this->_identity = new AdminIdentity($this->name, $this->password);
            $this->_identity->authenticate();
        }
        switch ($this->_identity->errorCode)
        {
            case AdminIdentity::ERROR_NONE:
                $duration = 0;
                Yii::app()->user->login($this->_identity);
                $this->updateLoginInfo();
                return !Yii::app()->user->isGuest;
                break;
            case AdminIdentity::ERROR_USERNAME_INVALID:
                $this->addError('name', '管理账户不存在或者您的账户被禁用');
                return false;
                break;
            case AdminIdentity::ERROR_PASSWORD_INVALID:
                $this->addError('password', '管理密码错误');
                return false;
                break;
            default:
                $this->addError('title', '错误未知，请联系管理员');
                return false;
                break;
        }
    }

    /**
     * 更新管理员登陆信息
     */
    public function updateLoginInfo()
    {
        $sql = "UPDATE {{admin}} SET login_time = :time, last_ip = INET_ATON(:ip), login_count = login_count + 1 WHERE id = :id";
        $command = Yii::app()->db->createCommand($sql);
        $command->execute(array(
        	':id'=>$this->_identity->getId(),
        	':time'=>Yii::app()->params['timestamp'],
        	':ip'=>Yii::app()->request->userHostAddress
        ));
    }
}
?>