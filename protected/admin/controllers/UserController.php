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
			if (!empty($id))
			{
					$user = $user->cache()->findByPk($id);
					$receiver = UserReceive::model()->findByPk($user->receive_id);
					if (empty($receiver))  $receiver = new UserReceive;

					if ($receiver->area_id == 0)
						$area_list = null;
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
        		    if($_POST['Form']['area_1']==0)
        		    {
        		    		$message="请选择省份";
        		    		$this->error($message);
        		    }
        	      	if($_POST['Form']['area_2']==0)
        		    {
        		    		$message="请选择市区";
        		    		$this->error($message);
        		    }
        	        if($_POST['Form']['area_id']==0)
        		    {
        		    		$message="请选择具体地区";
        		    		$this->error($message);
        		    }
        			if (!empty($_POST['Form']['id']))
        				$user = $user->cache()->findByPk($_POST['Form']['id']);
        		    if (!empty($_POST['Form']['id']) && trim($_POST['Form']['password']) == "")
                			$_POST['Form']['password'] = $user->password;
            		else
                			$_POST['Form']['password'] = md5(trim($_POST['Form']['password']));
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
                	$user->attributes = $_POST['Form'];
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
					if($user->save())
					{

								$receiver->attributes = $_POST['Form'];
								$receiver->user_id=$user->id;
								$receiver->receive_name=$user->name;
								if($receiver->save())
								{
										$user->receive_count+1;
										$user->receive_id=$receiver->id;
										$user->save();
										 if (!empty($_POST['Form']['id']))
										 {
										 		$message="修改成功";
										 		$this->success($message,array('navTabId'=>'user-index') );
										 }
		 								else
		 								{
					 							$message="添加成功";
												$this->success($message,array('navTabId'=>'user-index') );
		 								}
								}
								else
								{
										$error = array_shift($receiver->getErrors());
										 if (!empty($_POST['Form']['id']))  $message="修改失败".$error[0];
										 			else
										 					$message="添加失败";
										$this->error($message);
								}

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
        	//array('statusCode'=>200, 'message'=>$message)
            $areas =  Area::model()->cache()->findAll(array('condition'=>'parent_id = 0'));
            echo CJSON::encode($this->AreaFormat($areas));
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