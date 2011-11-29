<?php
class UserController extends Controller
{
    /**
     * 用户列表
     * @param array $params
     * @param integer $pageNum
     * @param integer $numPerPage
     */
    public function actionIndex(array $params = array(), $pageNum = null, $numPerPage = null)
    {

        $user = new User;
        $sheng = Area::model()->findAreaByLevel();
        $shi = Area::model()->findAreaByLevel(2);
        $qu = Area::model()->findAreaByLevel(3);
           $area['sheng'] = $sheng;
        $area['shi'] = $shi;
        $area['qu'] = $qu;
        $criteria = $user->dbCriteria;
        $criteria->order = "create_time DESC";
        if (!empty($params['name']))
            $criteria->addSearchCondition('name', $params['name']);
        if (!empty($params['phone']))
            $criteria->addCondition('mobile_phone = \'' . $params['phone'] . '\'');
        if (!empty($params['mail']))
            $criteria->addSearchCondition('email', $params['mail']);
        if(!empty($params['id']))
            $criteria->addSearchCondition('id',intval(substr($params['id'], 1)));
        $count = $user->count($criteria);
        $pages = new CPagination($count);
        $pages->currentPage = empty($pageNum) ? 0 : $pageNum - 1;
        $pages->pageSize = empty($numPerPage) ? 20 : $numPerPage;
        $pages->applyLimit($criteria);
        $user = $user->cache()->findAll($criteria);
        $this->render("index", array('userList'=>$user, 'params'=>$params, 'pages'=>$pages,'area'=>$area));
    }

    /**
     * 修改/添加用户
     * @param integer $id
     */
	public  function  actionEdit($id=null)
	{
		$user = new User;
		$receiver = new UserReceive;
		$area_list = '';
		if (!empty($id))
		{
			$user = $user->cache()->findByPk($id);
			$receiver = UserReceive::model()->findByPk($user->receive_id);
			if (empty($receiver))  $receiver = new UserReceive;

			if ($receiver->area_id == 0)
				$area_list = Area::getAreaLevelAll(0);
			else
				$area_list = Area::getAreaLevelAll($receiver->area_id);
		}
		else
		{
			$area_list = Area::getAreaLevelAll(0);
			$receiver = new UserReceive;
		}
	    if (isset($_POST['Form']))
        {
        	if (!empty($_POST['Form']['id']))
        		$user = $user->cache()->findByPk($_POST['Form']['id']);
        	if (!empty($_POST['Form']['receiver_id']))
        		$receiver = $receiver->findByPk($_POST['Form']['receiver_id']);
			if (!empty($_POST['Form']['id']))
			{
				$message="修改成功";
			}
 			else
 			{
			 	$message="添加成功";
			 	$user->first = 1;
            	$user->area_id = 0;
            	$user->admin_id=Yii::app()->user->id;
				$user->accumulation_price = 0;
				$user->receive_id = 0;
				$user->receive_count = 0;
				$user->next_order = 1;
				$user->login_time = 0;
				$user->last_ip = 0;
				$user->create_time = Yii::app()->params['timestamp'];
 			}

        	if (!empty($_POST['Form']['id']) && trim($_POST['Form']['password']) == "")
            	$_POST['Form']['password'] = $user->password;
            else
                $_POST['Form']['password'] = md5(trim($_POST['Form']['password']));


			if (preg_match("/^1[358][0-9]{9}$/",trim($_POST['Form']['mobile_phone'])) <= 0)
			{
				$this->error('请输入正确的手机号码');
			}
        	if (preg_match("/^\d{5,10}$/",trim($_POST['Form']['qq'])) <= 0)
			{
				$this->error('请输入正确的QQ号码');
			}
			//收获地址验证
        	if (isset($_POST['Form']['receive']['optional']))
        	{
        		$receive_name = trim($_POST['Form']['receive']['receive_name']);
				if (empty($receive_name)) $this->error("收货人姓名不能为空");

        		$receive_mobile_phone = trim($_POST['Form']['receive']['mobile_phone']);
				if (empty($receive_mobile_phone)) $this->error("收货人手机不能为空");

        		$receive_street = trim($_POST['Form']['receive']['street']);
				if (empty($receive_street)) $this->error("详细地址不能为空");

        		$receive_postalcode = trim($_POST['Form']['receive']['postalcode']);
				if (empty($receive_street)) $this->error("邮政编码不能为空");

	        	if($_POST['Form']['receive']['area_1']==0) $this->error("请选择省份");
	        	if($_POST['Form']['receive']['area_2']==0) $this->error("请选择市区");
	        	if($_POST['Form']['receive']['area_id']==0) $this->error("请选择具体地区");
				if (preg_match("/^1[358][0-9]{9}$/",trim($_POST['Form']['receive']['mobile_phone'])) <= 0) $this->error('[收货人手机]请输入正确的手机号码');

				$phone1 = trim($_POST['Form']['receive']['phone-1']);
		        $phone2 = trim($_POST['Form']['receive']['phone-2']);
		        $phone3 = trim($_POST['Form']['receive']['phone-3']);
	            unset($_POST['Form']['phone-1'], $_POST['Form']['phone-2'], $_POST['Form']['phone-3']);
	            if (empty($phone1) && empty($phone2))
	            	$phone = "";
	            else
	                $phone = $phone1 . "-" . $phone2;
	            if (!empty($phone) && !empty($phone3))
	            {
	                $phone .= "-" . $phone3;
	            }
	            if (preg_match("/^(\(\d{3,4}\)|\d{3,4}-)?\d{7,8}$/",trim($phone)) <= 0) $this->error('请输入正确的座机号码');
	        	if (preg_match("/^\\d{6}$/",trim($_POST['Form']['receive']['postalcode'])) <= 0) $this->error('请输入正确的邮编');

				if (empty($user->id) && empty($receiver->id))
					$user->receive_count = 1;
				elseif (!empty($user->id) && empty($receiver->id))
					$user->receive_count += 1;

	        	$receiver->receive_name = $_POST['Form']['receive']['receive_name'];//收货人姓名
	        	$receiver->phone = $phone;
	        	$receiver->mobile_phone = $_POST['Form']['receive']['mobile_phone'];//收货人手机
	        	$receiver->area_id = $_POST['Form']['receive']['area_id'];
	        	$receiver->street = $_POST['Form']['receive']['street'];
				$receiver->postalcode = $_POST['Form']['receive']['postalcode'];
				$receiver->user_id = 0;
				$receiver->save();

				$user->receive_id = $receiver->id;
	        	$user->phone = $phone;
	        	$user->area_id = $_POST['Form']['receive']['area_id'];
        	}

            $user->attributes = $_POST['Form'];
			$user->update_time = Yii::app()->params['timestamp'];

			if($user->save())
			{
				if (isset($_POST['Form']['receive']['optional']))
	        	{
					$sql = "update {{user_receive}} SET user_id = :user_id WHERE id = :id";
					$command = Yii::app()->db->createCommand($sql);
					$command->execute(array(':user_id'=>$user->id,':id'=>$user->receive_id));
	        	}
//				$this->error(CVarDumper::dump($user->attributes));
//				Yii::app()->end();
				$this->success($message,array('navTabId'=>'user-index'));
			}
			else
			{
				$error = array_shift($user->getErrors());
				$message="修改失败".$error[0];
				$this->error($message);
			}
       }
       $this->render("edit", array('user'=>$user,'area_list'=>$area_list,'receiver'=>$receiver));
	}



    /**
     * 删除用户
     * @param array $id
     */
    public function actionDel(array $id = array())
    {
        if ( $id === null)
            $this->error('参数传递错误');

        // 组合成字符串
        $id = implode(',', $id);
        $sql = "DELETE FROM {{user}} WHERE id IN ({$id})";
        $command = Yii::app()->db->createCommand($sql);
        $count = $command->execute();
        $this->success('删除成功', array('navTabId'=>'user-index'));
    }

    /**
     * 修改积分
     * @param integer $id
     * @param integer $score
     */
    public function actionChangeScore($id = null, $score = null)
    {
        if (empty($id) || empty($score))
            $this->error("参数传递错误");

        $sql = "UPDATE ll_erp_user SET score = :score WHERE id = :id";
        $command = Yii::app()->db->createCommand($sql);
        $count = $command->execute(array(":id"=>$id, ":score"=>$score));
        $this->success("修改成功", array('navTabId'=>'user-index'));
    }

    public function actionArea($id)
    {
        if ($id == '0')
        {
            echo CJSON::encode(array('status'=>0));
            return;
        }else{
            $areas =  Area::model()->cache()->findAll(array('condition'=>'parent_id = '.$id));
            echo CJSON::encode($this->AreaFormat($areas));
            return;
        }
    }
    public function AreaFormat($areas)
    {
    	$list = array();
		foreach ($areas as $key=>$area)
		{
			$list[] = array('id'=>$area->id,'name'=>$area->name);
		}
		return $list;
    }
}
?>