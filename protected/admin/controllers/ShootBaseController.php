<?php

class ShootBaseController extends Controller
{
	public function actionIndex(array $params = array(),$pageNum = null,$numPerPage = null)
	{//公告管理
	    $shootbase = new ShootBase;
	    $criteria = new CDbCriteria(array('select'=>'*',));

	    if(!empty($params['name']))
	        $criteria->addSearchCondition('name', $params['name']);
	    $count = $shootbase->count($criteria);

	    $pages = new CPagination($count);
	    $pages->currentPage = empty($pageNum) ? 0 : $pageNum - 1;
	    $pages->pageSize = empty($numPerPage) ? 20 : $numPerPage;
	    $pages->applyLimit($criteria);
	    $shootbaselist = $shootbase->findAll($criteria);
		$this->render('index',array('shootbaselist'=>$shootbaselist,'pages'=>$pages,'params'=>$params));
	}
	public function actionEdit($id = NULL)
	{//公告添加修改
	    $shootbase = new ShootBase;

	    if(isset($_POST['Form'])){
	        if(!empty($_POST['Form']['id'])){
	            $shootbase = $shootbase->findByPk($_POST['Form']['id']);
	            $message = '拍摄基地修改成功';
	        }else{
	            $message = '拍摄基地添加成功';
	        }
	        $shootbase->attributes = $_POST['Form'];
	        if ($shootbase->save())
	            $this->success($message, array('navTabId'=>'shootbase-index'));
	        else
	        {
	            $error = array_shift($shootbase->getErrors());
	            $this->error('错误：'.$error[0]);
	        }
	    }
		if($id !== NULL){
	        $shootbase = $shootbase->findByPk($id);
	    }
	    $this->render('edit',array('shootbase'=>$shootbase));
	}

	public function actionDel(array $id = array())
	{//删除公告
	    if(empty($id)){
	        $this->error('参数传递错误');
	    }
	    $id = implode(',', $id);
	    $sql = "SELECT id FROM {{shoot_base}} WHERE id IN ($id)";
	    $command = Yii::app()->db->createCommand($sql);
	    $shootbaseList = $command->queryAll();
	    $shootbase_id = '';
	    foreach ($shootbaseList as $key=>$val)
	    {//验证是否存在
	        if ($key > 0) $shootbase_id .= ',';
	        $shootbase_id .= $val['id'];
	    }
	    if (!empty($shootbase_id))
	    {
	        $sql = "DELETE FROM {{shoot_base}} WHERE id IN ($shootbase_id)";
	        $command = Yii::app()->db->createCommand($sql);
	        $command->execute();
	        $this->success('删除成功', array('navTabId'=>'shootbase-index'));
	    }
	}
}