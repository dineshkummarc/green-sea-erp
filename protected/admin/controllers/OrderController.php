<?php
class OrderController extends Controller
{
	/**
	 * 订单列表
	 * @param unknown_type $pageNum
	 * @param unknown_type $numPerPage
	 */
	public function actionIndex(array $params = array(), $pageNum = 1, $numPerPage = 20, $sort = null)
	{
		$criteria = new CDbCriteria;
		if ($sort == 'time')
		{
			$criteria->order = 'create_time DESC';
		}
		if ($sort == 'status')
		{
			$criteria->order = 'status ASC';
		}
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

		$orders = Order::model()->cache()->findAll($criteria);
//		if ($sort != null)
//            $this->success('aaa', array('navTabId'=>'order-goods'));
		$this->render('index',array(
			'params'=>$params,
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

}
