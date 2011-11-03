<?php
class UserController extends Controller
{
    public $defaultAction = 'login';
    public $cssFiles = array('user.css');
    public $jsFiles = array('user.js');

    public function actionInfo()
    {
        $userInfo = User::model()->with('ReceiveAddress')->findByAttributes(array('id'=>Yii::app()->user->id),
            array("select"=>'t.name, t.wangwang, t.qq, t.mobile_phone, t.page, t.receive_id, t.score'));
        $this->render('info', array('info'=>$userInfo));
    }

	//登陆方法
	public function actionLogin()
	{
	    if (!Yii::app()->user->isGuest)
	    {
	        if (Yii::app()->user->id == 999)
                $this->redirect(array("order/index"));
            else
	            $this->redirect(array('user/info'));
	    }
	    $this->layout = false;
	    array_push($this->cssFiles, 'login.css');
		$model=new LoginForm;
	    if(isset($_POST['LoginForm']))
	    {
	    	$model->attributes=$_POST['LoginForm'];
	        $time=isset($_POST['LoginForm']['saveTime']) ? 3600*24*14 : 0; //14天
	        // 验证用户输入，并在判断输入正确后重定向到前一页（内置的验证validate）
	        if($model->validate() && $model->login($time))
	        {
	            if (Yii::app()->user->id == 999)
	                $this->redirect(array("order/index"));
				$this->redirect($this->getUrlReferrer($this->createUrl('user/info')));
	        }
	    }
	    // 显示登录表单
	    $this->render('login',array('model'=>$model));
	}

	/*
	 * 注销
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	/**
	 * 添加一个客户
	 */
	public function actionAddUser()
	{
	    if (Yii::app()->user->id != 999)
	        $this->redirect("user/index");

	    if (isset($_POST['Form']))
	    {
	        $user = new User;
	        $user->first = 1;
	        $user->score = 0;
	        $user->accumulation_price = 0;
	        $user->receive_id = 0;
	        $user->receive_count = 0;
	        $user->create_time = Yii::app()->params['timestamp'];
	        $user->update_time = Yii::app()->params['timestamp'];
	        $user->next_order = 1;
	        $user->login_time = 0;
	        $user->last_ip = "";

	        $user->attributes = $_POST['Form'];
	        $user->password = md5($user->password);

	        if ($user->save())
	        {
	            $this->success("添加新用户成功，用户名：".$user->name);
	            $this->refresh();
	        }
	    }

	    $area = Area::model()->findAllByAttributes(array("parent_id"=>0));
	    $this->render("addUser", array(
	        "areaList"=>$area
	    ));
	}

	/**
	 * 修改客户资料
	 * @param integer $type
	 */
	public function actionEditInfo($type = 1)
	{
	    $id = Yii::app()->user->id;
	    $userInfo = User::model()->findByPk($id);

	    if (isset($_POST['Form']))
	    {
            $userInfo->attributes = $_POST['Form'];
            if ($userInfo->save())
                $this->success("修改成功");
            else
                $this->error(Dumper::dumpString($userInfo->getErrors()));

            $this->redirect(array("user/editInfo"));
	    }

        $this->render('editInfo', array('info'=>$userInfo));
	}

	public function actionReceive()
	{
	    $id = Yii::app()->user->id;
	    if (isset($_POST['Form']))
	    {
	        $userInfo = User::model()->findByPk($id);
	        if ($userInfo->receive_count > 10)
	        {
	            $this->error('最多保存10个收货地址');
	            $this->redirect("user/receive");
	        }
	        else
	        {
    	        // 判断是添加还是修改
    	        $isEdit = !empty($_POST['Form']['id']);
    	        if ($isEdit)
    	            $receiver = UserReceive::model()->findByPk($_POST['Form']['id']);
                else
                    $receiver = new UserReceive;

                // 处理固定电话号码以及手机号码
                $phone1 = trim($_POST['Form']['phone-1']);
                $phone2 = trim($_POST['Form']['phone-2']);
                $phone3 = trim($_POST['Form']['phone-3']);
                unset($_POST['Form']['phone-1'], $_POST['Form']['phone-2'], $_POST['Form']['phone-3']);
                if (empty($phone1) && empty($phone2))
                    $_POST['Form']['phone'] = "";
                else
                    $_POST['Form']['phone'] = $phone1 . "-" . $phone2;
                if (!empty($phone) && empty($phone3))
                {
                     $_POST['Form']['phone'] .= "-" . $phone3;
                }
                $_POST['Form']['phone'] = trim($_POST['Form']['phone']);

                $receiver->attributes = $_POST['Form'];
                $receiver->user_id = $id;

    	        if ($receiver->save())
    	        {
    	            if (!empty($_POST['Form']['default']))
                        $userInfo->receive_id = $receiver->id;
    	            if (!$isEdit)
    	                $userInfo->receive_count += 1;

    	            $userInfo->save();
    	            $this->success("操作成功", "address-success");
    	            $this->refresh();
    	        }
    	        else
    	        {
    	            $this->error(Dumper::dumpString($userInfo->getErrors()));
    	            $this->refresh();
    	        }

    	        $this->redirect(array("user/receive"));
	        }
	    }
	    // 获取当前用户默认收货地址
	    $userInfo = User::model()->findByPk($id, array('select'=>'id, receive_id'));
	    // 获取省份信息
	    $area = Area::model()->findAreaByLevel();
	    $shi = Area::model()->findAreaByLevel(2);
	    $qu = Area::model()->findAreaByLevel(3);
	    // 注册JS脚本以及CSS文件
	    $cs = Yii::app()->getClientScript();
	    $cs->registerScriptFile("js/area.js");
	    $cs->registerScript("", "$(userInit);", 0);
	    $receiveData = array();
	    foreach ($userInfo->ReceiveAddresses as $receive)
	    {
	        $receiveData[$receive->id]['name'] = $receive->receive_name;
	        $receiveData[$receive->id]['area'] = $receive->area_id;
	        $receiveData[$receive->id]['street'] = $receive->street;
	        $receiveData[$receive->id]['postalcode'] = $receive->postalcode;
	        $receiveData[$receive->id]['phone'] = $receive->phone;
	        $receiveData[$receive->id]['mobile_phone'] = $receive->mobile_phone;
	    }
	    $cs->registerScript("receiveData", "var receiveData = " . CJSON::encode($receiveData) . ";", 0);
	    $this->render('editReceive', array('info'=>$userInfo, 'area'=>$area));
	}

	public function actionChangePwd()
	{
	    if (isset($_POST['Form']))
	    {
	        $userInfo = User::model()->findByPk(Yii::app()->user->id);

	        $oldpassword = trim($_POST['Form']['oldpassword']);
	        $newpassword = trim($_POST['Form']['newpassword']);
	        $repassword = trim($_POST['Form']['repassword']);

	        if (md5($oldpassword) !== $userInfo->password)
	        {
	            $this->error('旧密码验证失败');
	            $this->redirect(array('user/changePwd'));
	        }

	        if (strlen($newpassword) <= 5)
	        {
	            $this->error("密码最短5位");
	            $this->redirect(array('user/changePwd'));
	        }

	        if ($newpassword !== $repassword)
	        {
	            $this->error('两次密码不相同');
	            $this->redirect(array('user/changePwd'));
	        }

	        $userInfo->password = md5($newpassword);
	        if ($userInfo->save())
	            $this->success("密码修改成功");
            else
                $this->error(Dumper::dumpString($userInfo->getErrors()));

	        $this->redirect(array('user/changePwd'));
	    }
	    $this->render('changePwd');
	}

	/*
	 * 删除地址
	 */
	public function actionDelReceive($id = null)
	{
	    $userInfo = User::model()->findByPk(Yii::app()->user->id);
	    if ($id == null)
	    {
	        $this->error('参数传递有误');
	        $this->redirect(array("user/receive"));
	    }
	    else
	    {
	        // 删除收货地址
	        $rows = UserReceive::model()->deleteByPk($id, array("condition"=>"user_id = ".Yii::app()->user->id));
	        if ($rows > 0)
	        {
	            // 修改收货地址署数量
	            $userInfo->receive_count -= 1;
	            $userInfo->save();
		        $this->success('删除成功');
		        $this->redirect(array("user/receive"));
	        }
	        else
	        {
	            $this->error("操作失败，您没有权限或者发生错误");
	            $this->redirect(array("user/receive"));
	        }

            $this->redirect(array('user/receive'));
	    }
	}

	/**
	 * 积分模块
	 * @param integer $time 查询时间段
	 * @param integer $page 当前页
	 * @param integer $size
	 */
	public function actionScore($time = 30, $page = 1, $size = 10)
	{
	    // 确定查询时间段
	    $timestamp = Yii::app()->params['timestamp'];
	    $timestamp = $timestamp - ( 3600 * 24 * $time );

	    // 构造查询条件
	    $criteria = new CDbCriteria;
	    $criteria->condition = "user_id = :user_id AND create_time >= :create_time";
	    $criteria->params = array(":user_id"=>Yii::app()->user->id, ":create_time"=>$timestamp);
	    $total = ScoreLog::model()->count($criteria);

	    // 构造分页
	    $pages = new CPagination($total);
	    $pages->currentPage = $page - 1;
	    $pages->pageSize = $size;
	    $pages->applyLimit($criteria);

	    // 查询日志
	    $logs = ScoreLog::model()->findAll($criteria);
	    // 查询当前用户积分
	    $score = User::model()->findByPk(Yii::app()->user->id, array("select"=>"score"))->score;

	    $this->render("score", array('time'=>$time, 'score'=>$score, 'logs'=>$logs, 'pages'=>$pages));
	}

	/**
	 * 积分说明
	 */
	public function actionScoreExplain()
	{
	    $this->render("scoreInfo");
	}

	public function actionSetDefault($id = null)
	{
	    $userInfo = User::model()->findByPk(Yii::app()->user->id);
	    if ($id == null)
	    {
	        $this->error('参数传递有误', 'address-error');
	    }
	    else
	    {
    	    $userInfo->receive_id = $id;
    	    if ($userInfo->save())
    	        $this->success('设置默认地址成功', 'address-success');
    	    else
    	        $this->error(Dumper::dumpString($userInfo->getErrors()), 'address-error');

	        $this->redirect(array('user/receive'));
	    }
	}

	/**
	 * 意见反馈
	 */
	public function actionContact()
	{
	    if (isset($_POST['Form']))
	    {
	        $model = new UserContact;
	        $model->user_id = Yii::app()->user->id;
	        $model->content = $_POST['Form']['content'];
	        if ($model->save())
	        {
	            $this->success("提交成功， 我们会尽快处理您反馈的情况");
	        }
	        else
	        {
	            $this->error(Dumper::dumpString($model->getErrors()));
	        }
	        $this->redirect(array("user/contact"));
	    }
	    $this->render("contact");
	}

	/**
	 * 作品下载
	 */
	public function actionDownload()
	{
	}

    public function actionList()
    {
        $userList = User::model()->findAll();
        $this->render('list', array('userList'=>$userList));
    }

    public function actionDelUser($id = null)
    {
        if (empty($id))
        {
            $this->error("参数错误");
            $this->redirect(array("user/list"));
        }

        $sql = "DELETE FROM {{user}} WHERE id = :id";
        $command = Yii::app()->db->createCommand($sql);
        $command->execute(array(':id'=>$id));
        $this->success("删除成功");
        $this->redirect(array("user/list"));
    }

}