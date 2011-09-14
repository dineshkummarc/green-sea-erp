<div class="nav">
    <div class="left"></div>
    <ul>
        <li><a href="<?php echo $this->controller->createUrl("user/info"); ?>" <?php if($this->controller->id == "user") echo 'class="active"'; ?>>客户管理</a></li>
        <li ><a href="<?php echo $this->controller->createUrl("order/index"); ?>" <?php if($this->controller->id == "order") echo 'class="active"'; ?>>订单管理</a></li>
        <!-- <li><a href="#">会员优惠</a></li> -->
        <li class="link">&nbsp;</li>
    </ul>
    <div class="right"></div>
</div>