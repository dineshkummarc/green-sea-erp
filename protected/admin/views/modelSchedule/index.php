<?php $this->widget('widget.Search', array(
    'panleStyle'=>'width: 100%;',
    'searchCondition'=>array(
        '模特昵称：'=>array('type'=>'select', 'name'=>'params[model_id]', 'defaultValue'=>empty($params['model_id']) ? '' : $params['model_id'],
                    'options'=>$nick_names,),
        '是否有档期：'=>array('type'=>'select', 'name'=>'params[scheduled]', 'defaultValue'=>empty($params['scheduled']) ? '' : $params['scheduled'],
                    'options'=>array('有档期'=>1),),
    ),
)); ?>
<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
			<li><a class="add" href="<?php echo $this->createUrl("modelschedule/edit"); ?>" target="dialog" width="400" height="500" mask="true" ><span>添加</span></a></li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="100">档期安排</th>
				<th width="100">模特</th>
				<th width="100">是否有档期</th>
				<th width="100">最大拍摄数量</th>
				<th width="100">最小拍摄数量</th>
				<th width="100">当前数量</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php if (empty($modelscheduleList)): ?>
	        <tr>
	            <td colspan="10">无数据</td>
	        </tr>
		    <?php else: foreach ($modelscheduleList as $modelscheduleRow): ?>
		    <tr>
		        <td><?php echo $modelscheduleRow->date; ?></td>
		        <td><?php echo $modelscheduleRow->Model->nick_name; ?></td>
		        <td><?php if($modelscheduleRow->scheduled == 1){echo '有档期';}else{echo '无档期  有请修改';}?></td>
		        <td><?php echo $modelscheduleRow->max_count; ?></td>
		        <td><?php echo $modelscheduleRow->min_count; ?></td>
		        <td><?php echo $modelscheduleRow->cur_count; ?></td>
		        <td align="center">
		            <a href="<?php echo $this->createUrl("modelschedule/edit", array('date'=>$modelscheduleRow->date,'model_id'=>$modelscheduleRow->model_id)); ?>" target="dialog" width="400" height="500" mask="true" >修改</a>
		        </td>
		    </tr>
		    <?php endforeach; endif; ?>
		</tbody>
	</table>
    <?php $this->widget('widget.Pager', array(
        'pages'=>$pages,
    )); ?>
</div>