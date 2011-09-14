<?php
class ShootNotice extends CWidget
{
    public function run()
    {
        $shootNotice = $this->controller->getShootNotice();
        $this->render('shootnotice/index', array('shootNotice'=>$shootNotice));
    }
}
?>