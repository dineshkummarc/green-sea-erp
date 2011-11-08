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
<div class="pageContent" width="100%" layoutH="27">
	<?php $this->widget('widget.Search', array(
	    'panleStyle'=>'width: 100%; height: 50px;',
	    'searchCondition'=>array(
	        '订单号：'=>array('type'=>'text', 'name'=>'params[sn]', 'defaultValue'=>empty($params['sn']) ? '' : $params['sn'], 'alt'=>'支持模糊搜索'),
	        '客户名：'=>array('type'=>'text', 'name'=>'params[user_name]', 'defaultValue'=>empty($params['user_name']) ? '' : $params['user_name'], 'alt'=>'支持模糊搜索'),

	        '状态：'=>array('type'=>'select', 'name'=>'params[status]', 'defaultValue'=>empty($params['status']) ? '' : $params['status'],
	            'options'=>$shootStatus
	        ),
	        '时间查询：'=>array('type'=>'text', 'class'=>'date', 'readonly'=>'readonly', 'name'=>'params[start_time]', 'defaultValue'=>empty($params['start_time']) ? '' : $params['start_time'],),
	        '至：'=>array('type'=>'text', 'class'=>'date', 'readonly'=>'readonly', 'name'=>'params[end_time]', 'defaultValue'=>empty($params['end_time']) ? '' : $params['end_time'],),

	    ),
	)); ?>
	<div class="panelBar">
		<ul class="toolBar">
			<li><a class="icon" href="<?php echo $this->createUrl("order/OrderExcel"); ?>" target="dwzExport" targetType="navTab" title="确实要导出这些记录吗?" rel="id[]"><span>导出EXCEL</span></a></li>
			<li class="line">line</li>
			<li><a class="icon" ><span style="color:#F00"><?php echo "金额：￥".$money?></span></a></li>
		</ul>
	</div>
    <table class="list" width="100%">
    	<thead>
	        <tr>
	            <th width="30"><input type="checkbox" class="checkboxCtrl" group="id[]" /></th>
	            <th width="80" >客户名</th>
	            <th width="80">旺旺号</th>
	            <th width="50">订单号</th>
	            <th width="50">合同金额</th>
	            <th width="80"><a style="line-height:20px" target="navTab" rel="order-index" href="<?php echo $this->createUrl('order/index', array('sort'=>'time'));?>">下单时间</a></th>
	            <th width="120"><a style="line-height:20px" target="navTab" rel="order-index" href="<?php echo $this->createUrl('order/index', array('sort'=>'status'));?>">当前状态</a></th>
	            <th width="40">是否跟拍</th>
	            <th width="250">操作</th>
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
	            <td>
	            	<a href="<?php echo $this->createUrl('order/goods', array('id'=>$order->id)); ?>" target="navTab" rel="order-goods">订单物品</a>
	                &nbsp;
	            	<a href="<?php echo $this->createUrl('order/ShootScene', array('id'=>$order->id)); ?>" target="dialog" mask="true" width="700" height="600" title="拍摄需求">需求</a>
	                &nbsp;
	                <?php if (Storage::getStorageBoolean($order->id) == null):?>
	                <a href="<?php echo $this->createUrl('order/storage', array('id'=>$order->id)); ?>" target="navTab" a="仓储不存在，确定要创建吗？" rel="order-storage">仓储</a>
	                <?php else:?>
	                <a href="<?php echo $this->createUrl('order/storage', array('id'=>$order->id)); ?>" target="navTab" rel="order-storage">仓储</a>
	                <?php endif;?>
	                &nbsp;
	                <a href="<?php echo $this->createUrl('order/schedule', array('id'=>$order->id)); ?>" target="navTab" rel="order-schedule">排程</a>
	                &nbsp;
	            	<a target="_blank" href="<?php echo $this->createUrl('order/print', array('id'=>$order->id)); ?>">打印</a>
	                &nbsp;
	            	<a href="<?php echo $this->createUrl('order/OrderDel', array('id'=>$order->id)); ?>" target="ajaxTodo" title="关联数据也将删除，确定删除？">删除</a>
	                &nbsp;
	            </td>
	        </tr>
	        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
<?php $this->widget('widget.Pager', array(
    'pages'=>$pages,
)); ?>
