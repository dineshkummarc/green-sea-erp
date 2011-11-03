<?php
class SideBar extends CWidget
{
    public function run()
    {
        $controller = $this->controller;
        $action = $controller->action;
        if (Yii::app()->user->id == 999)
            $menuList = require(dirname(__FILE__)."/../config/adminMenu.php");
        else
            $menuList = Yii::app()->params['menu'];
        $this->render('sidebar', array(
            'menuList'=>$menuList,
        	'controller'=>$controller,
        	'action'=>$action
        ));
    }
}
?>