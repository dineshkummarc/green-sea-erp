<?php
return array(
    array(
        "text"=>"系统管理",
        "active"=>"order",
    	"child"=>array(
            array(
                "text"=>"订单列表",
                "active"=>"",
                "url"=>"order/index",
            ),
            array(
                'text'=>'客户列表',
                'active'=>'list',
                'url'=>'user/list'
            ),
            array(
                'text'=>'模特列表',
                'active'=>'',
                'url'=>'model/index'
            ),
            array(
                'text'=>'编辑模特',
                'active'=>'',
                'url'=>'model/edit'
            ),
            /*array(
                "text"=>"订单详情",
                "active"=>"show",
                "url"=>"order/show",
            ),*/
        ),
    ),
);