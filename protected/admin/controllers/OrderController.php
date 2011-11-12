<?php
class OrderController extends Controller
{
	/**
	 * 订单列表
	 * @param unknown_type $pageNum
	 * @param unknown_type $numPerPage
	 */
	public function actionIndex(array $params = array(), $sort = null, $pageNum = 1, $numPerPage = 20)
	{
		$criteria = new CDbCriteria;
		if (!empty($params['logistics_sn']))
		{
			$criteria->addSearchCondition('logistics_sn', $params['logistics_sn']);
		}
		if (!empty($params['start_time']) && !empty($params['end_time']))
		{
			$stare_time = strtotime($params['start_time']);
			$end_time = strtotime($params['end_time']) + 24 * 3600;
			$criteria->addCondition('create_time >= '.$stare_time.' and create_time < '.$end_time);
		}elseif (!empty($params['start_time']))
		{
			$stare_time = strtotime($params['start_time']);
			$criteria->addCondition('create_time >= '.$stare_time);
		}elseif (!empty($params['end_time']))
		{
			$end_time = strtotime($params['end_time']) + 24 * 3600;
			$criteria->addCondition('create_time < '.$end_time);
		}
	    if (!empty($params['sn']))
            $criteria->addSearchCondition('sn', $params['sn']);
        if (!empty($params['user_name']))
            $criteria->addSearchCondition('user_name', $params['user_name']);
        if (!empty($params['status']) && $params['status'] > 0)
        {
        	$criteria->addCondition('status = '.$params['status']);
        }
        //计算金额
		$orders = Order::model()->cache()->findAll($criteria);
		if ($orders != null)
		{
	        $sql = "SELECT SUM( `total_price` ) FROM {{order}} WHERE id in (".$this->orderId($orders).")";
	        $command = Yii::app()->db->createCommand($sql);
	        $money = $command->queryScalar();
		}else {
			$money = 0;
		}

		$shootStatus = $this->arrayReverse(Order::getShootStatus());//状态信息
		$count = Order::model()->cache()->count($criteria);
        $pages = new CPagination($count);
        $pages->currentPage = $pageNum - 1;
        $pages->pageSize = $numPerPage;
        $pages->applyLimit($criteria);

        if ($sort == 'time')$criteria->order = 'create_time DESC';
		else if ($sort == 'status')$criteria->order = 'status ASC';
		else $criteria->order = "status asc, create_time desc";

		$orders = Order::model()->cache()->findAll($criteria);

		$this->render('index',array(
			'money' => $money,
			'shootStatus' => $shootStatus,
			'params' => $params,
			'pages' => $pages,
			'orders' => $orders
		));
	}
	/**
	 * 得到order id字符串
	 */
	public function orderId($list)
	{
		$str = '';
		foreach ($list as $key=>$order)
		{
			if ($key > 0) $str .= ',';
			$str .= $order->id;
		}
		return $str;
	}
	/**
	 * $shootStatus数组反向
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
	/**
	 * 订单 删除
	 */
    public function actionOrderDel($id = null)
    {
    	if ($id === null)
	    {
	        $this->error("删除失败，发生错误");
	    }
	    //删除订单表
	    $sql = "DELETE FROM {{order}} WHERE id = ".$id;
        $command = Yii::app()->db->createCommand($sql);
        $count = $command->execute();
        //删除订单模特
        $sql = "DELETE FROM {{order_model}} WHERE order_id = ".$id;
        $command = Yii::app()->db->createCommand($sql);
        $count = $command->execute();
        //订单物品
        $sql = "DELETE FROM {{order_goods}} WHERE order_id = ".$id;
        $command = Yii::app()->db->createCommand($sql);
        $count = $command->execute();
        //删除订单跟进表
        $sql = "DELETE FROM {{order_track}} WHERE order_id = ".$id;
        $command = Yii::app()->db->createCommand($sql);
        $count = $command->execute();
        //订单排程
        $sql = "DELETE FROM {{schedule}} WHERE order_id = ".$id;
        $command = Yii::app()->db->createCommand($sql);
        $count = $command->execute();
        //订单仓储
        $sql = "DELETE FROM {{storage}} WHERE order_id = ".$id;
        $command = Yii::app()->db->createCommand($sql);
        $count = $command->execute();
        //仓储物品
        $sql = "DELETE FROM {{storage_goods}} WHERE storage_id = (SELECT id FROM {{storage}} WHERE order_id =".$id.") ";
        $command = Yii::app()->db->createCommand($sql);
        $count = $command->execute();

        $this->success('删除成功', array('navTabId'=>'order-index'));
    }
    /**
     * 切换订单状态
     * @param integer $id
     * @param integer $status
     */
    public function actionOrderStatus($id = null, $status = null)
    {
        if (empty($id))
            $this->error("参数传递错误");
//      return array(
//		    1=>"未付款",
//		    2=>"已付款、未收货",
//		    3=>"已付款、已收货、待排程",
//		    4=>"已付款、已收货、已排程",
//		    5=>"拍摄中",
//		    6=>"拍摄完成、待修图",
//		    7=>"修图中",
//		    8=>"修图完成、待上传",
//		    9=>"可下载",
//		    10=>"货物待寄出",
//		    11=>"货物已寄出",
//		    12=>"确认收货",
//		);
		$order_track_sql = null;//订单追踪sql
		//查找user_id
		$sql = "select user_id FROM {{order}} WHERE id = :id";
		$command = Yii::app()->db->createCommand($sql);
		$user_id = $command->queryScalar(array(':id'=>$id));
		//查找user admin_id
		$sql = "select admin_id FROM {{user}} WHERE id = :id";
		$command = Yii::app()->db->createCommand($sql);
		$admin_id = $command->queryScalar(array(':id'=>$user_id));

		if ($status > 2)//必须要仓储
		{
			$storage = Storage::getStorageBoolean($id);
			if (empty($storage)) $this->error("[操作失败]仓储不存在，请创建仓储后再操作");
		}
	    if ($status == 2)//积分修改
	    {
//	        // 消费金额等积分
//	        $sql = "SELECT total_price FROM {{order}} WHERE id = $id";
//	        $data = Yii::app()->db->createCommand($sql)->query();
//            $data->bindColumn(1, $price);
//            if ($data->read() !== false) User::addScore((int)$price);
//
//            // 新客户送积分和累积消费积分
//            $sql = "SELECT first, accumulation_price FROM {{user}} WHERE id = " . Yii::app()->user->id;
//            $data = Yii::app()->db->createCommand($sql)->query();
//            $data->bindColumn(1, $first);
//            $data->bindColumn(2, $price);
//            if ($data->read() !== false)
//            {
//                // 如果为首次下单
//                if ($first == 1)
//                {
//                    User::addScore(1500, "首次下单积分奖励");
//                    // 清楚首次下单状态，防止重复奖励
//                    $sql = "UPDATE  {{user}} SET first = 0, update_time = :update_time WHERE id = " . Yii::app()->user->id;
//                    $command = Yii::app()->db->createCommand($sql);
//                    $command->bindValue(":update_time", Yii::app()->params['timestamp'], PDO::PARAM_INT);
//                    $command->execute();
//                }
//                // 如果累积消费大于等于5000，则额外赠送3000积分
//                if ((int)$price >= 5000)
//                {
//                    User::addScore(3000, "累积消费额外积分奖励");
//                    // 减去累积消费5000，防止重复奖励
//                    $sql = "UPDATE {{user}} SET accumulation_price = :price , update_time = :update_time WHERE id = " . Yii::app()->user->id;
//                    $command = Yii::app()->db->createCommand($sql);
//                    $command->bindValue(":price", strval($price - 5000), PDO::PARAM_STR);
//                    $command->bindValue(":update_time", Yii::app()->params['timestamp'], PDO::PARAM_INT);
//                    $command->execute();
//                }
//            }
	    	$sql = "UPDATE {{order}} SET status = :status, receive_time = '".Yii::app()->params['timestamp']."' WHERE id = :id";
	    }
	    elseif ($status == 5)//拍摄中
        {
        	$order_track_sql = "UPDATE {{order_track}} SET photographer_id = :user_id WHERE id = :id";
        	$sql = "UPDATE {{order}} SET status = :status, shoot_begin_time = '".Yii::app()->params['timestamp']."' WHERE id = :id";
        }
        elseif ($status == 6)//拍摄完成
        {
        	$order_track_sql = "UPDATE {{order_track}} SET photographer_id_2 = :user_id WHERE id = :id";
        	$sql = "UPDATE {{order}} SET status = :status, shoot_end_time = '".Yii::app()->params['timestamp']."' WHERE id = :id";
        }
        elseif ($status == 7)//修图中
        {
        	$order_track_sql = "UPDATE {{order_track}} SET retouch_id = :user_id WHERE id = :id";
        	$sql = "UPDATE {{order}} SET status = :status, retouch_begin_time = '".Yii::app()->params['timestamp']."' WHERE id = :id";
        }
        elseif ($status == 8)//修图完成
        {
        	$order_track_sql = "UPDATE {{order_track}} SET retouch_id_2 = :user_id WHERE id = :id";
        	$sql = "UPDATE {{order}} SET status = :status, retouch_end_time = '".Yii::app()->params['timestamp']."' WHERE id = :id";
        }
        elseif ($status == 9)//可下载
        {
        	$order_track_sql = "UPDATE {{order_track}} SET deliver_id = :user_id WHERE id = :id";
        	$sql = "UPDATE {{order}} SET status = :status WHERE id = :id";
		}
        else{
        	$sql = "UPDATE {{order}} SET status = :status WHERE id = :id";
        }
        if ($order_track_sql != null)
        {
        	//添加订单追踪信息
        	$order_track_id = OrderTrack::getOrderTrackId($id,$admin_id);
        	$command = Yii::app()->db->createCommand($order_track_sql);
        	$command->execute(array(':user_id'=>Yii::app()->user->id,':id'=>$order_track_id));
        }else{
        	OrderTrack::getOrderTrackId($id,$admin_id);
        }
        $command = Yii::app()->db->createCommand($sql);
        $command->execute(array(":id"=>$id, ":status"=>$status));

        $this->success("修改成功", array('navTabId'=>'order-index'));
    }
	/**
	 * 订单物品
	 */
	public function actionGoods($id, $pageNum = 1, $numPerPage = 20)
	{
		$criteria = new CDbCriteria;
		$criteria->condition='order_id='.$id;

		$count = OrderGoods::model()->count($criteria);
        $pages = new CPagination($count);
        $pages->currentPage = $pageNum - 1;
        $pages->pageSize = $numPerPage;
        $pages->applyLimit($criteria);
        $pages->params = array('id'=>$id);

		$orderGoodsList = OrderGoods::model()->findAll($criteria);
		$this->render('goods',array(
			'orderId'=>$id,
			'pages' => $pages,
			'orderGoodsList' => $orderGoodsList
		));
	}
	/**
	 * 订单物品 修改
	 */
	public function actionGoodsEdit($id = null,$orderId = null)
	{
		$orderGoods = new OrderGoods;

		if (!empty($id))
			$orderGoods = $orderGoods->model()->findByPk($id);

		if (isset($_POST['Form']))
        {
            if (!empty($_POST['Form']['id']))
            {
                $message = '修改成功';
                $orderGoods = $orderGoods->findByPk($_POST['Form']['id']);
            }
            else
            {
                $message = '添加成功';
            }
            $orderGoods->attributes = $_POST['Form'];
			if ($_POST['Form']['type'] != 0)
			{
            	$sql = "SELECT name FROM {{goods_type}} WHERE id = :id";
        		$command = Yii::app()->db->createCommand($sql);
        		$orderGoods->type_name = $command->queryScalar(array(':id'=>$_POST['Form']['type']));
			}

            if ($orderGoods->save())
                $this->success($message, array('navTabId'=>'order-goods'));
            else
            {
                $error = array_shift($orderGoods->getErrors());
                $message = '错误：'.$error[0];
                $this->error($message);
            }
        }
        $styles = Style::model()->findAll();
        $shootTypes = ShootType::model()->findAll();
        $types = GoodsType::model()->findAll();
		$this->render('goods_edit',array(
			'orderId'=>$orderId,
			'styles' => $styles,
			'shootTypes' => $shootTypes,
			'types' => $types,
			'orderGoods' => $orderGoods
		));
	}

	/**
	 * 订单需求 修改
	 */
	public function actionShootScene($id = null)
	{
		$order = new Order;
		$sn = null;
		$sn_name = null;

		if (!empty($id))
		{
			$order = $order->model()->findByPk($id);
			$sn = $order->logistics_sn;
			$str = explode(" ",$sn);
			if (count($str) == 1)
				$sn = $str[0];
			if (count($str) == 2)
			{
				$sn_name = $str[0];
				$sn = $str[1];
			}
		}

		if (isset($_POST['Form']))
        {
            if (!empty($_POST['Form']['id']))
            {
                $message = '修改成功';
                $order = $order->findByPk($_POST['Form']['id']);
            }
            else
            {
                $message = '添加成功';
            }
			$sn = trim($_POST['Form']['sn']);
			$sn_name = trim($_POST['Form']['sn_name']);
			$sn = trim($sn_name." ".$sn);

            $order->attributes = $_POST['Form'];
            $order->shoot_notice = serialize($_POST['Form']['shoot_notice']);
            $order->width = serialize($_POST['Form']['width']);
            $order->logistics_sn = $sn;

            if ($order->save())
                $this->success($message, array('navTabId'=>'order-index'));
            else
            {
                $error = array_shift($order->getErrors());
                $message = '错误：'.$error[0];
                $this->error($message);
            }
        }
        $shoot = unserialize($order->shoot_notice);

        $shootType = $this->loadShootType();
		$shootTypeList = unserialize($order->width);
        $shootNotice = Order::getShootNotice();
		$this->render('shoot_scene',array(
			'sn'=>$sn,
			'sn_name'=>$sn_name,
			'shoot'	=> $shoot,
			'shootType' => $shootType,
			'shootTypeList' => $shootTypeList,
			'shootNotice' => $shootNotice,
			'orders' => $order
		));
	}
	/**
	 * 返回需求 格式化类型
	 * Enter description here ...
	 */
	public function loadShootType()
	{
		$shootType = ShootType::model()->findAll();
		$list=array();
		foreach ($shootType as $type)
		{
			$list[$type->id]=$type->name;
		}
		return $list;
	}
	/**
	 * 返回需求 说明
	 * @var unknown_type
	 */
    public $_shootNotice;
	public function getShootNotice()
	{
	    if (empty($this->_shootNotice))
	        $this->_shootNotice = require_once(Yii::getPathOfAlias('application.components', true) . '\shootnotice.php');
	    return $this->_shootNotice;
	}

	/**
	 * 订单 仓储
	 */
	public function actionStorage($id, $pageNum = 1, $numPerPage = 20)
	{
		$pageSizes = array(10, 20);
        $storage = Storage::model()->find(array('condition'=>'order_id='.$id));

		$criteria = new CDbCriteria;
		$criteria->order = "sn ASC";
		if (empty($storage))
		{
			$storage = new Storage;
			$storage -> order_id = $id;
			$storage -> admin_id = Yii::app()->user->id;
			$storage -> in_time = Yii::app()->params['timestamp'];
			$storage -> out_time = 0;
			$storage -> out_sn = '';

			if ($storage->save())
			{
				//查找user_id
				$sql = "select user_id FROM {{order}} WHERE id = :id";
				$command = Yii::app()->db->createCommand($sql);
				$user_id = $command->queryScalar(array(':id'=>$id));
				//查找user admin_id
				$sql = "select admin_id FROM {{user}} WHERE id = :id";
				$command = Yii::app()->db->createCommand($sql);
				$admin_id = $command->queryScalar(array(':id'=>$user_id));

				//添加订单追踪
				$order_track_id = OrderTrack::getOrderTrackId($id,$admin_id);
				$order_track_sql = "UPDATE {{order_track}} SET storage_id = :storage_id WHERE id = :id";
        		$command = Yii::app()->db->createCommand($order_track_sql);
        		$command->execute(array(':storage_id'=>Yii::app()->user->id,':id'=>$order_track_id));
			}
		}
		$criteria->condition='storage_id = '.$storage->id;

		$count = StorageGoods::model()->count($criteria);
        $pages = new CPagination($count);
        $pages->currentPage = $pageNum - 1;
        $pages->pageSize = $numPerPage;
        $pages->applyLimit($criteria);

		$storageGoodsList = StorageGoods::model()->findAll($criteria);
		$this->render('storage',array(
			'id' => $id,
			'orderId' => $id,
			'pageSizes' => $pageSizes,
			'pages' => $pages,
			'storage' => $storage,
			'storageGoodsList' => $storageGoodsList
		));
	}
	/**
	 * 仓储 修改
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	public function actionStorageEdit($id = null)
	{
		$storage = new Storage;
		$out_sn = null;
		$sn_name = null;
		if (!empty($id))
		{
			$storage = $storage->model()->findByPk($id);
			$sn = $storage->out_sn;
			$str = explode(" ",$sn);
			if (count($str) == 1)
				$out_sn = $str[0];
			if (count($str) == 2)
			{
				$sn_name = $str[0];
				$out_sn = $str[1];
			}
		}
		if (isset($_POST['Form']))
        {
            if (!empty($_POST['Form']['storage_id']))
            {
                $message = '修改成功';
                $storage = $storage->findByPk($_POST['Form']['storage_id']);
            }
            else
            {
                $message = '添加成功';
            }
			$out_sn = trim($_POST['Form']['out_sn']);
			$sn_name = trim($_POST['Form']['sn_name']);
			$sn = trim($sn_name." ".$out_sn);

            $storage->out_time = strtotime($_POST['Form']['out_time']);
            $storage->out_sn = $sn;

            if ($storage->save())
                $this->success($message, array('navTabId'=>'order-storage'));
            else
            {
                $error = array_shift($storage->getErrors());
                $message = '错误：'.$error[0];
                $this->error($message);
            }
        }
		$this->render('storage_edit',array(
			'out_sn'=>$out_sn,
			'sn_name'=>$sn_name,
			'storage' => $storage
		));
	}
	/**
	 * 仓储 删除
	 * Enter description here ...
	 */
	public function actionStorageDel($id = null)
	{
		if ($id == null)
	    {
	        $this->error("删除失败，发生错误");
	    }
	    //删除仓储
	    Storage::model()->deleteByPk($id);
	    // 删除订单物品
        StorageGoods::model()->deleteAllByAttributes(array('storage_id'=>$id));
	    $this->success('删除成功', array('navTabId'=>'order-index'));
	}
	/**
	 * 仓储 物品
	 */
	public function actionStorageGoods($id = null, $storage_id = null, $order_sn = null, $orderId = null)
	{
		$storageGoods = new StorageGoods;
		if (!empty($id))
			$storageGoods = $storageGoods->model()->findByPk($id);
		if (!empty($orderId)){
			$sql = "SELECT shoot_type FROM {{order_goods}} WHERE order_id =:Id GROUP BY shoot_type";
			$command = Yii::app()->db->createCommand($sql);
			$shootTypes = $command->queryAll(true, array(':Id'=>$orderId));
		}
		if (isset($_POST['Form']))
        {
            if (!empty($_POST['Form']['id']))
            {
                $message = '修改成功';
                $storageGoods = $storageGoods->findByPk($_POST['Form']['id']);
                $storageGoods->attributes = $_POST['Form'];
	            if ($storageGoods->save())
	                $this->success($message, array('navTabId'=>'order-storage'));
	            else
	            {
	                $error = array_shift($storageGoods->getErrors());
	                $message = '错误：'.$error[0];
	                $this->error($message);
	            }
            }
            else
            {
                $message = '添加成功';
                $storageGoods->attributes = $_POST['Form'];
            	$count = $_POST['Form']['count'];

            	$sql = "select MAX(sn) FROM {{storage_goods}} WHERE storage_id = :storage_id";
            	$command = Yii::app()->db->createCommand($sql);
            	$max = $command->queryScalar(array(':storage_id'=>$_POST['Form']['storage_id']));

            	if ($max == null || $max == "")
            	{
            		$j = 1;
            	}else{
					$j = strrev($max);
					$j = substr($j,0,3);
					$j = (int)strrev($j) + 1;
					$count += $j - 1;
            	}
				for ($i = $j; $i <= $count; $i ++)
				{
				    $sn = substr(strval($i + 1000),1,3);
				    $sn = $_POST['Form']['order_sn'] . $sn;
					$sql = "INSERT INTO {{storage_goods}} ( storage_id, sn, name, shoot_type,is_shoot) VALUES (:val1, :val2, :val3, :val4, :val5)";
					$command = Yii::app()->db->createCommand($sql);
					$command->execute(array(
					    ":val1"=>$_POST['Form']['storage_id'],
					    ":val2"=>$sn,
					    ":val3"=>$_POST['Form']['name'],
					    ":val4"=>$_POST['Form']['shoot_type'],
					    ":val5"=>0,
					));
				}
		        $this->success($message, array('navTabId'=>'order-storage'));
            }

        }
		$this->render('storage_goods',array(
			'order_sn' => $order_sn,
			'storage_id' => $storage_id,
			'shootTypes'=>$shootTypes,
			'storageGoods'	=> $storageGoods
		));
	}
    /**
     * 仓储入库 提交
     */
    public function actionStorageOut($id = null,$out_sn,$sn_name)
    {
        if (empty($id))
            $this->error('参数传递错误');
        if (empty($out_sn))
            $this->error('订单号不能为空');
		$out_sn = trim($out_sn);
		$sn_name = trim($sn_name);
		$sn = trim($sn_name." ".$out_sn);
        $storage = Storage::model()->findByPk($id);
        $storage->out_time = Yii::app()->params['timestamp'];
		$storage->out_sn = $sn;

        if ($storage->save())
            $this->success('修改成功', array('navTabId'=>'order-storage'));
        else
        {
            $error = array_shift($storage->getErrors());
            $message = '错误：'.$error[0];
            $this->error($message);
        }
    }

	/**
	 * 仓储物品 删除
	 */
    public function actionStorageGoodsDel(array $id = array())
    {
        if (empty($id))
            $this->error('参数传递错误！');

        $sqlIn = implode(',', $id);

        $sql = "DELETE FROM {{storage_goods}} WHERE id in ($sqlIn)";
        $command = Yii::app()->db->createCommand($sql);
        $count = $command->execute();
        $this->success('删除成功', array('navTabId'=>'order-storage'));
    }

	/**
	 * 打印订单
	 * @param integer $id order id
	 */
	public function actionPrint($id = null)
	{
	    if ($id === null)
	    {
	        $this->error("参数错误");
	        $this->redirect(array("order/index"));
	    }
	    $this->layout = false;

	    // 获取数据库信息
	    $order = Order::model()->findByPk($id);
	    $goodsList = OrderGoods::model()->findAllByAttributes(array('order_id'=>$order->id));
	    $models = OrderModel::model()->findAllByAttributes(array('order_id'=>$order->id));
	    $shootType = ShootType::model()->findAll();
	    $style = Style::model()->findAll();

	    // 拆解数据对象
	    $season = array();
        $season[0] = "不限";
        $season[1] = "春秋";
        $season[2] = "夏";
        $season[3] = "冬";

        $sex = array();
        $sex[0] = "不限";
        $sex[1] = "男";
        $sex[2] = "女";
        $sex[3] = "情侣";

	    $result = array();
        foreach ($shootType as $type)
        {
            $result[$type->id] = $type->name;
        }
        $shootType = $result;

	    $result = array();
        foreach ($style as $style)
        {
            $result[$style->id] = $style->name;
        }
        $result[0] = "不限";
        $style = $result;

        $result = array();
        foreach ($models as $model)
        {
            $result[] = $model->Info;
        }
        $models = $result;

        $order->shoot_notice = unserialize($order->shoot_notice);
        $order->width = unserialize($order->width);

	    $this->render("print", array(
	        'order'=>$order,
	        'goodsList'=>$goodsList,
	    	'models'=>$models,
	        'season'=>$season,
	        'sex'=>$sex,
	        'shootType'=>$shootType,
	        'style'=>$style,
	        'models'=>$models,
            'shootNotice'=>Order::getShootNotice()
	    ));
	}

	/**
	 * 将订单 数据导出到Excel
	 */
	public function actionOrderExcel($id = null)
	{
        if (empty($id))$this->error('参数传递错误！');

        $sql = "SELECT SUM( `total_price` ) FROM {{order}} WHERE id in (".$id.")";
	    $command = Yii::app()->db->createCommand($sql);
	    $money = $command->queryScalar();

        $idList=explode(",",$id);
		$chinese = new Chinese;

		$phpExcelPath = Yii::getPathOfAlias('application.components');
		spl_autoload_unregister(array('YiiBase','autoload'));
		include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');

		$objPHPExcel = new PHPExcel();
		$objActSheet = $objPHPExcel->getActiveSheet(0);
		//标题样式
		$objStyle = $objActSheet->getStyle('A1');
		$objStyle->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

		$objActSheet->setTitle('订单');

		$objActSheet->mergeCells('A1:K1');
		$objActSheet->mergeCells('B2:D2');
		$objActSheet->mergeCells('F2:H2');

		$objActSheet->setCellValue('A1','订单汇总表');
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
		//设置填充色
//		$objFill = $obj->getFill();
//		$objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
//		$objFill->getStartColor()->setARGB('CCCCCC');

		$objActSheet->setCellValue('A2','序号');
		$objActSheet->setCellValue('B2','客户信息');
		$objActSheet->setCellValue('E2','下单时间');
		$objActSheet->setCellValue('F2','拍摄内容');
		$objActSheet->setCellValue('I2','模特');
		$objActSheet->setCellValue('J2','备注');
		$objActSheet->setCellValue('K2','金额');

		$objActSheet->setCellValue('A3','');
		$objActSheet->setCellValue('B3','订单编号');
		$objActSheet->setCellValue('C3','客户名称');
		$objActSheet->setCellValue('D3','阿里旺旺');
		$objActSheet->setCellValue('E3','');
		$objActSheet->setCellValue('F3','数量');
		$objActSheet->setCellValue('G3','性别');
		$objActSheet->setCellValue('H3','类别/类型');
		$objActSheet->setCellValue('I3','');
		$objActSheet->setCellValue('J3','');
		$objActSheet->setCellValue('K3',"￥".$money);
		//设置字体
		$objFont = $objActSheet->getStyle('K3')->getFont();
		$objFont->setBold(true);

		$objActSheet->getColumnDimension('E')->setWidth(20);
		//同样式列表
		$styleList = array(
			'A2','B2','C2','D2','E2','F2','G2','H2','I2','J2','K2',
			'A3','B3','C3','D3','E3','F3','G3','H3','I3','J3',
		);
		foreach ($styleList as $style)
		{
			//设置居中
			$obj = $objActSheet->getStyle($style);
			$objStyle = $obj->getAlignment();
			$objStyle->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objStyle->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

//			//设置填充色
//			$objFill = $obj->getFill();
//			$objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
//			$objFill->getStartColor()->setARGB('#666666');

			//设置边框
			$objBorder = $obj->getBorders();
			$objBorder->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objBorder->getTop()->getColor()->setARGB('000000'); // color
			$objBorder->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objBorder->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objBorder->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		}

		$i = 4;
		foreach ($idList as $key=>$id)
		{
			$list = '';
			$data = Order::model()->findByPk($id);
			if (!empty($data->Models))
			{
				foreach ($data->Models as $key=>$name)
				{
					if ($key > 0) $list.=',';
					$list .= $name->nick_name;
				}
			}
			$objActSheet
				->setCellValue('A'.$i, $i-3)
				->setCellValue('B'.$i, $data->sn)
				->setCellValue('C'.$i, $data->user_name)
				->setCellValue('D'.$i, !empty($data->User->wangwang)?$data->User->wangwang:'')//旺旺
				->setCellValue('E'.$i, date('Y-m-d H:i:s', $data->create_time))
				->setCellValue('F'.$i, $data->goodsCount)
				->setCellValue('G'.$i, $data->goodsSex)
				->setCellValue('H'.$i, $data->goodsType)
				->setCellValue('I'.$i, $list)
				->setCellValue('J'.$i, $data->memo)
				->setCellValue('K'.$i, '￥'.$data->total_price);
			$i += 1;
		}

		// Excel打开后显示的工作表
		$objPHPExcel->setActiveSheetIndex(0);
		//通浏览器输出Excel报表
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment;filename='.$chinese->convert("UTF-8", "gb2312","订单导出表.xls"));
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

		//恢复Yii自动加载功能
		spl_autoload_register(array('YiiBase','autoload'));
		Yii::app()->end();
	}
	/**
	 * 将物品 数据导出到Excel
	 */
	public function actionStorageGoodsExcel($order_id = null, $id = null, $storage_id = null)
	{
        if (empty($id)) $this->error('参数传递错误！');
        if ($id != 'all') $idList=explode(",",$id);

		$chinese = new Chinese;
		$order = Order::model()->findByPk($order_id);

		$phpExcelPath = Yii::getPathOfAlias('application.components');
		spl_autoload_unregister(array('YiiBase','autoload'));
		include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');

		$objPHPExcel = new PHPExcel();
		$objActSheet = $objPHPExcel->getActiveSheet(0);
		//标题样式
		$objStyle = $objActSheet->getStyle('A1');
		$objStyle->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

		$objActSheet->setTitle('拍摄清单');

		$objActSheet->mergeCells('A1:F1');
		$objActSheet->mergeCells('A2:B2');
		$objActSheet->mergeCells('C2:F2');

		$objActSheet->setCellValue('A1','绿浪视觉拍摄清单');
		$obj = $objActSheet->getStyle('A1');
		$objStyle = $obj->getAlignment();
		$objStyle->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objStyle->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体
		$objFont = $obj->getFont();
		$objFont->setSize(20);
		$objFont->setBold(true);
		$objFont->getColor()->setARGB('000000');

		$objActSheet->setCellValue('A2','入库单号：'.$order->sn);
		$objActSheet->setCellValue('C2','客户名称：'.$order->user_name);

		$i = 3;
		if ($id == 'all' && !empty($storage_id))
		{
			$storage_goods = StorageGoods::model()->findAll(array('condition'=>'storage_id ='.$storage_id,'order'=>'sn ASC'));

			if (empty($storage_goods)) $this->error('物品列表为空');
			foreach ($storage_goods as $key=>$data)
			{
				$list = '';
				$objActSheet
					->setCellValue('A'.$i, $i-2)
					->setCellValue('B'.$i, $data->sn)
					->setCellValue('C'.$i, $data->name)
					->setCellValue('D'.$i, $data->ShootType->name);
				$i += 1;
			}
		}
		else
		{
			foreach ($idList as $key=>$id)
			{
				$list = '';
				$data = StorageGoods::model()->findByPk($id);
				$objActSheet
					->setCellValue('A'.$i, $i-2)
					->setCellValue('B'.$i, $data->sn)
					->setCellValue('C'.$i, $data->name)
					->setCellValue('D'.$i, $data->ShootType->name);
				$i += 1;
			}
		}

		// Excel打开后显示的工作表
		$objPHPExcel->setActiveSheetIndex(0);
		//通浏览器输出Excel报表
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment;filename='.$chinese->convert("UTF-8", "gb2312","拍摄清单导出表.xls"));
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

		//恢复Yii自动加载功能
		spl_autoload_register(array('YiiBase','autoload'));
		Yii::app()->end();
	}
	/**
	 * 订单追踪
	 * Enter description here ...
	 */
	public function actionOrderTrack(array $params = array(), $pageNum = 1, $numPerPage = 20)
	{
		$criteria = new CDbCriteria;

		if (!empty($params['user_sn']))
		{
//			$user_p = substr($params['user_sn'],0,1);
			$user_id = (int)substr($params['user_sn'],1);

			$sql = "SELECT id FROM {{order}} WHERE user_id = ".$user_id;
			$command = Yii::app()->db->createCommand($sql);
			$order_id_list = $command->queryAll();

			foreach ($order_id_list as $key=>$order)
			{
				$criteria->addCondition('t.order_id = '.$order['id'],'or');
			}
		}
		if (!empty($params['user_name']))
		{
			$criteria_user = new CDbCriteria;
			$criteria_user->addSearchCondition('user_name', $params['user_name']);
			$criteria_user->select = "id";
			$order_id_list = Order::model()->cache()->findAll($criteria_user);

			foreach ($order_id_list as $key=>$order)
			{
				$criteria->addCondition('t.order_id = '.$order->id,'or');
			}
		}
		if (!empty($params['start_time']) && !empty($params['end_time']))
		{
			$stare_time = strtotime($params['start_time']);
			$end_time = strtotime($params['end_time']) + 24 * 3600;

			$sql = "SELECT order_id FROM {{storage}} WHERE in_time >= $stare_time and in_time < $end_time";
			$command = Yii::app()->db->createCommand($sql);
			$order_id_list = $command->queryAll();

			foreach ($order_id_list as $key=>$order)
			{
				$criteria->addCondition('t.order_id = '.$order['order_id'],'or');
			}
		}elseif (!empty($params['start_time']))
		{
			$stare_time = strtotime($params['start_time']);

			$sql = "SELECT order_id FROM {{storage}} WHERE in_time >= $stare_time";
			$command = Yii::app()->db->createCommand($sql);
			$order_id_list = $command->queryAll();

			foreach ($order_id_list as $key=>$order)
			{
				$criteria->addCondition('t.order_id = '.$order['order_id'],'or');
			}
		}elseif (!empty($params['end_time']))
		{
			$end_time = strtotime($params['end_time']) + 24 * 3600;

			$sql = "SELECT order_id FROM {{storage}} WHERE in_time < $stare_time";
			$command = Yii::app()->db->createCommand($sql);
			$order_id_list = $command->queryAll();

			foreach ($order_id_list as $key=>$order)
			{
				$criteria->addCondition('t.order_id = '.$order['order_id'],'or');
			}
		}
		$count = OrderTrack::model()->count($criteria);
        $pages = new CPagination($count);
        $pages->currentPage = $pageNum - 1;
        $pages->pageSize = $numPerPage;
        $pages->applyLimit($criteria);

        $criteria->with = "Storage";
		$criteria->order = "Storage.in_time desc";
		$orderTrackList = OrderTrack::model()->findAll($criteria);
		$this->render('track',array(
			'params' => $params,
			'orderTrackList' => $orderTrackList,
			'pages' => $pages,
		));
	}

	/**
	 * 获取排程
	 */
	public function getSchedule($id = null)
	{
		$sql = "SELECT shoot_type,model_id,shoot_time FROM {{schedule}} WHERE order_id = :Id";
		$command = Yii::app()->db->createCommand($sql);
		$schedules = $command->queryAll(true,array(':Id'=>$id));
        return $schedules;
	}
}
