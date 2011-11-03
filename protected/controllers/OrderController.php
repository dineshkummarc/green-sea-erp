<?php

class OrderController extends Controller
{
    public $_shootNotice;
    public $cssFiles = array('order.css');
    public $jsFiles = array('order.js');

    /**
     * 订单列表
     */
	public function actionIndex($sort = '', $logistics = '', $page = 1, $size = 20)
	{
	    $order = Order::model();
	    $criteria = new CDbCriteria;
	    $criteria->select = '*';
	    if (Yii::app()->user->id != 999)
	        $criteria->condition = "user_id = " . Yii::app()->user->id;

        if (!empty($logistics))
            $criteria->addCondition("logistics_sn like '%{$logistics}%'");
	    $count = $order->count($criteria);
        $pages = new CPagination($count);
        $pages->currentPage = $page - 1;
        $pages->pageSize = $size;
        $pages->applyLimit($criteria);
        $params = array();
        if ($sort === 'time')
        {
            $params['sort'] = 'time';
            $criteria->order = 'create_time DESC';
        }
        elseif ($sort === 'status')
        {
            $params['sort'] = 'status';
            $criteria->order = 'status ASC';
        }
        else
            $criteria->order = 'create_time DESC, status ASC';

        $orderList = $order->findAll($criteria);
		$this->render('index', array('orderList'=>$orderList, 'pages'=>$pages, 'params'=>$params));
	}

	/**
	 * 查看订单
	 * @param integer $id
	 */
    public function actionShow($id = null)
	{
	    if (empty($id))
	        $this->redirect('order/index');
	    else
	        $order = Order::model()->findByPk($id, array('select'=>'*'));

	    $this->render('show', array('order'=>$order));
	}

	/**
	 * 删除订单
	 * @param integer $id
	 */
	public function actionDel($id = null)
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
	    $this->success("删除成功");
        $this->redirect(array('order/index'));
	}


	public function actionChangeStatus($id = null, $status = null)
	{
	    if ($id === null || $status === null)
	    {
	        $this->error("参数不正确");
            $this->redirect($this->urlReferrer);
	    }
	    Order::changeStatus($id, $status);
	    if ($status == 2)
	    {
	        // 消费金额等积分
	        $sql = "SELECT total_price FROM {{order}} WHERE id = $id";
	        $data = Yii::app()->db->createCommand($sql)->query();
            $data->bindColumn(1, $price);
            if ($data->read() !== false) User::addScore((int)$price);

            // 新客户送积分和累积消费积分
            $sql = "SELECT first, accumulation_price FROM {{user}} WHERE id = " . Yii::app()->user->id;
            $data = Yii::app()->db->createCommand($sql)->query();
            $data->bindColumn(1, $first);
            $data->bindColumn(2, $price);
            if ($data->read() !== false)
            {
                // 如果为首次下单
                if ($first == 1)
                {
                    User::addScore(1500, "首次下单积分奖励");
                    // 清楚首次下单状态，防止重复奖励
                    $sql = "UPDATE  {{user}} first = 0, update_time = :update_time WHERE id = " . Yii::app()->user->id;
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":update_time", Yii::app()->params['timestamp'], PDO::PARAM_INT);
                    $command->execute();
                }
                // 如果累积消费大于等于5000，则额外赠送3000积分
                if ((int)$price >= 5000)
                {
                    User::addScore(3000, "累积消费额外积分奖励");
                    // 减去累积消费5000，防止重复奖励
                    $sql = "UPDATE {{user}} SET accumulation_price = :price , update_time = :update_time WHERE id = " . Yii::app()->user->id;
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":price", strval($price - 5000), PDO::PARAM_STR);
                    $command->bindParam(":update_time", Yii::app()->params['timestamp'], PDO::PARAM_INT);
                    $command->execute();
                }
            }
	    }
	    $this->success("修改成功");
	    $this->redirect($this->urlReferrer);
	}

	/**
	 * 拍摄物品列表
	 * @param integer $id 订单ID
	 */
	public function actionGoodsList($id = null)
	{
	    $goodsList = Yii::app()->user->getState("goodsList");
	    if ($goodsList === null && $id !== null)
            $goodsList = OrderGoods::model()->findAllByAttributes(array("order_id"=>$orderId));
        else if ($goodsList === null)
            $goodsList = array();

	    $goodsType = GoodsType::model()->findAll();
	    $result = array();
        foreach ($goodsType as $type)
        {
            $result[$type->id] = $type->name;
        }
        $goodsType = $result;

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

	    $shootType = ShootType::model()->findAll();
	    $result = array();
        foreach ($shootType as $type)
        {
            $result[$type->id] = $type->name;
        }
        $shootType = $result;

	    $styles = Style::model()->findAll();
	    $result = array();
        foreach ($styles as $style)
        {
            $result[$style->id] = $style->name;
        }
        $result[0] = "不限";
        $styles = $result;

	    $this->render("goodsList", array(
	    	'goodsList'=>$goodsList,
	        'goodsType'=>$goodsType,
	        'season'=>$season,
	        'sex'=>$sex,
        	'shootType'=>$shootType,
	    	'styles'=>$styles,
	    ));
	}

	/**
	 * 添加、修改拍摄物品
	 * @param integer $id
	 */
	public function actionGoodsEdit($id = null)
	{
	    $user = Yii::app()->user;
	    // 获取session中的数据
        $goodsList = $user->getState("goodsList");
	    if ($id !== null && $goodsList !== null)
            $goods = (object)$goodsList[$id];
	    else if ($id !== null)
	        $goods = OrderGoods::model()->findByPk($id);
	    else
	        $goods = new StdClass;

	    $goodsType = GoodsType::model()->findAll();
	    $result = array();
        foreach ($goodsType as $type)
        {
            $result[$type->id] = (object)$type->attributes;
        }
        $goodsType = $result;

        if (isset($_POST['Form']))
        {
            if ($id === null)
            {
                $id = $user->getState("lastGoodsId");
    	        if ($id === null)
    	        {
    	            $id = 1;
    	        }
    	        $user->setState("lastGoodsId", $id + 1);
            }

            $goods = (object)$_POST['Form'];
            if (!empty($goods->id))
                $id = $goods->id;
            else
                $goods->id = $id;

            $totalPrice = $user->getState("totalPrice");
            if ($goods->type != 0)
                $goods->type_name = $goodsType[$goods->type]->name;
            if (isset($_FILES["example_img"]))
                $goods->example_img = $_FILES["example_img"];

            // TODO 添加价格计算
            $price = 0;
            $goods->price = $price;

            if ($totalPrice === null)
                $totalPrice = $price;
            else
                $totalPrice += $price;

            $user->setState("totalPrice", $totalPrice);

            // 如果是从数据库取出来的
//            if ($goods instanceof OrderGoods)
//            {
//                $goods->attributes = $goods;
//                if (!$goods->save())
//                {
//                    $this->error(Dumper::dumpString($goods->getErrors()));
//                    $this->redirect(array("order/edit", array('step'=>"goodsEdit", 'id'=>$id)));
//                }
//                $goods = (object)$goods->attributes;
//            }
//            else
//            {
//                $goodsList[$id] = $goods;
//            }

            // 检查订单中存在的拍摄类型
            $shootTypes = $user->getState('shootTypes');
            // 获取所有拍摄类型
            $shootType = ShootType::model()->findAll();
            foreach ($shootType as $type)
            {
                $shootType[$type->id] = (object)$type->attributes;
            }
            // 保存拍摄类型
            if (empty($shootTypes) || !isset($shootTypes[$goods->shoot_type]))
                $shootTypes[$goods->shoot_type] = $shootType[$goods->shoot_type]->name;

            $styles = $user->getState('modelStyles');
            if ($goods->style == 0)
            {
                $styles = array();
                $styles[0] = 0;
            }
            else if (empty($styles) || ($goods->style != 0 && !isset($styles[$goods->type])) )
            {
                $styles[$goods->type] = $goods->type;
            }
            // 保存到session
            $goodsList[$id] = $goods;
            $user->setState("shootTypes", $shootTypes);
            $user->setState("modelStyles", $styles);
            $user->setState("goodsList", $goodsList);
            $this->success("添加成功");
            $this->redirect(array("order/goodsList"));
        }

        if (isset($goods->attributes))
            $goods = (object)$goods->attributes;

	    $shootType = ShootType::model()->findAll();
        foreach ($shootType as $key=>$type)
        {
            $shootType[$key] = $type->attributes;
        }

	    $styles = Style::model()->findAll();
        foreach ($styles as $key=>$style)
        {
            $styles[$key] = $style->attributes;
        }

	    $this->render("goodsEdit", array(
	        'id'=>$id,
	        'goods'=>$goods,
	        'goodsType'=>$goodsType,
        	'shootType'=>$shootType,
	    	'styles'=>$styles,
	    ));
	}

	/**
	 * 删除拍摄物品
	 * @param integer $id
	 */
	public function actionGoodsDel($id = null)
	{
	    $user = Yii::app()->user;
	    if ($id === null)
	    {
	        $user->error("参数错误");
	    }
	    else
	    {
	        $goodsList = $user->getState("goodsList");
	        unset($goodsList[$id]);
    	    $user->setState("goodsList", $goodsList);
	        $shootType = ShootType::model()->findAll();
            foreach ($shootType as $type)
            {
                $shootType[$type->id] = (object)$type->attributes;
            }
    	    $styles = array();
    	    $shootTypes = array();
    	    foreach ($goodsList as $goods)
    	    {
    	        // 保存拍摄类型
                if (!isset($shootTypes[$goods->shoot_type]))
                    $shootTypes[$goods->shoot_type] = $shootType[$goods->shoot_type]->name;

                if (!empty($styles) && $styles[0] == 0)
                    continue;
        	    if ($goods->style == 0)
                {
                    $styles = array();
                    $styles[0] = 0;
                }
                else if ($goods->style != 0 && !isset($styles[$goods->type]) )
                {
                    $styles[$goods->type] = $goods->type;
                }
    	    }
    	    // 更新模特风格
    	    $user->setState("shootTypes", $shootTypes);
    	    // 更新订单拍摄类型
            $user->setState("modelStyles", $styles);
    	    $this->success("删除成功");
	    }
	    $this->redirect(array("order/goodsList"));
	}

	/**
	 * 选择模特
	 * @param integet $id 订单ID
	 */
	public function actionSelectModels($id = null)
	{
	    $user = Yii::app()->user;
	    if (isset($_POST['Form']))
	    {
	        if (empty($_POST['Form']['models']))
	        {
	            $this->error("请选择模特");
	            $this->redirect(array("order/selectModels"));
	        }
	        else
	        {
	            $models = $_POST['Form']['models'];
                $user->setState("selectedModels", $models);
                $this->redirect(array("order/shootScene"));
	        }
	    }

	    $criteria = new CDbCriteria;
	    $criteria->select = "t.id, t.nick_name, t.head_img, t.picture";
	    $models = Models::model()->findAll($criteria);
	    $modelArr = array();
	    foreach ($models as $model)
	    {
	        $modelArr[$model->id] = (object)$model->attributes;
	    }
	    $models = $user->getState("selectedModels");

	    $this->render("selectModels", array(
	        "orderId"=>$id,
	    	"selectedModels"=>$models,
	    	"models"=>$modelArr
	    ));
	}

	/**
	 * 添加、修改拍摄需求
	 * @param integet $id 订单ID
	 */
	public function actionShootScene($id = null)
	{
        $user = Yii::app()->user;
        $shootTypes = $user->getState('shootTypes');
        $selectedModels = $user->getState("selectedModels");
        if ((isset($shootTypes[1]) || isset($shootTypes[2])) && empty($selectedModels))
            $this->redirect(array("order/selectModels", "id"=>$id));

        if (isset($_POST['Form']))
        {
            // 获取相关数据
            $userInfo = User::model()->findByPk($user->id);
            $totalPrice = $user->getState("totalPrice");

            $order = new Order;
            // 添加固定值
            $order->sn = $order->getSn();
            $order->user_id = $userInfo->id;
            $order->user_name = $userInfo->name;
            //$order->total_price = $totalPrice;
            $order->create_time = Yii::app()->params['timestamp'];
            $order->update_time = Yii::app()->params['timestamp'];
            $order->pay_time = 0;
            $order->receive_time = 0;
            $order->shoot_time = 0;
            $order->receive_address = isset($userInfo->ReceiveAddress) ? $userInfo->ReceiveAddress->getFullAddress() : "";
            $order->status = 1;

            // 分离不是订单数据表的数据，以及合并物品公共需求
            $goodsList = $user->getState("goodsList");
            if ($goodsList === null)
            {
                $this->error("发生错误，请重新下单");
                $this->redirect(array("order/index"));
            }
            $width = $_POST['Form']['width'];
            $detail_width = $_POST['Form']['detail_width'];
            foreach ($goodsList as $key=>$goods)
            {
                if (!empty($_POST['Form']['width']))
                {
                    $goods->width = $width[$goods->shoot_type];
	                $goods->detail_width = $detail_width[$goods->shoot_type];
                }
                $goodsList[$key] = $goods;
                $user->setState("goodsList", $goodsList);
            }
            unset($_POST['Form']['width'], $_POST['Form']['detail_width']);

            $order->attributes = $_POST['Form'];
            $order->shoot_notice = @serialize($order->shoot_notice);

            // 保存订单、订单物品、模特
            if ($order->save() && $this->saveGoods($order->id, $order->sn) && $this->saveModel($order->id))
            {
                // 保存完毕，清空session
                $user->setState("shootTypes", null);
                $user->setState("totalPrice", null);
                $user->setState("shootTypeWidth", null);
                $user->setState("lastGoodsId", null);
                $user->setState("modelStyles", null);
                $user->setState("selectedModels", null);
                $user->setState("goodsList", null);
                // 用户数据修改
                $nextOrder = $user->getState("nextOrder");
                $nextOrder += 1;
                $user->setState("nextOrder", $nextOrder);
                $userInfo->next_order = $nextOrder;
                $userInfo->save();
                $this->success("订单添加成功");
                $this->redirect(array('order/index'));
            }
            else
            {
                $this->error(Dumper::dumpString($order->getErrors()));
                $this->refresh();
            }
        }

        // 获取订单内存在的拍摄类型
        $selectedShootType = $user->getState("shootTypes");
        $shootNotice = $this->getShootNotice();

	    $this->render("shootScene", array(
	        "selectedShootType"=>$selectedShootType,
	        "shootNotice"=>$shootNotice
	    ));
	}

	public function actionEditShootScene($id)
	{
	    if (isset($_POST['id']))
	    {
	        $sql = "UPDATE {{order}} SET `memo` = :memo, `update_time` = :time WHERE `id` = :id";
	        $command = Yii::app()->db->createCommand($sql);
	        $command->execute(array(':id'=>$_POST['id'], ':memo'=>$_POST['memo'], ':time'=>Yii::app()->params['timestamp']));
	        $this->success("修改成功");
            $this->redirect(array('order/index'));
	    }
	    $sql = "SELECT `memo` FROM {{order}} WHERE id = :id";
	    $command = Yii::app()->db->createCommand($sql);
	    $memo = $command->queryScalar(array(':id'=>$id));
	    $this->render('editShootScene', array('memo'=>$memo, 'id'=>$id));
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

        // 组合物品公用信息
        $goodsPublicInfo = array();
        foreach ($goodsList as $goods)
        {
            $goodsPublicInfo['width'][$goods->shoot_type][0] = $goods->width;
            $goodsPublicInfo['width'][$goods->shoot_type][1] = $goods->detail_width;
        }

	    $this->render("print", array(
	        'order'=>$order,
	        'goodsList'=>$goodsList,
	    	'models'=>$models,
	        'season'=>$season,
	        'sex'=>$sex,
	        'shootType'=>$shootType,
	        'style'=>$style,
	        'models'=>$models,
	        'goodsPublicInfo'=>$goodsPublicInfo,
            'shootNotice'=>$this->getShootNotice()
	    ));
	}

	/**
	 * 保存订单物品
	 * @param integer $id order id
	 */
	public function saveGoods($id = null, $orderSn)
	{
	    if ($id === null)
	    {
	        $this->error("订单保存失败");
	        $this->refresh();
	    }
	    $user = Yii::app()->user;
	    $goodsList = $user->getState("goodsList");
	    $shootTypeWidth = $user->getState("shootTypeWidth");
	    foreach ($goodsList as $goods)
	    {
	        $goods->sn = $orderSn . $goods->id;
	        $goods->id = null;
	        $goods->order_id = $id;
	        $goods->status = 2;

	        $orderGoods = new OrderGoods;
	        $orderGoods->attributes = (array)$goods;
//	        Dumper::dump($goods);Yii::app()->end();
	        if (!$orderGoods->save())
	        {
	            $this->error(Dumper::dumpString($orderGoods->getErrors()));
	            return false;
	        }
	    }
	    return true;
	}

	/**
	 * 保存模特
	 * @param integer $id 订单ID
	 */
	public function saveModel($id = null)
	{
	    if ($id === null)
	    {
	        $this->error("订单保存失败");
	        $this->refresh();
	    }
        $models = Yii::app()->user->getState("selectedModels");
        if ($models === null) return true;
        foreach ($models as $model)
        {
            $orderModel = new OrderModel;
            $orderModel->order_id = $id;
            $orderModel->model_id = $model;
            if (!$orderModel->save())
            {
                $this->error(Dumper::dumpString($orderModel->getErrors()));
                return false;
            }
        }
        return true;
	}

	/**
	 * 添加、修改订单物品风格
	 * @param integer $goodsId
	 * @param integer $styleId
	 */
	public function editGoodsStyles($goodsId, $styleId)
	{
	    $styles = OrderGoodsStyles::model()->findByPk($goodsId);
	    if ($styles === null)
	        $styles = new OrderGoodsStyles;
        $styles->style_id = $styleId;
        return $styles->save();
	}

	public function getShootNotice()
	{
	    if (empty($this->_shootNotice))
	        $this->_shootNotice = require_once(Yii::getPathOfAlias('application.config', true) . '\shootnotice.php');
	    return $this->_shootNotice;
	}
}