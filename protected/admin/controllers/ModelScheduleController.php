<?php

class ModelScheduleController extends Controller{
    public function actionIndex(array $params = array(),$pageNum = null,$numPerPage = null)
	{//模特档期显示
	    $modelschedule = new ModelSchedule;
	    $criteria = new CDbCriteria(array('select'=>'*',));
        if($modelschedule->with('Model')->count($criteria) > 0){
	        $modelscheduleList = $modelschedule->with('Model')->findAll($criteria);
	        foreach($modelscheduleList as $modelscheduleRow){
	            $nick_names[$modelscheduleRow->Model->nick_name] = $modelscheduleRow->model_id;
	            if(!empty($_GET['model_id'])){
	                if($_GET['model_id'] == $modelscheduleRow->model_id){
	                    $params['model_id'] = $_GET['model_id'];
	                    $model_id_cd = true;
	                }
	            }
	        }
        }else{
            $nick_names = array('默认'=>'');
        }
	    if(!empty($params['model_id']))
	        $criteria->addSearchCondition('model_id', $params['model_id']);
        if(isset($_GET['scheduled'])){
            $params['scheduled'] = $_GET['scheduled'];
        }
        if(!empty($params['scheduled']))
                $criteria->addSearchCondition('scheduled',$params['scheduled']);

	    $count = $modelschedule->with('Model')->count($criteria);
	    $pages = new CPagination($count);
	    $pages->currentPage = empty($pageNum) ? 0 : $pageNum - 1;
	    $pages->pageSize = empty($numPerPage) ? 20 : $numPerPage;
	    $pages->applyLimit($criteria);

	    $modelscheduleList = $modelschedule->with('Model')->findAll($criteria);
	    if(!empty($_GET['model_id'])){
	        if(empty($model_id_cd)){$modelscheduleList = NULL;}
	    }
	    $sql = "DELETE FROM {{model_schedule}} WHERE date < CURDATE()";
	    $command = Yii::app()->db->createCommand($sql);
	    $command->execute();

		$this->render('index',array('modelscheduleList'=>$modelscheduleList,'pages'=>$pages,'nick_names'=>$nick_names,'params'=>$params));
	}
	public function actionEdit($date = NULL,$model_id = NULL)
	{//公告添加修改
	    $modelschedule = new ModelSchedule;
	    if(isset($_POST['Form'])){
	        if(!empty($_POST['Form']['date']) and !empty($_POST['Form']['model_id'])){
	            $criteria = new CDbCriteria(array(
	                    'select' => '*',
	                    'condition'=>'date = :date AND model_id = :id',
	                    'params'=>array(':date'=>$_POST['Form']['date'], ':id'=>$_POST['Form']['model_id'])
	            ));
	            $modelscheduleList = $modelschedule->find($criteria);
	            if(!empty($modelscheduleList)){
	                if($_POST['Form']['update'] == 1){
	                    $this->error('当日已有档期，如有必要可以进行修改，添加操作失败！');
	                }else{
	                    $modelschedule = $modelscheduleList;
	                    $modelschedule->attributes = $_POST['Form'];
	                    $message = '档期修改成功';
	                }
	            }else{
	                $message = '档期添加成功';
	                $modelschedule->attributes = $_POST['Form'];
	            }
	            if ($modelschedule->save())
	                $this->success($message, array('navTabId'=>'modelschedule-index'));
	            else
	            {
	                $error = array_shift($modelschedule->getErrors());
	                $this->error('错误：'.$error[0]);
	            }
	        }
	    }

	    $models = new Models;
	    $criteriam = new CDbCriteria(array('select'=>'id,nick_name',));
	    $modelsList = $models->findAll($criteriam);

	    if($date !== NULL){
	        $criteriams = new CDbCriteria(array(
	                'select' => '*',
	                'condition'=>'date = :date AND model_id = :id',
	                'params'=>array(':date'=>$date, ':id'=>$model_id)
	        ));
	        $modelscheduleRow = $modelschedule->find($criteriams);
	    }

	    $this->render('edit',array('modelsList'=>$modelsList,'modelscheduleRow'=>$modelscheduleRow));
	}
}