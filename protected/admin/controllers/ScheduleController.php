<?php
class ScheduleController extends Controller
{
	//首页控制器
	public function actionIndex(array $params = array(), $orderId = null, $pageNum = null, $numPerPage = null)
	{
		$pages = null;
		$typeList = null;
		$models = new Schedule();
		if(!empty($orderId)){
			$models = $models->findAllByAttributes(array('order_id'=>$orderId));
		}else {
			$criteria = new CDbCriteria;
			if (!empty($params['start_time']) && !empty($params['end_time']))
			{
				$stare_time = strtotime($params['start_time']);
				$end_time = strtotime($params['end_time']) + 24 * 3600;
				$criteria->addCondition('shoot_time >= '.$stare_time.' and shoot_time < '.$end_time);
			}elseif (!empty($params['start_time']))
			{
				$stare_time = strtotime($params['start_time']);
				$criteria->addCondition('shoot_time >= '.$stare_time);
			}elseif (!empty($params['end_time']))
			{
				$end_time = strtotime($params['end_time']) + 24 * 3600;
				$criteria->addCondition('shoot_time < '.$end_time);
			}
			if(!empty($params['shoot_type']))
				$criteria -> addCondition('shoot_type = '.$params['shoot_type']);

			$count = $models->count($criteria);

			$pages = new CPagination($count);
	        $pages->currentPage = $pageNum !== null ? $pageNum - 1 : 0;
	        $pages->pageSize = $numPerPage !== null ? $numPerPage : 20;
	        $pages->applyLimit($criteria);

			$models = $models->findAll($criteria);
			//得到拍摄类型 且 数组反向
			$typeList = $this->getType();
		}

//        Dumper::dump($orders);
//        Yii::app()->end();
		$this->render('index',array(
			'orderId' =>$orderId,
			'typeList' => $typeList,
			'models' => $models,
			'pages' => $pages
		));
	}
	//根据订单ID查询分类信息
	public function getShootType($orderId = null)
	{
		// 根据订单ID查询拍摄类型
        $sql = "SELECT shoot_type FROM {{order_goods}} WHERE order_id =:Id GROUP BY shoot_type";
		$command = Yii::app()->db->createCommand($sql);
		$shootType = $command->queryAll(true, array(':Id'=>$orderId));
		return $shootType;
	}

	//未排程订单
	public function actionOrder($status = null, $pageNum = null, $numPerPage = null)
	{

		$orders = new Order();

		$criteria = new CDbCriteria;
//		if(!empty($status))
			$criteria -> condition =" status < 4 ";
		$count = $orders->count($criteria);

		$pages = new CPagination($count);
        $pages->currentPage = $pageNum !== null ? $pageNum - 1 : 0;
        $pages->pageSize = $numPerPage !== null ? $numPerPage : 20;
        $pages->applyLimit($criteria);

        $orders = $orders->findAll($criteria);

        $this->render('wait',array(
			'orders' =>$orders,
			'pages' => $pages
		));
	}
	//编辑排程
	public function actionEdit($id = null, $orderId = null)
	{
		$model = new Schedule();
        if ($id !== null)
            $model = $model->findByPk($id);

        if (isset($_POST['Form']))
        {
            $id = $_POST['Form']['id'];
            if (!empty($id))
            {
                $model = $model->findByPk($id);
                $message = "修改成功";
            }
            else
                $message = "添加成功";

            $model->attributes = $_POST['Form'];

            // 格式化时间
            $model->shoot_time = strtotime($_POST['Form']['shoot_time']);
//            $model->shoot_id = !empty($_POST['Form']['shoot_id']) ? $_POST['Form']['shoot_id'] : 0;
//            $model->stylist_id = !empty($_POST['Form']['stylist_id']) ? $_POST['Form']['stylist_id'] : 0;
//            $model->model_id = !empty($_POST['Form']['model_id']) ? $_POST['Form']['model_id'] : 0;
            if ($model->save())
            {
            	$sql = "UPDATE {{order}} SET status = 4 WHERE id = :id";
	            $command = Yii::app()->db->createCommand($sql);
	            $count = $command->execute(array(':id'=> $orderId));
                $this->success($message,array('navTabId'=>'schedule-index') );
            }
            else
            {
                $this->error(Dumper::dumpString($model->getErrors()));
            }
        }
        if (!empty($orderId))
        	$model->order_id = $orderId;
        $orders = $this->getOrder($orderId);

        // 根据订单ID查询拍摄类型
        $sql = "SELECT shoot_type FROM {{order_goods}} WHERE order_id =:Id GROUP BY shoot_type";
		$command = Yii::app()->db->createCommand($sql);
		$typeList = $command->queryAll(true, array(':Id'=>$orderId));
//		$result = array();
//		foreach ($typeList as $List){
//			$result[] = $List['shoot_type'];
//		}
//		$model->shoot_type = !empty($result['0']) ? $result['0'] : 1;
//		$sql = "SELECT * FROM {{shoot_type}}";
//		$command = Yii::app()->db->createCommand($sql);
//		$typeList = $command->queryAll();

		// 根据订单ID查询拍摄模特
        $sql = "SELECT model_id FROM {{order_model}} WHERE order_id =:Id GROUP BY model_id";
		$command = Yii::app()->db->createCommand($sql);
		$modelList = $command->queryAll(true, array(':Id'=>$orderId));
		$array = array();
		foreach ($modelList as $List){
			$array[] = $List['model_id'];
		}
		$model->model_id = !empty($array['0']) ? $array['0'] : 0 ;

		$sql = "SELECT id,nick_name FROM {{models}}";
		$command = Yii::app()->db->createCommand($sql);
		$modelList = $command->queryAll();

		if($modelList === false)
			$modelList[] = Models::model()->cache()->findAll();

		//得到摄影师列表和造型师列表
		$shootList = $this->getAdmin(null, 6);
		$styleList = $this->getAdmin(null, 8);

        $this->render('edit', array(
            'orders'=>$orders,
        	'model'=>$model,
        	'shootList'=>$shootList,
        	'styleList'=>$styleList,
        	'typeList'=>$typeList,
        	'modelList'=>$modelList
        ));
	}

	//删除排程
	public function actionDel(array $id =array())
	{
		if (empty($id))
            $this->error('参数传递错误！');

        $sqlIn = implode(',', $id);

        $sql = "DELETE FROM {{schedule}} WHERE id in ($sqlIn)";
        $command = Yii::app()->db->createCommand($sql);
        $command->execute();
        $this->success('删除成功',array('navTabId'=>'schedule-index'));
	}

	/**
	 * 获取订单信息
	 */
	public function getOrder($id = null, $params = null)
	{
		if(!empty($params))
			$sql = $params;
		if(!empty($id))
			$sql = "SELECT id, sn, user_name FROM {{order}} WHERE id =".$id;
		else
			$sql = "SELECT id, sn, user_name FROM {{order}}";
		$command = Yii::app()->db->createCommand($sql);
		$types = $command->queryAll();
		if ($types === false)
		{
			return false;
		}
		else{
			return $types;
		}
	}

	/**
	 * 获取模特
	 */
	public function getModel($id = null)
	{
		if(!empty($id))
			$sql = "SELECT id, nick_name FROM {{models}} WHERE id =".$id;
		else
			$sql = "SELECT id, nick_name FROM {{models}}";
		$command = Yii::app()->db->createCommand($sql);
		$models = $command->queryAll();
        return $models;
	}

	/**
	 * 获取拍摄类型
	 */
	public function getType($id = null)
	{
		if(!empty($id))
			$sql = "SELECT * FROM {{shoot_type}} WHERE id =".$id;
		else
			$sql = "SELECT * FROM {{shoot_type}}";
		$command = Yii::app()->db->createCommand($sql);
		$types = $command->queryAll();
		if ($types === false)
		{
			return false;
		}
		else{
			return $types;
		}
	}
	/**
	 * 获取用户组
	 */
	public function getAdmin($id = null, $roleId = null)
	{
		if(!empty($id))
			$sql = "SELECT id, name FROM {{admin}} WHERE id = ".$id;
		if(!empty($roleId))
			$sql = "SELECT id, name FROM {{admin}} WHERE role_id = ".$roleId;
		if(empty($id) && empty($roleId)){
			$admins = false;
		}else {
			$command = Yii::app()->db->createCommand($sql);
			$admins = $command->queryAll();
		}
	    return $admins;
	}

	/**
	 * 数组反向
	 */
	public function arrayReverse($list)
	{
		$str = array();
		foreach($list as $key=>$name)
		{
			$str[$name]=$key;
		}
		return $str;
	}
}