<?php if(isset($typeList)): $this->widget('widget.Search', array(
    'panleStyle'=>'width: 100%; height: 50px;',
    'searchCondition'=>array(
		'订单号：'=>array('type'=>'text', 'name'=>'params[sn]', 'defaultValue'=>empty($params['sn']) ? '' : $params['sn'], 'alt'=>'支持模糊搜索'),
		'客户名：'=>array('type'=>'text', 'name'=>'params[user_name]', 'defaultValue'=>empty($params['user_name']) ? '' : $params['user_name'], 'alt'=>'精确搜索'),
        '摄影师：'=>array('type'=>'text', 'class'=>'text', 'name'=>'params[shoot]', 'defaultValue'=>empty($params['shoot']) ? '' : $params['shoot']),
	    '造型师：'=>array('type'=>'text', 'class'=>'text', 'name'=>'params[stylist]', 'defaultValue'=>empty($params['stylist']) ? '' : $params['stylist']),
		'拍摄类型：'=>array('type'=>'select', 'name'=>'params[shoot_type]', 'defaultValue'=>empty($params['shoot_type']) ? '' : $params['shoot_type'],
		'options'=>array(
				'模特棚拍' => '1',
				'模特外拍' => '2',
				'平铺拍摄' => '3',
				'衣模拍摄' => '4',
			),
		),
        '拍摄时间：'=>array('type'=>'text', 'class'=>'date', 'readonly'=>'readonly', 'name'=>'params[start_time]', 'defaultValue'=>empty($params['start_time']) ? '' : $params['start_time'],),
	    '至结束时间：'=>array('type'=>'text', 'class'=>'date', 'readonly'=>'readonly', 'name'=>'params[end_time]', 'defaultValue'=>empty($params['end_time']) ? '' : $params['end_time'],),
    ),
)); endif;?>
<div class="panelBar">
<ul class="toolBar">
	<li><a class="add" href="<?php echo $this->createUrl("schedule/edit", array('orderId'=>$orderId)); ?>" target="dialog" width="400" height="500" mask="true" ><span>添加排程</span></a></li>
	<li class="line">line</li>
	<li><a class="icon" href="<?php echo $this->createUrl("schedule/wait", array('status'=>3));?>" target="navTab" ><span>未排程订单</span></a></li>
	<li class="line">line</li>
	</ul>
</div>
	<table class="list" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="30"><input type="checkbox" class="checkboxCtrl" group="id[]" /></th>
				<th width="150">订单</th>
				<th width="80">拍摄类型</th>
				<th width="120">入库时间</th>
				<th width="120">拍摄时间</th>
				<th width="100">拍摄场地</th>
				<th width="80">摄影师</th>
				<th width="60">模特</th>
				<th width="60">造型师</th>
				<th >描述</th>
				<th width="150">操作</th>
			</tr>
		</thead>
		<tbody>
		<?php if (empty($models)): ?>
        <tr>
            <td colspan="9">订单未排程</td>
        </tr>
	    <?php else:  $models = (object)$models; foreach ($models as $model): ?>
	    <tr>
	    	<td><input type="checkbox" name="id[]" value="<?php echo $model->id ?>" /></td>
	        <td> <a href="<?php echo $this->createUrl('order/index', array('id'=>$model->order_id)); ?>" target="navTab"><?php foreach ($this->getOrder($model->order_id) as $val) { echo "[ ".$val['sn']." ]".$val['user_name']; }?></a></td>
	        <td><?php if(!empty($model->shoot_type) && $model->shoot_type != 0){ echo ShootType::getShootName($model->shoot_type); }?></td>
	        <td><?php $in_time = $this->getStorage($model->order_id); echo !empty($in_time) ? date("Y-m-d H:i",$in_time) : '订单未入库'; ?></td>
	        <td><?php echo date("Y-m-d H:i",$model->shoot_time); ?></td>
	        <td><?php echo $model->shoot_site; ?></td>
	        <td><?php echo !empty($model->shoot_id) ? Admin::getAdminName($model->shoot_id) : '';?></td>
	        <td><?php echo !empty($model->model_id) ? Models::getModelName($model->model_id) : '';?></td>
	        <td><?php echo !empty($model->stylist_id) ? Admin::getAdminName($model->stylist_id) : '';?></td>
	        <td><?php echo !empty($model->memo) ? $model->memo : ''; ?></td>
	        <td align="center">
	        	<a href="<?php echo $this->createUrl('schedule/index', array('orderId'=>$model->order_id)); ?>" target="navTab" rel="schedule-index">查看排程</a> |
	            <a href="<?php echo $this->createUrl("schedule/edit", array('id'=>$model->id, 'orderId'=>$model->order_id)); ?>" target="dialog" width="400" height="500" mask="true" >修改</a> |
	            <a href="<?php echo $this->createUrl("schedule/del", array('id'=>$model->id)); ?>" target="ajaxTodo" title="删除" >删除</a>
	        </td>
	    </tr>
	    <?php endforeach; endif; ?>
	</tbody>
</table>
<?php if(isset($pages)): $this->widget('widget.Pager', array(
        'pages'=>$pages,
    )); endif;?>


