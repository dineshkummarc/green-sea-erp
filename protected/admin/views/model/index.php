<?php $this->widget('widget.Search', array(
    'panleStyle'=>'width: 100%;',
    'searchCondition'=>array(
    	'昵称：'=>array('type'=>'text', 'name'=>'name', 'defaultValue'=>empty($params['name']) ? '' : $params['name'], 'alt'=>'支持模糊搜索'),
		'类别：'=>array('type'=>'text', 'name'=>'type', 'defaultValue'=>empty($params['type']) ? '' : $params['type'], 'alt'=>'支持模糊搜索'),
		'性别：'=>array('type'=>'select', 'name'=>'params[status]', 'defaultValue'=>empty($params['status']) ? '' : $params['status'],
            'options'=>array(
                '男'=>'1',
                '女'=>'2'
            )
        ),
    ),
)); ?>
<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
			<li><a class="add" href="<?php echo $this->createUrl("model/edit"); ?>" target="dialog" width="400" height="500" mask="true" ><span>添加</span></a></li>
			<li class="line">line</li>
			<li><a class="delete" href="<?php echo $this->createUrl("model/del"); ?>" target="ajaxTodo" title="确定要删除吗?"><span>删除</span></a></li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="30"><input type="checkbox" class="checkboxCtrl" group="id[]" /></th>
				<th width="100">昵称</th>
				<th width="100">中文</th>
				<th width="100">英文</th>
				<th width="100">身高/体重</th>
				<th width="100">三围</th>
				<th width="100">所在地区</th>
				<th width="50">签约等级</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php if (empty($modelList)): ?>
	        <tr>
	            <td colspan="10">无数据</td>
	        </tr>
		    <?php else: foreach ($modelList as $model): ?>
		    <tr>
		    	<td><input type="checkbox" name="id[]" value="<?php echo $model->id ?>" /></td>
		        <td><?php echo $model->nick_name; ?></td>
		        <td><?php echo $model->china_name; ?></td>
		        <td><?php echo $model->english_name; ?></td>
		        <td><?php echo $model->height.'/'.$model->weight; ?></td>
		        <td><?php echo $model->chest.'/'.$model->waist.'/'.$model->hip; ?></td>
		        <td><?php echo $model->Area->name; ?></td>
		        <td><?php echo $model->level; ?></td>
		        <td align="center">
		            <a href="<?php echo $this->createUrl("model/edit", array('id'=>$model->id)); ?>" target="dialog" width="400" height="500" mask="true" >修改</a> |
		            <a href="<?php echo $this->createUrl("model/del", array('id'=>$model->id)); ?>" target="ajaxTodo" title="删除" >删除</a>
		        </td>
		    </tr>
		    <?php endforeach; endif; ?>
		</tbody>
	</table>
    <?php $this->widget('widget.Pager', array(
        'pages'=>$pages,
    )); ?>
</div>