<?php
class UserIdentity extends CUserIdentity
{
    private $_id;

	/**
	 * @return integer the ID of the user record
	 */
	public function getId()
	{
		return $this->_id;
	}

	/*
	 * 验证用户
	 */
	public function authenticate()
	{
		$user=User::model()->findByAttributes(array('mobile_phone'=>$this->username));
		if($user===null)
		{
		    $id = substr($this->username, 1);
		    $user=User::model()->findByAttributes(array('id'=>(int)$id));
		    if($user===null)
    		    $this->errorCode=self::ERROR_USERNAME_INVALID;
		}
		if ($user !== null)
		{
    		if($user->password!=strtolower(md5($this->password)))
    			$this->errorCode=self::ERROR_PASSWORD_INVALID;
    		else
    		{
    		    $this->_id=$user->id;
    			$this->username=$user->name;
    			$this->setState("nextOrder", $user->next_order);
    			$this->errorCode=self::ERROR_NONE;
    		}
		}
		return $this->errorCode;
	}
}