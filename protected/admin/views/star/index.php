<?php $this->widget('widget.Search', array(
    'panleStyle'=>'width: 100%;',
    'searchCondition'=>array(
        '时间查询：'=>array('type'=>'text', 'class'=>'date', 'readonly'=>'readonly', 'name'=>'params[start_time]', 'defaultValue'=>empty($params['start_time']) ? '' : $params['start_time'],),
        '至：'=>array('type'=>'text', 'class'=>'date', 'readonly'=>'readonly', 'name'=>'params[end_time]', 'defaultValue'=>empty($params['end_time']) ? '' : $params['end_time'],),
        '&nbsp;'=>array('type'=>'text', 'name'=>'params[sort]', 'style'=>'display:none', 'defaultValue'=>empty($params['sort']) ? '' : $params['sort'], 'alt'=>''),
    ),
)); ?>
<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
			<li><a class="add" href="<?php echo $this->createUrl("star/edit"); ?>" target="dialog" width="400" height="500" mask="true" ><span>添加</span></a></li>
			<li class="line">line</li>
			<li><a class="delete" href="<?php echo $this->createUrl("star/del"); ?>" target="selectedTodo" rel="id[]" title="确定要删除吗?"><span>删除</span></a></li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="30"><input type="checkbox" class="checkboxCtrl" group="id[]" /></th>
				<th width="100">管理员</th>
				<th width="100">时间</th>
				<th width="100">月评</th>
				<th width="100">明星一</th>
				<th width="100">明星二</th>
				<th width="100">明星三</th>
				<th width="100">明星四</th>
				<th width="100">明星五</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php if (empty($starList)): ?>
	        <tr>
	            <td colspan="10">无数据</td>
	        </tr>
		    <?php else: foreach ($starList as $starRow): ?>
		    <tr>
		    	<td><input type="checkbox" name="id[]" value="<?php echo $starRow->id;?>" /></td>
		        <td><?php echo $starRow->Admin->name; ?></td>
		        <td><?php echo date("Y-m-d H:i:s",$starRow->time); ?></td>
		        <td><?php if($starRow->is_month==1){echo '月评';}else{echo '周评';} ?></td>
		        <td><?php echo $starRow->star1; ?></td>
		        <td><?php echo $starRow->star2; ?></td>
		        <td><?php echo $starRow->star3; ?></td>
		        <td><?php echo $starRow->star4; ?></td>
		        <td><?php echo $starRow->star5; ?></td>
		        <td align="center">
		            <a href="<?php echo $this->createUrl("star/edit", array('id'=>$starRow->id)); ?>" target="dialog" width="400" height="500" mask="true" >修改</a> |
		            <a href="<?php echo $this->createUrl("star/del", array('id'=>$starRow->id)); ?>" target="ajaxTodo" title="删除" >删除</a>
		        </td>
		    </tr>
		    <?php endforeach; endif; ?>
		</tbody>
	</table>
    <?php $this->widget('widget.Pager', array(
        'pages'=>$pages,
    )); ?>
</div>