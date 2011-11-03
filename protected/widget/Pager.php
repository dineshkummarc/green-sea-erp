<?php
class Pager extends CWidget
{
    public $pages;
    public $pageSizes = array(20, 10);
    public $params;

    public function run()
    {
        if ($this->pages->pageCount == 0)
            return;
        $this->render("pager");
    }
}
