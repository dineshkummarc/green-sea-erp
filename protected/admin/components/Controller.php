<?php
class Controller extends CController
{
    public $layout = false;
//    public $compareExclude = array(
//	    'site'=>array('error', 'login', 'logout', 'captcha', ),
//	    'test'=>array('*'),
//	);

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

    /**
     * 重写方法，将GET和POST数据都映射到相关方法的参数中
     */
    public function getActionParams()
    {
        return $_GET+$_POST;
    }
}