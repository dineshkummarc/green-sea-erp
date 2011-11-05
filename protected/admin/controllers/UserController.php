<?php
class UserController extends Controller
{
    /**
     * �û��б�
     * @param array $params
     * @param integer $pageNum
     * @param integer $numPerPage
     */
    public function actionIndex(array $params = array(), $pageNum = null, $numPerPage = null)
    {
        $user = new User;
        $criteria = $user->dbCriteria;
        $criteria->order = "create_time DESC";
        if (!empty($params['name']))
            $criteria->addSearchCondition('name', $params['name']);
        if (!empty($params['phone']))
            $criteria->addCondition('mobile_phone = \'' . $params['phone'] . '\'');
        if (!empty($params['mail']))
            $criteria->addSearchCondition('email', $params['mail']);

        $count = $user->count($criteria);
        $pages = new CPagination($count);
        $pages->currentPage = empty($pageNum) ? 0 : $pageNum - 1;
        $pages->pageSize = empty($numPerPage) ? 20 : $numPerPage;
        $pages->applyLimit($criteria);
        $user = $user->cache()->findAll($criteria);
        $this->render("index", array('userList'=>$user, 'params'=>$params, 'pages'=>$pages));
    }

    /**
     * �޸��û�
     * @param integer $id
     */
    public function actionEdit($id = null)
    {
        $user = new User;
        if (!empty($id))
            $user = $user->cache()->findByPk($id);

        if (isset($_POST['Form']))
        {
            if (!empty($_POST['Form']['id']))
                $user = $user->cache()->findByPk($_POST['Form']['id']);

            if (empty($user->score))
                $user->score = 0;

            // ��������
            if (!empty($user->password) && empty($_POST['Form']['password']))
                $user->password = md5(trim($_POST['Form']['password']));

            $user->attributes = $_POST['Form'];

            if (empty($user->first)) $user->first = 1;
            if (empty($user->accumulation_price)) $user->accumulation_price = 0;
            if (empty($user->receive_id)) $user->receive_id = 0;
            if (empty($user->receive_count)) $user->receive_count = 0;
            if (empty($user->next_order)) $user->next_order = 1;
            if (empty($user->login_time)) $user->login_time = 0;
            if (empty($user->last_ip)) $user->last_ip = 0;
            if (empty($user->create_time)) $user->create_time = Yii::app()->params['timestamp'];
            $user->update_time = Yii::app()->params['timestamp'];

            // �ֻ�����Ψһ����֤
            if ($user->mobile_phone != trim($_POST['Form']['mobile_phone']))
            {
                $sql = "SELECT COUNT(*) FROM fanwe_user WHERE mobile_phone = :phone";
                $command = Yii::app()->db->createCommand($sql);
                $count = $command->queryScalar(array(":phone"=>$_POST['Form']['mobile_phone']));
                if ($count > 0)
                    $this->error("�ֻ�����Ψһ");
            }

            if ($user->save())
                $this->success("�޸ĳɹ�", array('navTabId'=>'user-index'));
            else
                $this->error('����'.Dumper::dumpAsString($user->getErrors(), 10, true));
        }

        $this->render("edit", array('user'=>$user));
    }

    /**
     * ɾ���û�
     * @param array $id
     */
    public function actionDel(array $id = array())
    {
        if ( $id === null)
            $this->error('�������ݴ���');

        // ��ϳ��ַ���
        $id = implode(',', $id);
        $sql = "DELETE FROM {{user}} WHERE id IN ({$id})";
        $this->success('ɾ���ɹ�', array('navTabId'=>'user-index'));
    }

    /**
     * �޸Ļ���
     * @param integer $id
     * @param integer $score
     */
    public function actionChangeScore($id = null, $score = null)
    {
        if (empty($id) || empty($score))
            $this->error("�������ݴ���");

        $sql = "UPDATE ll_erp_user SET score = :score WHERE id = :id";
        $command = Yii::app()->db->createCommand($sql);
        $count = $command->execute(array(":id"=>$id, ":score"=>$score));
        $this->success("�޸ĳɹ�", array('navTabId'=>'user-index'));
    }

}
?>