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
    	$sql = "SELECT id,name,password,is_supper FROM {{admin}} WHERE number = :name AND status = 1";
    	$command = Yii::app()->db->createCommand($sql);
    	$admin = (object)$command->queryRow(true, array(':name'=>$this->name));
    	if($admin === null)
    	{
    		$sql = "SELECT id,name,password,is_supper FROM {{admin}} WHERE name = :name AND status = 1";
	    	$command = Yii::app()->db->createCommand($sql);
	    	$admin = (object)$command->queryRow(true, array(':name'=>$this->name));
	    	if ($admin === null)
	    		$this->errorCode=self::ERROR_USERNAME_INVALID;
    	} else
		{
    		if($admin->password != md5(trim($this->password)))
    			$this->errorCode=self::ERROR_PASSWORD_INVALID;
    		else
    		{
    		    $this->_id=$admin->id;
    			$this->username = $admin->name;
    			$this->_isSupper = $admin->is_supper;
    			$this->errorCode=self::ERROR_NONE;
    		}
		}

        return !$this->errorCode;
    }
}
?>