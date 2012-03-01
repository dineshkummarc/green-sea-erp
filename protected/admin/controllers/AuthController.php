<?php
/**
 * 权限相关控制器
 * @author Yuanchensi
 *
 */
class AuthController extends Controller
{

    /**
     * 管理员管理
     */
    public function actionAdmin($role_id = null, $numPerPage = null, $pageNum = null)
    {
        // 返回所有权限组
        $roles = AdminRole::model()->cache()->findAll();

        $model = new Admin();
        $criteria = new CDbCriteria();

        if ( !empty($role_id))
            $criteria->addCondition("role_id = {$role_id}");
        else
            $role_id = 0;
        //
        // 生成分页信息
        //
        $count = $model->count($criteria);

        $pages = new CPagination($count);
        $pages->currentPage = $pageNum !== null ? $pageNum - 1 : 0;
        $pages->pageSize = $numPerPage !== null ? $numPerPage : 20;
        $pages->applyLimit($criteria);

        $criteria->select = array('id', 'number', 'name', 'login_time', 'login_count', 'last_ip', 'role_id', 'city_id', 'is_supper', 'status');

        $list = $model->cache()->findAll($criteria);
        $this->render("admin", array( 'list'=>$list, 'roles'=>$roles, 'pages'=>$pages, 'role_id'=>$role_id ));
    }

	/**
     * 添加/修改管理员
     */
    public function actionEditAdmin($id = null)
    {
        $admin = new Admin;
        if ($id !== null)
            $admin = $admin->cache()->findByPk($id);

        if (isset($_POST['Form']))
        {
            if (!empty($_POST['Form']['id']))
            {
                $admin = $admin->cache()->findByPk($_POST['Form']['id']);
                $message = '修改成功';
            }
            else
                $message = '添加成功';

            if (!empty($_POST['Form']['id']) && trim($_POST['Form']['password']) == "")
                $_POST['Form']['password'] = $admin->password;
            else
                $_POST['Form']['password'] = md5(trim($_POST['Form']['password']));

            $admin->attributes = $_POST['Form'];
            //if (empty($admin->city_id)) $admin->city_id = 178;
            if (empty($admin->city_id)) $admin->city_id = 1;
            $admin->update_time = Yii::app()->params['timestamp'];
            if ($admin->save())
                $this->success($message, array('navTabId'=>'list'));
            else
            {
                $error = array_shift($admin->getErrors());
                $this->error('错误：'.$error[0]);
            }

        }

        $roles = AdminRole::model()->cache()->findAll();
        //$cities = array();
        $bases = ShootBase::model()->findAll();

        $this->render('admin_edit', array('admin'=>$admin, 'roles'=>$roles, 'bases'=>$bases));
    }

	/**
     * 修改密码
     * @param string $oldPwd
     * @param string $newPwd
     */
    public function actionChangePwd($oldPwd = null, $newPwd = null)
    {
        if (isset($_POST['Form']))
        {
        	$oldPwd = $_POST['Form']['oldPwd'];
        	$newPwd = $_POST['Form']['newPwd'];
        	$newPwd1 = $_POST['Form']['newPwd1'];
        	if (trim($newPwd1) != trim($newPwd)) $this->error('重复密码不匹配');
            $sql = "SELECT `password` FROM {{admin}} WHERE `id` = :id";
            $command = Yii::app()->db->createCommand($sql);
            $password = $command->queryScalar(array(":id"=>Yii::app()->user->id));
            if ($password === md5(trim($oldPwd)))
            {
                if (trim($oldPwd) === trim($newPwd))
                {
                    $this->success('修改成功', array('navTabId'=>'menu-index'));
                }
                $sql = "UPDATE {{admin}} SET `password` = :password, `update_time` = :update_time WHERE id = :id";
                $command = Yii::app()->db->createCommand($sql);
                $count = $command->execute(array(":password"=>md5(trim($newPwd)), ":update_time"=>Yii::app()->params['timestamp'], ":id"=>Yii::app()->user->id));

                if ($count > 0)
                    $this->success('修改成功', array('navTabId'=>'menu-index'));
                else
                    $this->error('错误，请联系管理员');
            }
            else
                $this->error('修改失败，旧密码错误！');
        }
        $this->render('admin_changePwd');
    }

    /**
     * 修改管理员状态
     * @param integer $id
     * @param integer $status
     */
    public function actionChangeStatus($id = null, $status = 1)
    {
        if ( $id === null)
            $this->error('参数传递错误');

        $sql = "UPDATE {{admin}} SET `status` = :status, `update_time` = :update_time WHERE `id` = :id";
        $command = Yii::app()->db->createCommand($sql);
        $count = $command->execute(array(":id"=>$id, ":status"=>$status, ":update_time"=>Yii::app()->params['timestamp']));
        $this->success('修改成功', array('navTabId'=>'list'));
    }

    /**
     * 删除管理员
     * @param array $id
     */
    public function actionDelAdmin(array $id = array())
    {
        if ( $id === null)
            $this->error('参数传递错误');

        // 批量删除
        if (count($id) > 1)
        {
            // 组合成字符串
            $id = implode(',', $id);
            Yii::app()->db->createCommand()->delete("{{admin}}", "id IN ({$id})");
            $this->success('删除成功', array('navTabId'=>'list'));
        }
        else
        {
            $id = $id[0];
            Yii::app()->db->createCommand()->delete("{{admin}}", "id = {$id}");
            $this->success('删除成功', array('navTabId'=>'list'));
        }
    }

    /**
     * 权限组管理
     */
    public function actionRole()
    {
        $roles = AdminRole::model()->cache()->findAll();
        $this->render("role", array('roles'=>$roles));
    }

    /**
     * 修改权限组
     * @param integer $id
     */
    public function actionEditRole($id = null)
    {
        $role = new AdminRole;

        if (isset($_POST['Form']))
        {
            if (!empty($_POST['Form']['id']))
            {
                $role = $role->cache()->findByPk($_POST['Form']['id']);
                $message = '修改成功';
            }
            else
                $message = '添加成功';

            $role->attributes = $_POST['Form'];
            $role->update_time = Yii::app()->params['timestamp'];

            if ($role->save())
                $this->success($message, array('navTabId'=>'auth-role'));
            else
            {
                $error = array_shift($role->getErrors());
                $this->error('错误：'.$error[0]);
            }

        }

        if ($id !== null)
            $role = $role->cache()->findByPk($id);

        $this->render("role_edit", array('role'=>$role));
    }

    /**
     * 删除权限组
     * @param array $id
     */
    public function actionDelRole(array $id = array())
    {
        if ( $id === null)
            $this->error('参数传递错误');

        // 批量删除
        if (count($id) > 1)
        {
            // 组合成字符串
            $id = implode(',', $id);
            // 删除其下属管理员
            Yii::app()->db->createCommand()->delete("{{admin}}", "role_id in ({$id})");
            // 删除其下权限分配信息
            Yii::app()->db->createCommand()->delete("{{admin_role_child}}", "role_id in ({$id})");
            // 删除管理权限组
            Yii::app()->db->createCommand()->delete("{{admin_role}}", "id in ({$id})");
            $this->success('删除成功', array('navTabId'=>'auth-role'));
        }
        else
        {
            $id = $id[0];
            // 删除其下属管理员
            Yii::app()->db->createCommand()->delete("{{admin}}", "role_id = ({$id})");
            // 删除其下权限分配信息
            Yii::app()->db->createCommand()->delete("{{admin_role_child}}", "role_id = ({$id})");
            // 删除管理权限组
            Yii::app()->db->createCommand()->delete("{{admin_role}}", "id = ({$id})");
            $this->success('删除成功', array('navTabId'=>'auth-role'));
        }

    }

    /**
     * 切换角色权限组状态
     * @param integer $id
     * @param integer $status
     */
    public function actionToggleStatus($id = null, $status = 1)
    {
        if ( $id === null)
            $this->error('参数传递错误');

        $sql = "UPDATE {{admin_role}} SET `status` = :status, `update_time` = :update_time WHERE `id` = :id";
        $command = Yii::app()->db->createCommand($sql);
        $count = $command->execute(array(":id"=>$id, ":status"=>$status, ":update_time"=>Yii::app()->params['timestamp']));

        if ($count > 0)
            $this->success('修改成功', array('navTabId'=>'auth-role'));
        else
        {
            $this->error('错误，请联系管理员');
        }

    }

    /**
     * 配置权限组权限
     * @param integer $id
     * @param integer $numPerPage
     * @param integer $pageNum
     */
    public function actionItem($id = null, $name = null, $numPerPage = null, $pageNum = null)
    {
        if ( $id === null)
            $this->error('参数传递错误');
        $role = AdminRole::model()->cache()->findByPk($id);
        $model = new AdminRoleItem;
        $criteria = new CDbCriteria();
        if (!empty($name))
            $criteria->addCondition("t.description like '%{$name}%'");
        $count = $model->count();
        $pages = new CPagination($count);
        $pages->currentPage = $pageNum !== null ? $pageNum - 1 : 0;
        $pages->pageSize = $numPerPage !== null ? $numPerPage : 20;
        $pages->applyLimit($criteria);
        $pages->params = array('id'=>$id);


        $criteria->order .= "rule ASC";
        $allItems = $model->cache()->findAll($criteria);

        $this->render('item', array('role'=>$role, 'allItems'=>$allItems, 'pages'=>$pages));
    }

    /**
     * 编辑权限
     * @param integer $id
     */
    public function actionEditItem($id = null)
    {
        $item = new AdminRoleItem;

        if (isset($_POST['Form']))
        {
            if (!empty($_POST['Form']['id']))
            {
                $item = $item->cache()->findByPk($_POST['Form']['id']);
                $message = '修改成功';
            }
            else
                $message = '添加成功';

            $item->attributes = $_POST['Form'];
            $item->update_time = Yii::app()->params['timestamp'];

            if ($item->save())
                $this->success($message, array('navTabId'=>'auth-role-config'));
            else
            {
                $error = array_shift($item->getErrors());
                $this->error('错误：'.$error[0]);
            }
        }

        $allItems = $item->cache()->findAll();
        if ($id !== null)
            $item = $item->cache()->findByPk($id);

        $this->render('item_edit', array('allItems'=>$allItems, 'item'=>$item));
    }

    /**
     * 删除权限
     * @param integer $id
     */
    public function actionDelItem(array $id = array())
    {
        if ( $id === null)
            $this->error('参数传递错误');
        if ( count($id) > 1 )
            $this->error('暂不支持批量删除');
        else
            $id = $id[0];
        // 连带删除子项
        $sql = "DELETE FROM {{admin_role_item}} WHERE `parent_id` = :id";
        $command = Yii::app()->db->createCommand($sql);
        $count = $command->execute(array(":id"=>$id));

        $sql = "DELETE FROM {{admin_role_item}} WHERE `id` = :id";
        $command = Yii::app()->db->createCommand($sql);
        $count = $command->execute(array(":id"=>$id));
        if ($count > 0)
            $this->success('删除成功', array('navTabId'=>'auth-role-config'));
        else
             $this->error('错误！');
    }

    /**
     * 授权
     * @param integer $id
     * @param integer $roleId
     */
    public function actionAssign($id = null, $roleId = null)
    {
        if ( $id === null || $roleId === null)
            $this->error('参数传递错误');

        // 检查其下权限是否有被授权过
        $sql = "SELECT item_id FROM {{admin_role_child}} WHERE role_id = :roleId";
        $command = Yii::app()->db->createCommand($sql);
        $items = $command->queryAll(true, array(':roleId'=>$roleId));
        foreach ($items as $item)
        {
            // 如果有，则撤销授权
            if ($item['item_id'] == $id)
            {
                $sql = "DELETE FROM {{admin_role_child}} WHERE item_id = :itemId AND role_id = :roleId";
                $command = Yii::app()->db->createCommand($sql);
                $items = $command->execute(array(':itemId'=>$id, ":roleId"=>$roleId));
            }
        }

        $sql = "INSERT INTO {{admin_role_child}} VALUES (:roleId, :itemId)";
        $command = Yii::app()->db->createCommand($sql);
        $command->execute(array(':roleId'=>$roleId, ':itemId'=>$id));

        $this->success('授权成功', array('navTabId'=>'auth-role-config'));
    }

    /**
     * 撤销授权
     * @param integer $id
     * @param integer $roleId
     */
    public function actionRevoke($id = null, $roleId = null)
    {
    	if ( $id === null || $roleId === null)
        	$this->error('参数传递错误');

        Yii::app()->db->createCommand()->delete(
        	"{{admin_role_child}}",
        	"`item_id` = :item_id AND `role_id` = :role_id",
            array(":item_id"=>$id, ":role_id"=>$roleId)
        );

        $this->success('撤销授权成功', array('auth-role-config'));
    }
}
?>