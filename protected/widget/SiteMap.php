<?php
class SiteMap extends CWidget
{
    public function run()
    {
        $siteMap = Yii::app()->params['sitemap'];
        $this->render('sitemap', array(
            'data'=>$siteMap,
        	'controller'=>$this->controller,
        	'action'=>$this->controller->action
        ));
    }
}
?>