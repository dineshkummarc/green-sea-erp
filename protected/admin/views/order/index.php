<div class="pageContent" width="100%">
    <table class="table" width="100%">
    	<thead>
	        <tr>
	            <th width="30"><input type="checkbox" class="checkboxCtrl" group="id[]" /></th>
	            <th width="100">客户名</th>
	            <th width="100">旺旺号</th>
	            <th width="50">订单号</th>
	            <th width="50">合同金额</th>
	            <th width="80">下单时间</th>
	            <th width="100">当前状态</th>
	            <th width="50">是否跟拍</th>
	            <th width="200">操作</th>
	        </tr>
        </thead>
        <tbody>
	        <?php if (empty($orders)): ?>
	        <tr>
	            <td colspan="10">无数据</td>
	        </tr>
	        <?php else: foreach ($orders as $order): ?>
	        <tr>
	            <td><input type="checkbox" name="id[]" value="<?php echo $order->id ?>" /></td>
	            <td><?php echo $order->user_name; ?></td>
	            <td><?php echo $order->User->wangwang; ?></td>
	            <td><?php echo $order->sn; ?></td>
	            <td><?php echo $order->total_price; ?></td>
	            <td><?php echo date('Y-m-d H:i:s', $order->create_time); ?></td>
	            <td><?php echo $order->getStatusText(); ?></td>
	            <td><?php echo $order->following == 1 ? "是" : '否'; ?></td>
	            <td>
	            	<a href="<?php echo $this->createUrl('order/goods', array('id'=>$order->id)); ?>" target="navTab" rel="order-goods">订单物品</a>
	                &nbsp;
	            </td>
	        </tr>
	        <?php endforeach; endif; ?>
        </tbody>
    </table>
    <?php $this->widget('widget.Pager', array(
        'pages'=>$pages,
    )); ?>
</div>