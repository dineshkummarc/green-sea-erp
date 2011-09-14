<?php
class SideBar extends CWidget
{
    public function run()
    {
        $controller = $this->controller;
        $action = $controller->action;
        $this->render('sidebar', array(
            'menuList'=>Yii::app()->params['menu'],
        	'controller'=>$controller,
        	'action'=>$action
        ));
    }
}
?>