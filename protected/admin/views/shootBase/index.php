<?php $this->widget('widget.Search', array(
    'panleStyle'=>'width: 100%;',
    'searchCondition'=>array(
    	'拍摄基地：'=>array('type'=>'text', 'name'=>'params[name]', 'defaultValue'=>empty($params['name']) ? '' : $params['name'], 'alt'=>'城市模糊搜索'),
    ),
)); ?>
<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
			<li><a class="add" href="<?php echo $this->createUrl("shootbase/edit"); ?>" target="dialog" width="400" height="500" mask="true" ><span>添加</span></a></li>
			<li class="line">line</li>
			<li><a class="delete" href="<?php echo $this->createUrl("shootbase/del"); ?>" target="selectedTodo" rel="id[]" title="确定要删除吗?"><span>删除</span></a></li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="30"><input type="checkbox" class="checkboxCtrl" group="id[]" /></th>
				<th width="100">基地</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php if (empty($shootbaselist)): ?>
	        <tr>
	            <td colspan="10">无数据</td>
	        </tr>
		    <?php else: foreach ($shootbaselist as $shootbaserow): ?>
		    <tr>
		    	<td><input type="checkbox" name="id[]" value="<?php echo $shootbaserow->id;?>" /></td>
		        <td><?php echo $shootbaserow->name; ?></td>
		        <td align="center">
		            <a href="<?php echo $this->createUrl("shootbase/edit", array('id'=>$shootbaserow->id)); ?>" target="dialog" width="400" height="500" mask="true" >修改</a> |
		            <a href="<?php echo $this->createUrl("shootbase/del", array('id'=>$shootbaserow->id)); ?>" target="ajaxTodo" title="删除" >删除</a>
		        </td>
		    </tr>
		    <?php endforeach; endif; ?>
		</tbody>
	</table>
    <?php $this->widget('widget.Pager', array(
        'pages'=>$pages,
    )); ?>
</div>