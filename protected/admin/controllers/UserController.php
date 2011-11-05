<?php
	class UserController extends Controller
	{
	/**
	 * 用户列表
	 * @param unknown_type $pageNum
	 * @param unknown_type $numPerPage
	 */
	 public function actionIndex(array $params = array(), $pageNum = null, $numPerPage = null)
    {
        $user = new User;
        $criteria = new CDbCriteria;
        $criteria = $user->dbCriteria;
        $criteria->order = "create_time DESC";
        if (!empty($params['name']))
            $criteria->addSearchCondition('name', $params['name']);
        if (!empty($params['phone']))
            $criteria->addCondition('mobile_phone = \'' . $params['phone'] . '\'');
        if (!empty($params['mail']))
            $criteria->addSearchCondition('email', $params['mail']);
       	$count = User::model()->cache()->count($criteria);
        $pages = new CPagination($count);
        $pages->currentPage = empty($pageNum) ? 0 : $pageNum - 1;
        $pages->pageSize = empty($numPerPage) ? 20 : $numPerPage;
        $pages->applyLimit($criteria);
        $user = $user->cache()->findAll($criteria);
        $this->render("index", array('userList'=>$user, 'params'=>$params, 'pages'=>$pages));
    }
	/**
	 * 添加用户列表
	 *
	 *
	 */
		public function actionAddUser($id=null)
		{
			$user=new User;



		    if (isset($_POST['Form']))
		    {


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
		            $this->success('添加新用户成功，用户名：'.$user->name);

		            $this->refresh();
		        }
			     else
	            {
	                $error = array_shift($user->getErrors());
	                $message = '错误：'.$error[0];
	                $this->error($message);
	            }
		    }

		    $area = Area::model()->findAllByAttributes(array("parent_id"=>0));
		    $this->render('editUser', array(
		        'areaList'=>$area,
		    	'user'=>$user
		    ));

		}

	/**
	 * 修改用户列表
	 *
	 *
	 */

		public function actionEdit($id = null)
	    {
	        if (empty($id))
	            $id = $_POST['Form']['id'];
	        if (empty($id))
	            $this->error("参数传递错误");
	        $user = User::model()->cache()->findByPk($id);
	        if (isset($_POST['Form']))
	        {
	            $user->attributes = $_POST['Form'];
	            // 保存不存在 中的属性
	            $user->score = $_POST['Form']['score'];
	            // 密码重置
	            $_POST['Form']['re_pwd'] = trim($_POST['Form']['re_pwd']);
	            if (!empty($_POST['Form']['re_pwd']))
	                $user->password = md5(trim($_POST['Form']['re_pwd']));

	            // 手机号码唯一性验证
	            if ($user->mobile_phone != trim($_POST['Form']['mobile_phone']))
	            {
	                $sql = "SELECT COUNT(*) FROM fanwe_user WHERE mobile_phone = :phone";
	                $command = Yii::app()->db->createCommand($sql);
	                $count = $command->queryScalar(array(":phone"=>$_POST['Form']['mobile_phone']));
	                if ($count > 0)
	                    $this->error("手机号码已被注册");
	            }

	            if ($user->save())
	                $this->success("修改成功", array('navTabId'=>'user-index'));
	            else
	            {
	                $this->error('错误：'.Dumper::dumpAsString($user->getErrors(), 10, true));
	            }
	        }

	        $this->render("edit", array('user'=>$user));
	    }

	/**
	 * 修改积分
	 *
	 *
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



	/**
	 * 删除用户列表
	 *
	 *
	 */
		public function actionDelete(array $id = array())
		{
			if (empty($id))
	            $this->error('参数传递错误');
	        if (count($id) > 1)
	            $this->error('暂不能批量删除');
	        else
	            $id = $id[0];

	        $users = User::model();
	        $count = $users->deleteByPk($id);
	        if ($count > 0)
	            $this->success('删除成功', array('navTabId'=>'user-index'));
	        else
	        {
	            $error = array_shift($ad->getErrors());
	            $this->error('错误：'.$error[0]);
	        }
		}
	}