<script type="text/javascript">
function status(val)
{
	var val = val.split("-");
	$.ajax({
		type: 'POST',
		url: '<?php echo $this->createUrl('order/orderStatus');?>',
		data: {id: val[0], status: val[1]},
		dataType:"json",
		cache: false,
		success: DWZ.ajaxDone,
		error: DWZ.ajaxError
	});
}
</script>
<div class="pageContent" width="1200px" layoutH="114">

    <table class="list" width="1200">
    	<thead>
	        <tr>
	            <th width="30"><input type="checkbox" class="checkboxCtrl" group="id[]" /></th>
	            <th width="80" >客户名</th>
	            <th width="80">旺旺号</th>
	            <th width="50">订单号</th>
	            <th width="60">合同金额</th>
	            <th width="120"><a style="line-height:20px" target="navTab" rel="order-index" href="<?php echo $this->createUrl('order/index', array('sort'=>'time'));?>">下单时间</a></th>
	            <th width="180"><a style="line-height:20px" target="navTab" rel="order-index" href="<?php echo $this->createUrl('order/index', array('sort'=>'status'));?>">当前状态</a></th>
	            <th width="60">是否跟拍</th>
	            <th width="60">运单号</th>
	            <th>操作</th>
	        </tr>
        </thead>
        <tbody>

	        <tr>
	            <td><input type="checkbox" name="id[]" value="<?php echo $order->id ?>" /></td>
	            <td><?php echo $order->user_name; ?></td>
	            <td><?php echo !empty($order->User->wangwang) ? $order->User->wangwang : ''; ?></td>
	            <td><?php echo $order->sn; ?></td>
	            <td>￥ <?php echo $order->total_price; ?></td>
	            <td><?php echo date('Y-m-d H:i:s', $order->create_time); ?></td>
	            <td>
	            	<select class="combox" default="<?php echo $order->id.'-'.$order->status?>" name="status" change="status">
	            		<?php foreach ($shootStatus as $status=>$key):?>
	            		<option value="<?php echo $order->id.'-'.$key;?>"><?php echo $status?></option>
						<?php endforeach;?>
					</select>
	            </td>
	            <td><?php echo $order->following == 1 ? "是" : '否'; ?></td>
	            <td><?php echo empty($order->logistics_sn)?'':$order->logistics_sn?></td>
	            <td>
	            	<a href="<?php echo $this->createUrl('order/goods', array('id'=>$order->id)); ?>" target="navTab" rel="order-goods">订单物品</a>
	                &nbsp;
	            	<a href="<?php echo $this->createUrl('order/ShootScene', array('id'=>$order->id)); ?>" target="dialog" mask="true" width="700" height="600" title="拍摄需求">需求</a>
	                &nbsp;
	                <?php if (Storage::getStorageBoolean($order->id) == null):?>
	                <a href="<?php echo $this->createUrl('order/storage', array('id'=>$order->id)); ?>" target="navTab" a="入库单不存在，确定要创建吗？" rel="order-storage">仓储</a>
	                <?php else:?>
	                <a href="<?php echo $this->createUrl('order/storage', array('id'=>$order->id)); ?>" target="navTab" rel="order-storage">仓储</a>
	                <?php endif;?>
	                &nbsp;
	                <a href="<?php echo $this->createUrl('schedule/index', array('orderId'=>$order->id)); ?>" target="navTab" rel="schedule-index">排程</a>
	                &nbsp;
	            	<a target="_blank" href="<?php echo $this->createUrl('order/print', array('id'=>$order->id)); ?>">打印</a>
	                &nbsp;
	            	<a href="<?php echo $this->createUrl('order/OrderDel', array('id'=>$order->id)); ?>" target="ajaxTodo" title="关联数据也将删除，确定删除？">删除</a>
	                &nbsp;
	            </td>
	        </tr>

        </tbody>
    </table>
</div>
