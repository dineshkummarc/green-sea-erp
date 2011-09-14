<table class="list" cellpadding="0" cellspacing="0" width="90%">
    <tr class="head">
        <th width="10%">客户名</th>
        <th width="15%">订单号</th>
        <th width="10%" align="center">合同金额</th>
        <th width="25%" align="center">下单时间</th>
        <th width="">当前状态</th>
        <th width="10%" align="center">是否跟拍</th>
        <th width="15%" align="center">操作</th>
    </tr>
    <?php foreach ($orderList as $order): ?>
    <tr>
        <td><?php echo $order->user_name; ?></td>
        <td><?php echo $order->sn; ?></td>
        <td><?php echo $order->total_price; ?></td>
        <td align="center"><?php echo date('Y-m-d H:i:s', $order->create_time); ?></td>
        <td><?php echo $order->getStatusText(); ?></td>
        <td align="center"><?php echo $order->following == 1 ? "是" : '否'; ?></td>
        <td align="center">
            <?php if ($order->status == 0): ?>
            <a href="<?php echo $this->createUrl("order/print", array('id'=>$order->id)); ?>">修改</a> |
            <?php endif; ?>
            <a href="<?php echo $this->createUrl("order/print", array('id'=>$order->id)); ?>" target="_blank">打印</a> |
            <a href="<?php echo $this->createUrl("order/del", array('id'=>$order->id)); ?>" onclick="return confirm('删除订单将会删除所有与之相关的信息，确定删除？')">删除</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>