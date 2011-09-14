<?php
class UserInfo extends CWidget
{
    public function run()
    {
        $user = Yii::app()->user;
        $userInfo = User::model()->findByPk($user->id, array("select"=>'id, name, score'));
        $this->render('userinfo', array('user_info'=>$userInfo));
    }
}
?>