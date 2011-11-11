<?php

class OrderController extends Controller
{
    public $_shootNotice;
    public $cssFiles = array('order.css');
    public $jsFiles = array('order.js');

    /**
     * 订单列表
     */
	public function actionIndex($page = 1, $size = 20)
	{
	    $order = Order::model();
	    $criteria = new CDbCriteria;
	    $criteria->select = '*';
	    if (Yii::app()->user->id != 999)
	        $criteria->condition = "user_id = " . Yii::app()->user->id;
	    $count = $order->count($criteria);
        $pages = new CPagination($count);
        $pages->currentPage = $page - 1;
        $pages->pageSize = $size;
        $pages->applyLimit($criteria);
        $criteria->order = 'create_time DESC, status ASC';

        $orderList = $order->findAll($criteria);
		$this->render('index', array('orderList'=>$orderList, 'pages'=>$pages));
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
	    {
            $goodsList = OrderGoods::model()->findAllByAttributes(array("order_id"=>$orderId));
	    }
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
	    {
	        $_POST['count'] = 1;
            $goods = (object)$goodsList[$id];
	    }
	    else
	        $goods = new StdClass;

	    $goodsType = GoodsType::getType();

        if (isset($_POST['Form']))
        {
            foreach ($_POST['Form'] as $form)
            {
                $form['count'] = trim($form['count']);
                if (empty($form['count'])) continue;
                if (empty($form['id']))
                {
                    $id = $user->getState("lastGoodsId");
        	        if ($id === null) $id = 1;
        	        $user->setState("lastGoodsId", $id + 1);
                }
                else
                    $id = $form['id'];

                $goods = (object)$form;
                if (empty($goods->id))
                    $goods->id = $id;

                if ($goods->type != 0)
                    $goods->type_name = $goodsType[$goods->type]->name;

                $totalPrice = $user->getState("totalPrice");
                // TODO 添加价格计算
                $price = 0;
                $goods->price = $price;

                if ($totalPrice === null)
                    $totalPrice = $price;
                else
                    $totalPrice += $price;

                $user->setState("totalPrice", $totalPrice);

                // 检查订单中存在的拍摄类型
                $shootTypes = $user->getState('shootTypes');
                // 获取所有拍摄类型
                $shootType = ShootType::getType(true);
                // 保存拍摄类型
                if (empty($shootTypes) || !isset($shootTypes[$goods->shoot_type]))
                    $shootTypes[$goods->shoot_type] = $shootType[$goods->shoot_type]['name'];

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
            }

        	$this->success("添加成功");
        	$this->redirect(array("order/goodsList"));
        }

        $sql = "SELECT * FROM {{shoot_type}}";
        $command = Yii::app()->db->createCommand($sql);
        $shootType = $command->queryAll();

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
                if (!empty($styles) && in_array(0, $styles))
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
	public function actionSelectModels()
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

        //获取拍摄数量和
        $goodsList = $user->getState("goodsList");
		$goodsCounts=0;
        foreach ($goodsList as $goods)
            if ($goods->shoot_type == 1 || $goods->shoot_type == 2 || $goods->shoot_type == 5)
        	    $goodsCounts += (int)$goods->count;

        if ((isset($shootTypes[1]) || isset($shootTypes[2]) || isset($shootTypes[5])) && $goodsCounts >= 50)
            $this->redirect(array("order/selectModels", "id"=>$id));
        else
            $user->setState("selectedModels", null);

        if (isset($_POST['Form']))
        {
            // 获取相关数据
            $userInfo = User::model()->findByPk($user->id);
            $admin_id = $userInfo->admin_id;//负责人ID
            $totalPrice = $user->getState("totalPrice");

            $order = new Order;
            // 添加固定值
            $order->sn = $order->getSn();
            $order->user_id = $userInfo->id;
            $order->user_name = $userInfo->name;
            $order->total_price = $totalPrice;
            $order->create_time = Yii::app()->params['timestamp'];
            $order->update_time = Yii::app()->params['timestamp'];
            $order->pay_time = 0;
            $order->receive_time = 0;
            $order->receive_address = isset($userInfo->ReceiveAddress) ? $userInfo->ReceiveAddress->getFullAddress() : "";
            $order->status = 1;
            $order->logistics_sn = $_POST['Form']['logistics_sn1'].' '.$_POST['Form']['logistics_sn2'];

            $order->attributes = $_POST['Form'];
            $order->width = serialize($order->width);
            $order->shoot_notice = serialize($order->shoot_notice);

            $transaction = Yii::app()->db->beginTransaction();
            // 保存订单、订单物品、模特
            if ($order->save() && $this->saveGoods($order->id, $order->sn) && $this->saveModel($order->id))
            {
                $transaction->commit();
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

                //添加订单追踪信息
        		OrderTrack::getOrderTrackId($order->id,$admin_id);

                $this->success("订单添加成功");
                $this->redirect(array('order/index'));
            }
            else
            {
                $transaction->rollBack();
                $this->error(Dumper::dumpString($order->getErrors()));
                $this->refresh();
            }
        }

        // 获取订单内存在的拍摄类型
        $selectedShootType = $user->getState("shootTypes");
        $shootNotice = Order::getShootNotice();

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
	        $goods->type_name = 0;
	        if (empty($goods->real_count))
	            $goods->real_count = 0;
            if (empty($goods->shoot_count))
                $goods->shoot_count = 0;

	        $orderGoods = new OrderGoods;
	        $orderGoods->attributes = (array)$goods;
	        // 忽略订单物品的保存错误
	        if (!$orderGoods->save())
	        {
	            $this->error(Dumper::dumpString($orderGoods->getErrors()));
	            $this->refresh();
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

}