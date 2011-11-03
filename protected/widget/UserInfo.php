<?php
class UserInfo extends CWidget
{
    public function run()
    {
        $user = Yii::app()->user;
        $userInfo = User::model()->findByPk($user->id, array("select"=>'id, name, score'));
        if ($userInfo === null)
        {
            $userInfo = new StdClass;
            $userInfo->id = 999;
            $userInfo->name = "Admin";
            $userInfo->score = 0;
        }
        $this->render('userinfo', array('user_info'=>$userInfo));
    }
}
?>