<?php
class RoleShoot extends CWidget
{
    public function run()
    {
        $criteria = new CDbCriteria();
        $sql = "SELECT role_id FROM {{admin}} WHERE id = :Id";
        $command = Yii::app()->db->createCommand($sql);
        $beginTime = strtotime(date('Y-m-d'));
        $endTime = $beginTime + (3600 * 24);
        $criteria ->condition = "shoot_time >= ".$beginTime;
        $criteria ->condition .= " AND shoot_time <= ".$endTime;
        $roleId = $command->queryScalar(array(':Id'=>Yii::app()->user->id));
        if ($roleId === 6 )
            $criteria->addCondition('shoot_id = '.Yii::app()->user->id);
        if ($roleId === 7)
            $criteria->addCondition('stylist_id = '.Yii::app()->user->id);


        $models = Schedule::model()->findAll($criteria);
		$this->render('role/shoot',array(
			'models' => $models,
		));
    }

	/**
	 * 获取订单信息
	 */
	public function getOrder($id = null, $params = null)
	{
		if(!empty($params))
			$sql = $params;
		if(!empty($id))
			$sql = "SELECT id, sn, user_name, status FROM {{order}} WHERE id = :id";
		else
			$sql = "SELECT id, sn, user_name FROM {{order}}";
		$command = Yii::app()->db->createCommand($sql);
		$types = $command->queryRow(true, array(':id'=>$id));
		if ($types === false)
		{
			return false;
		}
		else{
			return $types;
		}
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

	//根据订单ID查询分类信息
	public function getShootType($orderId = null)
	{
		// 根据订单ID查询拍摄类型
        $sql = "SELECT shoot_type FROM {{order_goods}} WHERE order_id =:Id GROUP BY shoot_type";
		$command = Yii::app()->db->createCommand($sql);
		$shootType = $command->queryAll(true, array(':Id'=>$orderId));
		return $shootType;
	}
}