<?php
/**
 * YiiDebugToolbarRouter class file.
 *
 * @author Sergey Malyshev <malyshev.php@gmail.com>
 */


/**
 * YiiDebugToolbarRouter represents an ...
 *
 * Description of YiiDebugToolbarRouter
 *
 * @author Sergey Malyshev <malyshev.php@gmail.com>
 * @version $Id$
 * @package YiiDebugToolbar
 * @since 1.1.7
 */
class YiiDebugToolbarRoute extends CLogRoute
{

    /* The filters are given in an array, each filter being:
     * - a normal IP (192.168.0.10 or '::1')
     * - an incomplete IP (192.168.0.* or 192.168.0.)
     * - a CIDR mask (192.168.0.0/24)
     * - "*" for everything.
     */
    public $ipFilters=array('127.0.0.1','::1');

    private $_toolbarWidget,
            $_startTime,
            $_endTime;


    public function getStartTime()
    {
        return $this->_startTime;
    }

    public function getEndTime()
    {
        return $this->_endTime;
    }

    public function getLoadTime()
    {
        return ($this->endTime-$this->startTime);
    }

    protected function getToolbarWidget()
    {
        if (null === $this->_toolbarWidget)
        {
            $this->_toolbarWidget = Yii::createComponent('YiiDebugToolbar', $this);
        }
        return $this->_toolbarWidget;
    }

    public function init()
    {
        $this->_startTime=microtime(true);

        parent::init();

        $this->enabled && $this->enabled = ($this->allowIp(Yii::app()->request->userHostAddress)
                && !Yii::app()->getRequest()->getIsAjaxRequest());

        if ($this->enabled)
        {
            Yii::app()->attachEventHandler('onBeginRequest', array($this, 'onBeginRequest'));
            Yii::app()->attachEventHandler('onEndRequest', array($this, 'onEndRequest'));
            Yii::setPathOfAlias('yii-debug-toolbar', dirname(__FILE__));
            Yii::import('yii-debug-toolbar.*');
            $this->categories = '';
            $this->levels='';
        }

    }

    protected function onBeginRequest(CEvent $event)
    {
//        if ('CWebApplication' === get_class(Yii::app()))
//        {
//            Yii::app()->detachEventHandler('onBeginRequest',
//                    array($this, 'onBeginRequest'));
//
//            if(Yii::app()->hasEventHandler('onBeginRequest'))
//                Yii::app()->onBeginRequest(new CEvent(Yii::app()));
//        }

        
        $this->getToolbarWidget()
             ->init();

//        if ('CWebApplication' === get_class(Yii::app()))
//        {
//            $this->processRequest();
//            Yii::app()->end();
//        }
    }

    /**
     * Processes the current request.
     * It first resolves the request into controller and action,
     * and then creates the controller to perform the action.
     */
    private function processRequest()
    {
        if(is_array(Yii::app()->catchAllRequest) && isset(Yii::app()->catchAllRequest[0]))
        {
            $route=Yii::app()->catchAllRequest[0];
            foreach(array_splice(Yii::app()->catchAllRequest,1) as $name=>$value)
                $_GET[$name]=$value;
        }
        else
            $route=Yii::app()->getUrlManager()->parseUrl(Yii::app()->getRequest());
        Yii::app()->runController($route);
    }

    protected function onEndRequest(CEvent $event)
    {

    }

    public function collectLogs($logger, $processLogs=false)
    {
        parent::collectLogs($logger, $processLogs);
    }

    protected function processLogs($logs)
    {
        $this->_endTime = microtime(true);
        $this->enabled && $this->getToolbarWidget()->run();
    }

    /**
     * Checks to see if the user IP is allowed by {@link ipFilters}.
     * @param string $ip the user IP
     * @return boolean whether the user IP is allowed by {@link ipFilters}.
     */
    protected function allowIp($ip)
    {
        foreach ($this->ipFilters as $filter) {
            $filter = trim($filter);
            // normal or incomplete IPv4
            if (preg_match('/^[\d\.]*\*?$/', $filter)) {
                $filter = rtrim($filter, '*');
                if (strncmp($ip, $filter, strlen($filter)) === 0) {
                    return true;
                }
            }
            // CIDR
            else if (preg_match('/^([\d\.]+)\/(\d+)$/', $filter, $match)) {
                if (self::matchIpMask($ip, $match[1], $match[2])) {
                    return true;
                }
            }
            // IPv6
            else if ($ip === $filter) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if an IP matches a CIDR mask.
     *
     * @param int|string $ip IP to check.
     * @param int|string $matchIp Radical of the mask (e.g. 192.168.0.0).
     * @param int $maskBits Size of the mask (e.g. 24).
     */
    protected static function matchIpMask($ip, $maskIp, $maskBits)
    {
        $mask = ~ ( pow(2, 32-$maskBits)-1 );
        if (!is_int($ip)) {
            $ip = ip2long($ip);
        }
        if (!is_int($maskIp)) {
            $maskIp = ip2long($maskIp);
        }
        if ( ($ip & $mask) === ($maskIp & $mask)) {
            return true;
        } else {
            return false;
        }
    }
}
