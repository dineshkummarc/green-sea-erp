<div class="user-info">
    <div><a href="#">您好，<?php echo $user_info->name; ?>！</a><a href="<?php echo $this->controller->createUrl('user/logout') ?>">[退出]</a></div>
    <div>客户编号：P<?php echo substr(strval($user_info->id + 1000),1,3); ?></div>
    <div>积分：<?php echo $user_info->score; ?></div>
    <div>联系绿浪客服：400-000-9411</div>
</div>