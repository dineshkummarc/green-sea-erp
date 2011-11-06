<?php
class Search extends CWidget
{
    public $panleStyle = '';
    public $searchSubmit = '';
    public $params = array();
    public $searchCondition = array();
    public $lineSpace = 3;

    public function run()
    {
        if (empty($this->searchCondition))
            $this->controller->error('未指定查询组件的查询条件');

        $this->render('search');
    }
}
?>