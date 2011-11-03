<div style="margin-bottom: 10px; margin-left: 10px;">
    <form action="" method="get">
        <input type="hidden" name="r" value="<?php echo $this->id.'/'.$this->action->id; ?>" />
        物流单号：<input type="text" name="logistics" value="<?php echo isset($_GET['logistics']) ? $_GET['logistics'] : '' ?>" />
        <input type="submit" value="查询" />
    </form>
</div>
<table class="list" cellpadding="0" cellspacing="0" style="margin-bottom: 10px;">
    <tr class="head">
        <th width="10%">客户名</th>
        <th width="10%">旺旺号</th>
        <th width="8%">订单号</th>
        <th width="10%" align="center">合同金额</th>
        <th width="20%" align="center"><a href="<?php echo $this->createUrl('', array('sort'=>'time')); ?>" title="按下单时间排序">下单时间</a></th>
        <th width=""><a href="<?php echo $this->createUrl('', array('sort'=>'status')); ?>" title="按状态排序">当前状态</a></th>
        <th width="10%" align="center">是否跟拍</th>
        <th width="18%" align="center">操作</th>
    </tr>
    <?php foreach ($orderList as $order): ?>
    <tr>
        <td><?php echo $order->user_name; ?></td>
        <td><?php echo $order->User->wangwang; ?></td>
        <td><?php echo $order->sn; ?></td>
        <td><?php echo $order->total_price; ?></td>
        <td align="center"><?php echo date('Y-m-d H:i:s', $order->create_time); ?></td>
        <td><?php echo $order->getStatusText(); ?></td>
        <td align="center"><?php echo $order->following == 1 ? "是" : '否'; ?></td>
        <td align="center">
            <a href="<?php echo $this->createUrl("order/editShootScene", array('id'=>$order->id)); ?>">修改</a> |
            <a href="<?php echo $this->createUrl("order/print", array('id'=>$order->id)); ?>" target="_blank">打印</a> |
            <a href="<?php echo $this->createUrl("order/del", array('id'=>$order->id)); ?>" onclick="return confirm('删除订单将会删除所有与之相关的信息，确定删除？')">删除</a>
            <?php if ($order->status == 1): ?>
            | <a href="<?php echo $this->createUrl('order/changeStatus', array('id'=>$order->id, 'status'=>2)) ?>">收款</a>
            <?php elseif ($order->status == 2): ?>
            | <a href="<?php echo $this->createUrl('order/changeStatus', array('id'=>$order->id, 'status'=>3)) ?>">入库</a>
            <?php elseif ($order->status == 3): ?>
            | <a href="<?php echo $this->createUrl('order/changeStatus', array('id'=>$order->id, 'status'=>4)) ?>">排程</a>
            <?php elseif ($order->status == 4): ?>
            | <a href="<?php echo $this->createUrl('order/changeStatus', array('id'=>$order->id, 'status'=>5)) ?>">拍摄</a>
            <?php elseif ($order->status == 5): ?>
            | <a href="<?php echo $this->createUrl('order/changeStatus', array('id'=>$order->id, 'status'=>6)) ?>">修图</a>
            | <a href="<?php echo $this->createUrl('order/changeStatus', array('id'=>$order->id, 'status'=>5)) ?>">重拍</a>
            <?php elseif ($order->status == 6): ?>
            | <a href="<?php echo $this->createUrl('order/changeStatus', array('id'=>$order->id, 'status'=>7)) ?>">下载</a>
            <?php elseif ($order->status == 7): ?>
            | <a href="<?php echo $this->createUrl('order/changeStatus', array('id'=>$order->id, 'status'=>8)) ?>">出库</a>
            <?php elseif ($order->status == 8): ?>
            | <a href="<?php echo $this->createUrl('order/changeStatus', array('id'=>$order->id, 'status'=>9)) ?>">寄出</a>
            <?php elseif ($order->status == 9): ?>
            | <a href="<?php echo $this->createUrl('order/changeStatus', array('id'=>$order->id, 'status'=>10)) ?>">客户确认</a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php $this->widget('widget.Pager', array('pages'=>$pages, 'params'=>$params)); ?>