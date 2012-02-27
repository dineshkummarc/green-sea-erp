<?php

class NoticeController extends Controller
{
	public function actionIndex(array $params = array(),$pageNum = null,$numPerPage = null)
	{//公告管理
	    $notice = new Notice;
	    $criteria = new CDbCriteria(array('select'=>'*',));

	    if (!empty($params['title']))
	        $criteria->addSearchCondition('title', $params['title']);

	    if(!empty($params['content']))
	        $criteria->addSearchCondition('content', $params['content']);

	    if(!empty($params['Admin.name']))
	        $criteria->addSearchCondition('Admin.name', $params['Admin.name']);

	    $count = $notice->with('Admin')->count($criteria);

	    $pages = new CPagination($count);
	    $pages->currentPage = empty($pageNum) ? 0 : $pageNum - 1;
	    $pages->pageSize = empty($numPerPage) ? 20 : $numPerPage;
	    $pages->applyLimit($criteria);
	    $noticeList = $notice->with('Admin')->findAll($criteria);
		$this->render('index',array('noticeList'=>$noticeList,'pages'=>$pages,'params'=>$params));
	}
	public function actionEdit($id = NULL)
	{//公告添加修改
	    $notice = new Notice;
	    if(isset($_POST['Form'])){
	        if(!empty($_POST['Form']['id'])){
	            $notice = $notice->findByPk($_POST['Form']['id']);
	            $message = '公告修改成功';
	        }else{
	            $notice->admin_id = Yii::app()->user->id;
	            $notice->create_time = Yii::app()->params['timestamp'];
	            $message = '公告添加成功';
	        }
	        $notice->update_time = Yii::app()->params['timestamp'];
	        $notice->attributes = $_POST['Form'];
	        if ($notice->save())
	            $this->success($message, array('navTabId'=>'notice-index'));
	        else
	        {
	            $error = array_shift($notice->getErrors());
	            $this->error('错误：'.$error[0]);
	        }
	    }
		if($id !== NULL){
	        $notice = $notice->findByPk($id);
	    }
	    $this->render('edit',array('notice'=>$notice));
	}

	public function actionDel(array $id = array())
	{//删除公告
	    if(empty($id)){
	        $this->error('参数传递错误');
	    }
	    $id = implode(',', $id);
	    $sql = "SELECT id FROM {{notice}} WHERE id IN ($id)";
	    $command = Yii::app()->db->createCommand($sql);
	    $noticeList = $command->queryAll();
	    $notice_id = '';
	    foreach ($noticeList as $key=>$val)
	    {//验证是否存在
	        if ($key > 0) $notice_id .= ',';
	        $notice_id .= $val['id'];
	    }
	    if (!empty($notice_id))
	    {
	        $sql = "DELETE FROM {{notice}} WHERE id IN ($notice_id)";
	        $command = Yii::app()->db->createCommand($sql);
	        $command->execute();
	        $this->success('删除成功', array('navTabId'=>'notice-index'));
	    }
	    $this->error(CVarDumper::dumpAsString($notice_id));
	}
}