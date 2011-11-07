<?php
return array(
    1=>"未付款",
    2=>"已付款、未收货",
    3=>"已付款、已收货、待排程",
    4=>"已付款、已收货、已排程",
    5=>"拍摄中",
    6=>"拍摄完成、待修图",
    7=>"修图中",
    8=>"修图完成、待上传",
    9=>"可下载",
    10=>"货物待寄出",
    11=>"货物已寄出",
    12=>"确认收货",
);
/*
update `ll_erp_order` set status = 12 WHERE status = 10;

update `ll_erp_order` set status = 11 WHERE status = 9;

update `ll_erp_order` set status = 10 WHERE status = 8;

update `ll_erp_order` set status = 8 WHERE status = 7;

update `ll_erp_order` set status = 7 WHERE status = 6;
*/