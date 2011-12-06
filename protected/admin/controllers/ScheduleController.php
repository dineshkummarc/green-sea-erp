<?php
class ScheduleController extends Controller
{
	//首页控制器
	public function actionIndex(array $params = array(), $orderId = null, $pageNum = null, $numPerPage = null)
	{
		$typeList = null;
		$models = new Schedule();
		if(!empty($orderId)){
			$models = $models->findAllByAttributes(array('order_id'=>$orderId));
			$pages = null;
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
			if(!empty($params['sn'])){
				$sql = "SELECT id FROM {{order}} WHERE sn = :sn";
				$command = Yii::app()->db->createCommand($sql);
				$id = $command->queryScalar(array(':sn'=>$params['sn']));
				if ($id != false)
					$criteria -> addCondition('order_id = '.$id);
			}
		    if(!empty($params['shoot'])){
				$sql = "SELECT id FROM {{admin}} WHERE name = :name";
				$command = Yii::app()->db->createCommand($sql);
				$id = $command->queryScalar(array(':name'=>$params['shoot']));
				if ($id != false)
					$criteria -> addCondition('shoot_id = '.$id);
			}
		    if(!empty($params['stylist'])){
				$sql = "SELECT id FROM {{admin}} WHERE name = :name";
				$command = Yii::app()->db->createCommand($sql);
				$id = $command->queryScalar(array(':name'=>$params['stylist']));
				if ($id != false)
					$criteria -> addCondition('stylist_id = '.$id);
			}
			if(!empty($params['user_name'])){
				$sql = "SELECT id FROM {{order}} WHERE user_name = :name";
				$command = Yii::app()->db->createCommand($sql);
				$id = $command->queryScalar(array(':name'=>$params['user_name']));
				if ($id != false)
					$criteria -> addCondition('order_id = '.$id);
			}

			$count = $models->count($criteria);

			$pages = new CPagination($count);
	        $pages->currentPage = $pageNum !== null ? $pageNum - 1 : 0;
	        $pages->pageSize = $numPerPage !== null ? $numPerPage : 20;
	        $pages->applyLimit($criteria);
	        $pages->params = $params;

			$models = $models->findAll($criteria);
			//得到拍摄类型 且 数组反向
			$typeList = ShootType::getType();
		}

		$this->render('index',array(
			'orderId' =>$orderId,
		    'params' => $params,
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

	//根据订单ID查询分类信息
	public function getStorage($orderId = null)
	{
		// 根据订单ID查询拍摄类型
        $sql = "SELECT in_time FROM {{storage}} WHERE order_id =:Id";
		$command = Yii::app()->db->createCommand($sql);
		$storage = $command->queryScalar(array(':Id'=>$orderId));
		return $storage;
	}

	//未排程订单
	public function actionWait($status = null, $pageNum = null, $numPerPage = null)
	{

		$orders = new Order();

		$criteria = new CDbCriteria;
		if(!empty($status))
			$criteria -> condition =" status = ".$status;
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
		$shootList = Admin::getAdmin(6);
		$styleList = Admin::getAdmin(7);

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

	/**排程导出**/
	public function actionExcel($id = null)
	{
		$chinese = new Chinese;

		$phpExcelPath = Yii::getPathOfAlias('application.components');
		spl_autoload_unregister(array('YiiBase','autoload'));
		include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');

		$objPHPExcel = new PHPExcel();
		$objActSheet = $objPHPExcel->getActiveSheet(0);
		//标题样式
		$objStyle = $objActSheet->getStyle('A1');
		$objStyle->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

		$objActSheet->setCellValue('A1','绿浪视觉排程列表');
		$obj = $objActSheet->getStyle('A1');
		$objStyle = $obj->getAlignment();
		$objStyle->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objStyle->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		//设置字体
		$objFont = $obj->getFont();
		$objFont->setSize(20);
		$objFont->setBold(true);
		$objFont->getColor()->setARGB('000000');
		//设置边框
		$objBorder = $obj->getBorders();
		$objBorder->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objBorder->getTop()->getColor()->setARGB('000000'); // color
		$objBorder->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objBorder->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objBorder->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		$objActSheet->setCellValue('A2','序号')
		    ->setCellValue('B2','订单号')
		    ->setCellValue('C2','客户名')
		    ->setCellValue('D2','拍摄时间')
		    ->setCellValue('E2','拍摄类型')
		    ->setCellValue('F2','摄影师')
		    ->setCellValue('G2','拍摄模特')
		    ->setCellValue('H2','造型师')
		    ->setCellValue('I2','说明');
		$i = 3;
		if ($id == 'all'){
		    $sql = "SELECT * FROM {{schedule}} ";
            $results = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($results as $data ){
    			$order = isset($data['order_id']) ? $this->getOrder($data['order_id']) : '';
    			$shoot = isset($data['shoot_id']) ? Admin::getAdminName($data['shoot_id']) : '';
    			$stylist = isset($data['stylist_id']) ? Admin::getAdminName($data['stylist_id']) : '';
    			$model = isset($data['model_id']) ? Models::getModelName($data['model_id']) : '';
    			$shoot = !empty($shoot) ? $shoot: '';
    			$stylist = !empty($stylist) ? $stylist : '';
    			$model = !empty($model) ? $model : '';
    			$objActSheet
    				->setCellValue('A'.$i, $i-2)
    				->setCellValue('B'.$i, $order['sn'])
    				->setCellValue('C'.$i, $order['user_name'])
    				->setCellValue('D'.$i, date("Y-m-d H:i",$data['shoot_time']))
    				->setCellValue('E'.$i, $data['shoot_site'])
    				->setCellValue('F'.$i, $shoot)
    				->setCellValue('G'.$i, $model)
    				->setCellValue('H'.$i, $stylist)
    				->setCellValue('I'.$i, $data['memo']);
    			$i += 1;
            }
		}else{
    		$id = explode(',', $id);
    		foreach ($id as $key=>$id)
    		{
    			$sql = "SELECT * FROM {{schedule}} WHERE id = :id";
                $data = Yii::app()->db->createCommand($sql)->queryRow(true,array(":id"=>$id));
    			$order = isset($data['order_id']) ? $this->getOrder($data['order_id']) : '';
    			$shoot = isset($data['shoot_id']) ? Admin::getAdminName($data['shoot_id']) : '';
    			$stylist = isset($data['stylist_id']) ? Admin::getAdminName($data['stylist_id']) : '';
    			$model = isset($data['model_id']) ? Models::getModelName($data['model_id']) : '';
    			$shoot = !empty($shoot) ? $shoot: '';
    			$stylist = !empty($stylist) ? $stylist : '';
    			$model = !empty($model) ? $model : '';
    			$objActSheet
    				->setCellValue('A'.$i, $i-2)
    				->setCellValue('B'.$i, $order['sn'])
    				->setCellValue('C'.$i, $order['user_name'])
    				->setCellValue('D'.$i, date("Y-m-d H:i",$data['shoot_time']))
    				->setCellValue('E'.$i, $data['shoot_site'])
    				->setCellValue('F'.$i, $shoot)
    				->setCellValue('G'.$i, $model)
    				->setCellValue('H'.$i, $stylist)
    				->setCellValue('I'.$i, $data['memo']);
    			$i += 1;
    		}
		}

		// Excel打开后显示的工作表
		$objPHPExcel->setActiveSheetIndex(0);
		//通浏览器输出Excel报表
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment;filename='.$chinese->convert("UTF-8", "gb2312","绿浪视觉-排程导出表.xls"));
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

		//恢复Yii自动加载功能
		spl_autoload_register(array('YiiBase','autoload'));
		Yii::app()->end();
	}
	/**
	 * 获取订单信息
	 */
	public function getOrder($id = null, $params = null)
	{
		if(!empty($params))
			$sql = $params;
		if(!empty($id)){
		    $sql = "SELECT id, sn, user_name FROM {{order}} WHERE id = :id";
		    $results = Yii::app()->db->createCommand($sql)->queryRow(true, array(":id"=>$id));
		}
		else{
		    $sql = "SELECT id, sn, user_name FROM {{order}}";
		    $results = Yii::app()->db->createCommand($sql)->query();
		}
		if ($results === false)
		{
			return false;
		}
		else{
			return $results;
		}
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