<?php if(isset($typeList)): $this->widget('widget.Search', array(
    'panleStyle'=>'width: 100%; height: 25px;',
    'searchCondition'=>array(
        '拍摄时间：'=>array('type'=>'text', 'class'=>'date', 'readonly'=>'readonly', 'name'=>'params[start_time]', 'defaultValue'=>empty($params['start_time']) ? '' : $params['start_time'],),
	    '至结束时间：'=>array('type'=>'text', 'class'=>'date', 'readonly'=>'readonly', 'name'=>'params[end_time]', 'defaultValue'=>empty($params['end_time']) ? '' : $params['end_time'],),
		'拍摄类型：'=>array('type'=>'select', 'name'=>'params[shoot_type]', 'defaultValue'=>empty($params['shoot_type']) ? '' : $params['shoot_type'],
		'options'=>array(
				'请选择' => '0',
				'模特棚拍' => '1',
				'模特外拍' => '2',
				'平铺拍摄' => '3',
				'衣模拍摄' => '4',
			),
		),
    ),
)); endif;?>
<div class="panelBar">
<ul class="toolBar">
	<li><a class="add" href="<?php echo $this->createUrl("schedule/edit", array('orderId'=>$orderId)); ?>" target="dialog" width="400" height="500" mask="true" ><span>添加排程</span></a></li>
	<li class="line">line</li>
	<li><a class="icon" href="<?php echo $this->createUrl("schedule/wait", array('status'=>3));?>" target="navTab" ><span>未排程订单</span></a></li>
	</ul>
</div>
	<table class="list" width="100%" layoutH="115">
		<thead>
			<tr>
				<th width="30"><input type="checkbox" class="checkboxCtrl" group="id[]" /></th>
				<th width="150">订单</th>
				<th width="80">拍摄类型</th>
				<th width="120">拍摄时间</th>
				<th width="100">拍摄场地</th>
				<th width="80">摄影师</th>
				<th width="60">模特</th>
				<th width="60">造型师</th>
				<th>描述</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php if (empty($models) && !isset($models)): ?>
        <tr>
            <td colspan="9">订单未排程</td>
        </tr>
	    <?php else:  $models = (object)$models; foreach ($models as $model): ?>
	    <tr>
	    	<td><input type="checkbox" name="id[]" value="<?php echo $model->id ?>" /></td>
	        <td><?php foreach ($this->getOrder($model->order_id) as $val) { echo "[ ".$val['sn']." ]".$val['user_name']; }?></td>
	        <td><?php echo ShootType::getShootName($model->shoot_type); ?></td>
	        <td><?php echo date("Y-m-d H:i",$model->shoot_time); ?></td>
	        <td><?php echo $model->shoot_site; ?></td>
	        <td><?php echo !empty($model->shoot_id) ? Admin::getAdminName($model->shoot_id) : '';?></td>
	        <td><?php echo !empty($model->model_id) ? Models::getModelName($model->model_id) : '';?></td>
	        <td><?php echo !empty($model->stylist_id) ? Admin::getAdminName($model->stylist_id) : '';?></td>
	        <td><?php echo !empty($model->memo) ? $model->memo : ''; ?></td>
	        <td align="center">
	        	<a href="<?php echo $this->createUrl('schedule/index', array('orderId'=>$model->order_id)); ?>" target="navTab" rel="schedule-index">查看</a> |
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


