<?php
class NewOrder extends CWidget
{
    public function run()
    {
        $order = Order::model();
	    $criteria = new CDbCriteria;

        $criteria->condition = "user_id = " . Yii::app()->user->id;
        $orderCount = $order->count($criteria);
        $criteria->addCondition("status != 12");

	    $criteria->order = "status DESC";
	    $criteria->limit = 1;

	    $step= array(
            2=>"已付款",
            4=>"排程",
            5=>"拍摄",
            7=>"修图",
            9=>"上传",
            11=>"货物已寄出",
            12=>"确认收货",
        );
	    $order = $order->find($criteria);

		$this->render('neworder', array('orderCount'=>$orderCount, 'order'=>$order,'orderType'=>$step));
    }
}
