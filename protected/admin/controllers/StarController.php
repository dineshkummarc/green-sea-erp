<?php

class StarController extends Controller
{
	public function actionIndex(array $params = array(),$pageNum = null,$numPerPage = null)
	{
	    $star = new Star;
	    $criteria = new CDbCriteria(array('select'=>'*',));
	    if (!empty($params['start_time']) && !empty($params['end_time']))
	    {
	        $stare_time = strtotime($params['start_time']);
	        echo $stare_time;
	        $end_time = strtotime($params['end_time']) + 24 * 3600;
	        $criteria->addCondition('time >= '.$stare_time.' and time < '.$end_time);
	    }
	    elseif (!empty($params['start_time']))
	    {
	        $stare_time = strtotime($params['start_time']);
	        $criteria->addCondition('time >= '.$stare_time);
	    }
	    elseif (!empty($params['end_time']))
	    {
	        $end_time = strtotime($params['end_time']) + 24 * 3600;
	        $criteria->addCondition('time < '.$end_time);
	    }
	    $count = $star->count($criteria);
	    $pages = new CPagination($count);
	    $pages->currentPage = empty($pageNum) ? 0 : $pageNum - 1;
	    $pages->pageSize = empty($numPerPage) ? 20 : $numPerPage;
	    $pages->applyLimit($criteria);
	    $starList = $star->with('Admin')->findAll($criteria);
		$this->render('index',array('starList'=>$starList,'pages'=>$pages,'params'=>$params));
	}
	public function actionEdit($id = NULL){
	    $star = new Star;
	    if(isset($_POST['Form'])){
	        if(!empty($_POST['Form']['id'])){
	            $star = $star->findByPk($_POST['Form']['id']);
	            $message = "修改明星成功";
	        }else{
	            $star->admin_id = Yii::app()->user->id;
	            $message = "添加明星成功";
	        }
	        $star->attributes = $_POST['Form'];
	        $star->time = strtotime($_POST['Form']['time']);
	        if ($star->save())
	            $this->success($message, array('navTabId'=>'star-index'));
	        else
	        {
	            $error = array_shift($star->getErrors());
	            $this->error('错误：'.$error[0]);
	        }
	    }
	    if($id !== NULL){
	        $star = $star->findByPk($id);
	    }
	    $this->render('edit',array('star'=>$star));
	}
	public function actionDel(array $id = array()){//删除公告
	    if(empty($id)){
	        $this->error('参数传递错误');
	    }
	    $id = implode(',', $id);
	    $sql = "SELECT id FROM {{star}} WHERE id IN ($id)";
	    $command = Yii::app()->db->createCommand($sql);
	    $starList = $command->queryAll();
	    $star_id = '';
	    foreach ($starList as $key=>$val)
	    {//验证是否存在
	        if ($key > 0) $star_id .= ',';
	        $star_id .= $val['id'];
	    }
	    if (!empty($star_id))
	    {
	        $sql = "DELETE FROM {{star}} WHERE id IN ($star_id)";
	        $command = Yii::app()->db->createCommand($sql);
	        $command->execute();
	        $this->success('删除成功', array('navTabId'=>'star-index'));
	    }
	}
}