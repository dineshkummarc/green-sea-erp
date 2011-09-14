<?php
class SiteMap extends CWidget
{
    public function run()
    {
        $this->render('sitemap', array(
            'data'=>Yii::app()->params['sitemap'],
        	'controller'=>$this->controller,
        	'action'=>$this->controller->action
        ));
    }
}
?>