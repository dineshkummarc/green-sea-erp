<div class="title"><a href="<?php echo $this->createUrl("user/editInfo") ?>">修改</a>基本资料</div>
<div class="info">
    <div>
        <label>客户名：</label><span><?php echo $info->name; ?></span>
    </div>
    <div>
        <label>旺旺号：</label><span><?php echo $info->wangwang; ?></span>
    </div>
    <div>
        <label>QQ号：</label><span><?php echo $info->qq; ?></span>
    </div>
    <div>
        <label>手机号：</label><span><?php echo $info->mobile_phone; ?></span>
    </div>
    <div>
        <label>淘宝网店地址：</label><span><?php echo $info->page; ?></span>
    </div>
    <div>
        <label>我的积分：</label><span><?php echo $info->score; ?></span> <a href="<?php echo $this->createUrl("user/score"); ?>">积分详情</a>
    </div>
</div>
<div class="title"><a href="<?php echo $this->createUrl("user/receive"); ?>">修改</a>收货信息</div>
<div class="info">
    <?php if ($info->ReceiveAddress !== null): ?>
    <div>
        <label>收货人：</label><span><?php echo $info->ReceiveAddress->receive_name; ?></span>
    </div>
    <div>
        <label>收货地址：</label><span><?php echo $info->ReceiveAddress->getFullAddress(); ?></span>
    </div>
    <div>
        <label>邮政编码：</label><span><?php echo $info->ReceiveAddress->postalcode; ?></span>
    </div>
    <div>
        <label>电话号码：</label><span><?php echo $info->ReceiveAddress->phone; ?></span>
    </div>
    <div>
        <label>手机号码：</label><span><?php echo $info->ReceiveAddress->mobile_phone; ?></span>
    </div>
    <?php else: ?>
    <div>
        无
    </div>
    <?php endif; ?>
</div>