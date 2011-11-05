<?php
class OrderController extends Controller
{
	/**
	 * 订单列表
	 * @param unknown_type $pageNum
	 * @param unknown_type $numPerPage
	 */
	public function actionIndex($pageNum = 1, $numPerPage = 20)
	{
		$criteria = new CDbCriteria;

	    if (!empty($params['sn']))
            $criteria->addSearchCondition('sn', $params['sn']);
        if (!empty($params['user_name']))
            $criteria->addCondition('user_name = \'' . $params['user_name'] . '\'');
        if (!empty($params['status']) && $params['status'] > 0)
        {
            if ($params['status'] == 1)$criteria->addCondition('status = 1');
            if ($params['status'] == 2)$criteria->addCondition('status = 2');
            if ($params['status'] == 3)$criteria->addCondition('status = 3');
            if ($params['status'] == 4)$criteria->addCondition('status = 4');
            if ($params['status'] == 5)$criteria->addCondition('status = 5');
            if ($params['status'] == 6)$criteria->addCondition('status = 6');
            if ($params['status'] == 7)$criteria->addCondition('status = 7');
            if ($params['status'] == 8)$criteria->addCondition('status = 8');
            if ($params['status'] == 9)$criteria->addCondition('status = 9');
            if ($params['status'] == 10)$criteria->addCondition('status = 10');
        }

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
			'pages' => $pages,
			'orders' => $orders
		));
	}
	/**
	 * 订单 删除
	 */
    public function actionOrderDel($id = null)
    {
    	if ($id === null)
	    {
	        $this->error("删除失败，发生错误");
	        $this->redirect(array('order/index'));
	    }
	    // 删除订单物品
        OrderGoods::model()->deleteAllByAttributes(array('order_id'=>$id));
	    // 删除订单模特
        OrderModel::model()->deleteAllByAttributes(array('order_id'=>$id));
	    //  删除订单
	    Order::model()->deleteByPk($id);
	    $this->success('删除成功', array('navTabId'=>'order-index'));
    }
    /**
     * 切换订单状态
     * @param integer $id
     * @param integer $status
     */
    public function actionOrderStatus($id = null, $status = 0)
    {
        if (empty($id))
            $this->error("参数传递错误");

//        Order::model()->updateByPk($id, array('status'=>$status));
        $sql = "UPDATE {{order}} SET status = :status WHERE id = :id";
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

		$orderGoodsList = OrderGoods::model()->findAll($criteria);
		$this->render('goods',array(
			'pages' => $pages,
			'orderGoodsList' => $orderGoodsList
		));
	}
	/**
	 * 订单物品 修改
	 */
	public function actionGoodsEdit($id = null)
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

		if (!empty($id))
			$order = $order->model()->findByPk($id);

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
            $order->attributes = $_POST['Form'];
            $order->shoot_notice=serialize($_POST['Form']['shoot_notice']);
            $order->width=serialize($_POST['Form']['width']);

            if ($order->save())
                $this->success($message, array('navTabId'=>'order-goods'));
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
		if (empty($storage))
		{
			$storage = new Storage;
			$storage -> order_id = $id;
			$storage -> admin_id = Yii::app()->user->id;
			$storage -> in_time = Yii::app()->params['timestamp'];
			$storage -> out_time = 0;

			$storage->save();
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
			'pageSizes' => $pageSizes,
			'pages' => $pages,
			'storage' => $storage,
			'storageGoodsList' => $storageGoodsList
		));
	}
	/**
	 * 仓储 物品
	 */
	public function actionStorageGoods($id = null, $storage_id = null, $order_sn = null)
	{
		$storageGoods = new StorageGoods;
		if (!empty($id))
			$storageGoods = $storageGoods->model()->findByPk($id);

		if (isset($_POST['Form']))
        {
            if (!empty($_POST['Form']['id']))
            {
                $message = '修改成功';
                $storageGoods = $storageGoods->findByPk($_POST['Form']['id']);
            }
            else
            {
                $message = '添加成功';
            	$count = $_POST['Form']['count'];
				for ($i = 1; $i <= $count; $i ++)
				{
				    $sn = substr(strval($i + 1000),1,3);
				    $sn = $_POST['Form']['order_sn'] . $sn;
					$sql = "INSERT INTO {{storage_goods}} ( storage_id, sn, name, shoot_type, is_shoot) VALUES (:val1, :val2, :val3, :val4, :val5)";
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
        $shootTypes = ShootType::model()->findAll();
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
    public function actionStorageOut($id = null)
    {
        if (empty($id))
            $this->error('参数传递错误');

        $storage = Storage::model()->findByPk($id);
        $storage->out_time = Yii::app()->params['timestamp'];
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
}
