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
        		if ($receiver === null) $receiver = new UserReceive();

	        	if($_POST['Form']['receive']['area_1']==0) $this->error("请选择省份");
	        	if($_POST['Form']['receive']['area_2']==0) $this->error("请选择市区");
	        	if($_POST['Form']['receive']['area_id']==0) $this->error("请选择具体地区");

				$phone1 = trim($_POST['Form']['receive']['phone-1']);
		        $phone2 = trim($_POST['Form']['receive']['phone-2']);
		        $phone3 = trim($_POST['Form']['receive']['phone-3']);
	            unset($_POST['Form']['phone-1'], $_POST['Form']['phone-2'], $_POST['Form']['phone-3']);
        		if (empty($phone1) && empty($phone2))
                    $_POST['Form']['receive']['phone'] = "";
                else
                    $_POST['Form']['receive']['phone'] = $phone1 . "-" . $phone2;
                if (!empty($phone2) && !empty($phone3))
                {
                     $_POST['Form']['receive']['phone'] .= "-" . $phone3;
                }

				if (empty($user->id) && empty($receiver->id))
					$user->receive_count = 1;
				elseif (!empty($user->id) && empty($receiver->id))
					$user->receive_count += 1;

				$receiver->attributes = $_POST['Form']['receive'];
				$receiver->user_id = 0;
				if($receiver->save())
				{
					$user->receive_id = $receiver->id;
	        		$user->phone = $_POST['Form']['receive']['phone'];
	        		$user->area_id = $_POST['Form']['receive']['area_id'];
				}else
				{
					$error = array_shift($receiver->getErrors());
					$message="失败".$error[0];
					$this->error($message);
				}

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

	//查看个人信息
	public function actionInfo($id = null)
	{
	    $sql = "SELECT	* FROM {{user}} WHERE id = :id";
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->queryRow(true, array(':id'=>$id));
        $user = (object)$result;
        if ($user != false){
            $sql = "SELECT	* FROM {{user_receive}} WHERE user_id = :id";
            $command = Yii::app()->db->createCommand($sql);
            $result = $command->queryAll(true, array(':id'=>$user->id));
            $receiver = $result;
        }

	    $this->render("info", array(
	    	'user'=>$user,
	    	'receiver'=>$receiver
	    ));
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
		$sql = "SELECT id FROM {{order}} WHERE user_id IN ($id)";
        $command = Yii::app()->db->createCommand($sql);
        $order = $command->queryAll();

        $order_id = '';
        foreach ($order as $key=>$val)
        {
        	if ($key > 0) $order_id .= ',';
        	$order_id .= $val['id'];
        }

		if (!empty($order_id))
		{
		    //删除订单表
		    $sql = "DELETE FROM {{order}} WHERE id IN ($order_id)";
	        $command = Yii::app()->db->createCommand($sql);
	        $count = $command->execute();
	        //删除订单模特
	        $sql = "DELETE FROM {{order_model}} WHERE order_id IN ($order_id)";
	        $command = Yii::app()->db->createCommand($sql);
	        $count = $command->execute();
	        //订单物品
	        $sql = "DELETE FROM {{order_goods}} WHERE order_id IN ($order_id)";
	        $command = Yii::app()->db->createCommand($sql);
	        $count = $command->execute();
	        //删除订单跟进表
	        $sql = "DELETE FROM {{order_track}} WHERE order_id IN ($order_id)";
	        $command = Yii::app()->db->createCommand($sql);
	        $count = $command->execute();
	        //订单排程
	        $sql = "DELETE FROM {{schedule}} WHERE order_id IN ($order_id)";
	        $command = Yii::app()->db->createCommand($sql);
	        $count = $command->execute();
	        //仓储物品
	        $sql = "DELETE FROM {{storage_goods}} WHERE storage_id IN (SELECT id FROM {{storage}} WHERE order_id IN ($order_id))";
	        $command = Yii::app()->db->createCommand($sql);
	        $count = $command->execute();
	        //订单仓储
	        $sql = "DELETE FROM {{storage}} WHERE order_id IN ($order_id)";
	        $command = Yii::app()->db->createCommand($sql);
	        $count = $command->execute();
    	}
        //删除用户
        $sql = "DELETE FROM {{user}} WHERE id IN ($id)";
        $command = Yii::app()->db->createCommand($sql);
        $count = $command->execute();
        //删除地址
        $sql = "DELETE FROM {{user_receive}} WHERE user_id IN ($id)";
        $command = Yii::app()->db->createCommand($sql);
        $count = $command->execute();
        //删除积分log
        $sql = "DELETE FROM {{score_log}} WHERE user_id IN ($id)";
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

    public function getArea($id = null)
    {
        $area = array();
        if (!empty($id))
        {
            $sql = "SELECT parent_id, name FROM {{area}} WHERE id = :id";
            $command = Yii::app()->db->createCommand($sql);
            $result = $command->queryRow(true, array(':id'=>$id));
            $city_3 = $result['name'];
            if ($result['parent_id'] != 0){
                $sql = "SELECT parent_id, name FROM {{area}} WHERE id = :id";
                $command = Yii::app()->db->createCommand($sql);
                $result = $command->queryRow(true, array(':id'=>$result['parent_id']));
                $city_2 = $result['name'];
                if ($result['parent_id'] != 0){
                    $sql = "SELECT parent_id, name FROM {{area}} WHERE id = :id";
                    $command = Yii::app()->db->createCommand($sql);
                    $result = $command->queryRow(true, array(':id'=>$result['parent_id']));
                    $city_1 = $result['name'];
                }
            }
        }
        $area = isset($city_1) ? $city_1.' ' :'';
        $area .= isset($city_2) ? $city_2.' ' :'';
        $area .= isset($city_3) ? $city_3.' ' :'';
        return $area;
    }
}
?>