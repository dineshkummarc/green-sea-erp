<?php

class Controller extends CController
{
	public $layout = false;
	public $compareExclude = array(
	    'site'=>array('error', 'login', 'logout', 'captcha', ),
	    'test'=>array('*'),
	);

    // 对action的权限验证
	protected function beforeAction($action)
	{
	    //Yii::app()->cache->flush();
	    $controller = strtolower($this->id);
	    $actionId = strtolower($action->id);
	    if (isset($this->compareExclude[$controller]))
	    {
	        $exclude = $this->compareExclude[$controller];
	        if (in_array('*', $exclude) || in_array($actionId, $exclude)) return true;
	    }
	    $isAjax = isset($this->actionParams['isAjax']) ? $this->actionParams['isAjax'] : false;
	    $tabid = isset($this->actionParams['tabid']) ? $this->actionParams['tabid'] : "";

	    $user = Yii::app()->user;
	    // login required
        if ($user->isGuest)
        {
            if (empty(Yii::app()->request->urlReferrer) || Yii::app()->request->urlReferrer === Yii::app()->homeUrl)
                $this->redirect(array('site/login'), false);
            else
                $this->error('登陆超时，请重新登陆！', array(), 301);
            return false;
        }

        // check access
	    $authName = $this->id.'/'.$action->id;
        if ($user->checkAccess($authName))
            return true;
        else
        {
            if (Yii::app()->request->urlReferrer == Yii::app()->request->hostInfo.$this->createUrl("site/login"))
            {
                header("Content-Type: text/html; charset=utf8;");
                echo "<script>alert('您没有权限登录后台，请联系管理员'); window.location.href='".$this->createUrl("site/logout")."'</script>";
                Yii::app()->end();
            }
            else
                $this->error('您没有权限执行本操作', array('navTabId'=>$tabid));
        }
        return false;
	}

	public function createUrl($route = '', $params = array(), $ampersand = '&')
	{
	    if (strpos($route, 'http://') !== false) return $route;
	    return parent::createUrl($route, $params, $ampersand);
	}

	public function error($message, $params = array(), $errorCode = 300)
	{
	    $result = array('statusCode'=>$errorCode, 'message'=>$message);
	    $result = array_merge($result, $params);
        $this->json($result);
	}

	public function success($message, $params = array())
	{
	    $result = array('statusCode'=>200, 'message'=>$message);
	    $result = array_merge($result, $params);
        $this->json($result);
	}

	/**
	 * 输出JSON字符串
	 * @param array $array
	 */
	public function json($var = array())
	{
	    echo CJSON::encode($var);
	    Yii::app()->end();
	}

	public function getActionParams()
	{
	    return $_GET + $_POST;
	}

}