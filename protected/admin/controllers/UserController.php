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
        if (isset($_POST['Form']))
        {
            if (!empty($_POST['Form']['id']))
                $user = $user->cache()->findByPk($_POST['Form']['id']);
            if (!empty($_POST['Form']['id']) && trim($_POST['Form']['password']) == "")
                    $_POST['Form']['password'] = $user->password;
            else
                    $_POST['Form']['password'] = md5(trim($_POST['Form']['password']));
            $user->attributes = $_POST['Form'];
            $user->first = 1;
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
                $error = array_shift($user->getErrors());
                $message="修改失败".$error[0];
                $this->error($message);
            }
        }
        $this->render("edit", array('user'=>$user));
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
    public function actionArea($type)
    {
        if ($type == 'all')
        {
            $areas =  Area::model()->cache()->findAll(array('condition'=>'parent_id = 0'));
            echo json_encode($this->AreaFormat($areas));
//            print_r ($this->AreaFormat($areas));
            return;
        }else{
            $areas =  Area::model()->cache()->findAll(array('condition'=>'parent_id = '.$type));
            echo json_encode($this->AreaFormat($areas));
//            print_r ($this->AreaFormat($areas));
            return;
        }
    }
    public function AreaFormat($areas)
    {
        $list = array();
        foreach ($areas as $area)
        {
            $list[$area->id][0] = $area->id;
            $list[$area->id][1] = $area->name;
        }
        return $list;
    }
}
?>