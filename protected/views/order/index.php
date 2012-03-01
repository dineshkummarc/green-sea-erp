<?php echo $cs1;?>
<table class="list" cellpadding="0" cellspacing="0" style="margin-bottom: 10px;">
    <tr class="head">
        <th width="20%" align="center">下单时间</th>
        <th width="8%">订单号</th>
        <th width="10%">拍摄数量</th>
        <th width="10%" align="center">合同金额</th>
        <th width="" align="center">当前状态</th>
        <th width="10%" align="center">是否跟拍</th>
        <th width="18%" align="center">操作</th>
    </tr>
    <?php foreach ($orderList as $order): ?>
    <tr>
        <td align="center"><?php echo date('Y-m-d H:i:s', $order->create_time); ?></td>
        <td align="center"><?php echo $order->sn; ?></td>
        <td align="center"><?php $counts=0;?>
            <?php foreach ($order->Goods as $Ocounts):?>
             <?php
                   $counts+=$Ocounts->count;?>
            <?php endforeach;?>
            <?php echo $counts;?>
        </td>
        <td align="center"><?php echo $order->total_price; ?></td>
        <td align="center"><?php echo $order->getStatusText(); ?></td>
        <td align="center"><?php echo $order->following == 1 ? "是" : '否'; ?></td>
        <td align="center">
            <a href="<?php echo $this->createUrl("order/view", array('id'=>$order->id)); ?>" target="_blank">查看详情</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php $this->widget('widget.Pager', array('pages'=>$pages)); ?>
