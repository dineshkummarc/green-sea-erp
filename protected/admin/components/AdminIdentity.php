<?php
class AdminIdentity extends CUserIdentity
{
    private $_id;
    private $_isSupper;

    public function getId()
    {
        return $this->_id;
    }

    public function getIsSupper()
    {
        return $this->_isSupper;
    }

    public function authenticate()
    {
        $admin = Admin::model()->cache()->findByAttributes(array('number' => $this->name, 'status'=>1));
        if ($admin === null) {
        	$admin = Admin::model()->cache()->findByAttributes(array('name' => $this->name, 'status'=>1));
        	if ($admin === null)
        	 $this->errorCode=self::ERROR_USERNAME_INVALID;
        }else if ($admin->password != md5(trim($this->password)))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else
        {
            $this->_id = $admin->id;
            $this->_isSupper = $admin->is_supper;
            $this->errorCode=self::ERROR_NONE;
        }

        return !$this->errorCode;
    }
}
?>