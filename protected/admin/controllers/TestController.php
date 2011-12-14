<?php
class TestController extends CController
{
	public function actionAdmin()
	{
				$sql = "UPDATE {{admin}} SET `password` = :password, `update_time` = :update_time";
                $command = Yii::app()->db->createCommand($sql);
                $count = $command->execute(array(":password"=>md5("123123"), ":update_time"=>Yii::app()->params['timestamp']));

                if ($count > 0)
                	echo "成功";
                else
                    echo '错误，请联系管理员';
	}
}