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
		
		$count = Order::model()->cache()->count($criteria);
        $pages = new CPagination($count);
        $pages->currentPage = $pageNum - 1;
        $pages->pageSize = $numPerPage;
        $pages->applyLimit($criteria);
		
		$orders = Order::model()->cache()->findAll($criteria);
		$this->render('index',array(
			'pages' => $pages,
			'orders' => $orders
		));
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
		$orders = new Order;
		
		if (!empty($id))
			$orders = $orders->model()->findByPk($id);
		
		if (isset($_POST['Form']))
        {
            if (!empty($_POST['Form']['id']))
            {
                $message = '修改成功';
                $orders = $orders->findByPk($_POST['Form']['id']);
            }
            else
            {
                $message = '添加成功';
            }
            $orders->attributes = $_POST['Form'];
            $orders->shoot_notice=serialize($_POST['Form']['shoot_notice']);
            $orders->width=serialize($_POST['Form']['width']);
            
            if ($orders->save())
                $this->success($message, array('navTabId'=>'order-goods'));
            else
            {
                $error = array_shift($orders->getErrors());
                $message = '错误：'.$error[0];
                $this->error($message);
            }
        }
        $goods = unserialize($orders->shoot_notice);
        
        $shootType = $this->loadShootType();
		$shootTypeList = unserialize($orders->width);
//        CVarDumper::dump($shootTypeList,10,true);
        $shootNotice = $this->getShootNotice();
		$this->render('shoot_scene',array(
			'goods'	=> $goods,
			'shootType' => $shootType,
			'shootTypeList' => $shootTypeList,
			'shootNotice' => $shootNotice,
			'orders' => $orders
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
}
