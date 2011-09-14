<?php
class Pager extends CWidget
{
    public $pages;
    public $pageSizes = array(10, 20);
    public $params = array();

    public function run()
    {
        $this->params = $this->pages->params === null ? array() : $this->pages->params;
        if ($this->pages->pageCount == 0)
            return;
        $this->render("pager", array(
            'url'=>$this->controller->id . "/" . $this->controller->action->id,
            'params'=>$this->params,
            'controller'=>$this->controller
        ));
    }
}
