<?php
class Pager extends CBasePager
{
    public $style = null;
    public $targetType = "navTab";
    public $pageSizes = array(10, 20);
    public $searchParams = array();

    public function run()
    {
        $this->render('pager', array('pages'=>$this->pages, 'style'=>$this->style));
    }
}
?>