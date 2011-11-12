<?php
class SiteController extends Controller
{

	public function actions()
    {
        return array(
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor' => 0xffffff,
                'width'=>50,
                'height'=>25,
                'minLength'=>4,
                'maxLength'=>4,
                'padding'=>2,
            ),
        );
    }

    public function actionIndex()
    {
    	$sql = "SELECT name,login_count,login_time,INET_NTOA(last_ip) as last_ip ,role_id,is_supper, status FROM {{admin}} WHERE id = :id";
    	$command = Yii::app()->db->createCommand($sql);
    	$result = $command->queryRow(true,array(':id'=>Yii::app()->user->id));
    	$admin = (object)$result;
    	$this->render("index", array('admin'=>$admin));
    }

    /**
     * 后台登陆
     */
    public function actionLogin()
    {
        if (!Yii::app()->user->isGuest) $this->redirect(Yii::app()->homeUrl);
        $model = new LoginForm;
        if (isset($_POST['LoginForm']))
        {
            $model->attributes = $_POST['LoginForm'];
            if ($model->validate() && $model->login())
            {
                $this->redirect(Yii::app()->homeUrl, false);
                Yii::app()->end();
            }
        }
        $this->render('login', array('model' => $model));
    }

    /**
     * 后台注销
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect($this->createUrl("site/login"));
    }

    /**
     *
     * 错误跳转页
     * @param string $message 错误消息
     * @param boolean $isAjax 是否为AJAX提交默认为是
     * @param string $tabid 需要关闭的tabID默认不关闭
     * @param mixed $jumpUrl 自动跳转页面
     * @param int $delay 自动跳转延时默认为5秒
     */
    public function actionError($message, $isAjax = true, $tabid = "", $jumpUrl = array('site/index'), $delay = 5)
    {
        if ($isAjax)
        {
            $output = "<script type=\"text/javascript\">";
            $output .= "alertMsg.error('{$message}');";
            if (!empty($tabid)) $output .= "navTab.closeTab('{$tabid}');";
            $output .= "</script>";
            echo $output;
            Yii::app()->end();
        }
        else
        {

        }
    }
}