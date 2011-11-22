<script type="text/javascript">
function status(val)
{
	var val = val.split("-");
	$.ajax({
		type: 'POST',
		url: '<?php echo $this->controller->createUrl('order/orderStatus');?>',
		data: {id: val[0], status: val[1]},
		dataType:"json",
		cache: false,
		success: DWZ.ajaxDone,
		error: DWZ.ajaxError
	});
}
</script>
<div class="panelBar">
<ul class="toolBar">
	<li><a class="icon" href="#" ><span>今日排程</span></a></li>
	<li class="line">line</li>
	<li><a class="add" href="<?php echo $this->controller->createUrl('schedule/index');?>" target="navTab" rel="schedule-index"><span>排程列表</span></a></li>
</ul>
</div>
<table class="list" width="100%" >
    <thead>
		<tr>
			<th width="30"><input type="checkbox" class="checkboxCtrl" group="id[]" /></th>
			<th width="160">订单</th>
			<th width="80">拍摄类型</th>
			<th width="100">拍摄时间</th>
			<th width="100">拍摄场地</th>
			<th width="60">摄影师</th>
			<th width="60">模特</th>
			<th width="60">造型师</th>
			<th>描述</th>
			<th width="120">拍摄状态</th>
			<th width="150">操作</th>
		</tr>
	</thead>
    <tbody>
		<?php if (empty($models)): ?>
        <tr>
        	<td></td>
            <td colspan="10">没有排程信息</td>
        </tr>
	    <?php else:  $models = (object)$models; foreach ($models as $model): ?>
	    <tr>
	    	<td><input type="checkbox" name="id[]" value="<?php echo $model->id ?>" /></td>
	        <td><?php $val = $this->getOrder($model->order_id); echo "[ ".$val['sn']." ]".$val['user_name'];?></td>
	        <td><?php if(!empty($model->shoot_type) && $model->shoot_type != 0){ echo ShootType::getShootName($model->shoot_type); }?></td>
	        <td><?php echo date("Y-m-d H:i",$model->shoot_time); ?></td>
	        <td><?php echo $model->shoot_site; ?></td>
	        <td><?php echo !empty($model->shoot_id) ? Admin::getAdminName($model->shoot_id) : '';?></td>
	        <td><?php echo !empty($model->model_id) ? Models::getModelName($model->model_id) : '';?></td>
	        <td><?php echo !empty($model->stylist_id) ? Admin::getAdminName($model->stylist_id) : '';?></td>
	        <td><?php echo !empty($model->memo) ? $model->memo : ''; ?></td>
	        <td>
	        	<select class="combox" default="<?php echo $model->order_id.'-'.$val['status']?>" name="status" change="status">
            		<?php foreach (Order::getShootStatus() as $key=>$status):?>
            		<option value="<?php echo $model->order_id.'-'.$key;?>"><?php echo $status?></option>
					<?php endforeach;?>
				</select>
	        </td>
	        <td align="center">
	        	<a href="<?php echo $this->controller->createUrl('order/storage', array('id'=>$model->order_id)); ?>" target="navTab" rel="order-storage">查看拍摄物品</a>
	        </td>
	    </tr>
	    <?php endforeach; endif; ?>
	</tbody>
</table>
