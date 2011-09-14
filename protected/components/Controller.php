<?php
class Controller extends CController
{
    public $cssFiles = array();
    public $jsFiles = array();
    public $exclude = array('login', 'logout', 'test');
    private $_messageContainer = "<div class='msg'></div>\r\n";
    private $_message = "";
    private $_hasError = false;

    public function beforeAction($action)
    {
        if (!in_array($action->id, $this->exclude) && Yii::app()->user->isGuest)
            $this->redirect(Yii::app()->homeUrl);
        return true;
    }

    public function beforeRender()
    {
        if ($this->action->id == "print") return true;
        $cs = Yii::app()->getClientScript();
        if (!empty($this->cssFiles))
        {
            if (is_array($this->cssFiles))
            {
                foreach ($this->cssFiles as $css)
                {
                    $path = Yii::app()->basePath . "/../css/" . $css;
                    $url = "css/". $css;
                    if (file_exists($path)) $cs->registerCssFile($url);
                }
            }
        }

        if (!empty($this->jsFiles))
        {
            if (is_array($this->jsFiles))
            {
                foreach ($this->jsFiles as $js)
                {
                    $path = Yii::app()->basePath . "/../js/" . $js;
                    $url = "js/". $js;
                    if (file_exists($path)) $cs->registerScriptFile($url);
                }
            }
        }
        return true;
    }

    public function success($message = "操作成功", $msg_class = "success-big")
    {
        if ($this->_hasError) return;
        $this->_hasError = false;
        $this->message($message, $msg_class);
    }

    public function error($message = "操作失败", $msg_class = "error-big")
    {
        $this->_hasError = true;
        $this->message($message, $msg_class);
    }

    public function message($message, $msg_class)
    {
        if (empty($this->_message))
            $this->_message = $message;
        else if (is_array($this->_message))
            $this->_message .= $message;
        else
            $this->_message .= "<br />$message";

        Yii::app()->user->setFlash($msg_class, $this->_message);
    }

    public function getHasError()
    {
        return $this->_hasError;
    }

    /**
     * 获取引用页，如果不存在则返回首页
     */
    public function getUrlReferrer($default = null)
    {
        $url = Yii::app()->request->urlReferrer;
        if ($url === null)
            $url = !empty($default) ? $default : Yii::app()->homeUrl;
        return $url;
    }

}
?>