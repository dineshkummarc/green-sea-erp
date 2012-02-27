<?php $this->widget('widget.Search', array(
    'panleStyle'=>'width: 100%;',
    'searchCondition'=>array(
    	'标题：'=>array('type'=>'text', 'name'=>'params[title]', 'defaultValue'=>empty($params['title']) ? '' : $params['title'], 'alt'=>'公告标题模糊搜索'),
		'内容：'=>array('type'=>'text', 'name'=>'params[content]', 'defaultValue'=>empty($params['content']) ? '' : $params['content'], 'alt'=>'公告内容模糊搜索'),
        '管理员：'=>array('type'=>'text','name'=>'params[Admin.name]','defaultValue'=>empty($params['Admin.name']) ? '' : $params['Admin.name'], 'alt'=>'管理员模糊查询'),
    ),
)); ?>
<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
			<li><a class="add" href="<?php echo $this->createUrl("notice/edit"); ?>" target="dialog" width="400" height="500" mask="true" ><span>添加</span></a></li>
			<li class="line">line</li>
			<li><a class="delete" href="<?php echo $this->createUrl("notice/del"); ?>" target="selectedTodo" rel="id[]" title="确定要删除吗?"><span>删除</span></a></li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="30"><input type="checkbox" class="checkboxCtrl" group="id[]" /></th>
				<th width="100">标题</th>
				<th width="100">管理员</th>
				<th width="100">内容</th>
				<th width="100">创建时间</th>
				<th width="100">更新时间</th>
				<th width="100">状态</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php if (empty($noticeList)): ?>
	        <tr>
	            <td colspan="10">无数据</td>
	        </tr>
		    <?php else: foreach ($noticeList as $NoticeRow): ?>
		    <tr>
		    	<td><input type="checkbox" name="id[]" value="<?php echo $NoticeRow->id;?>" /></td>
		        <td><?php echo $NoticeRow->title; ?></td>
		        <td><?php echo $NoticeRow->Admin->name; ?></td>
		        <td><?php echo $NoticeRow->content; ?></td>
		        <td><?php echo date("Y-m-d H:i:s",$NoticeRow->create_time); ?></td>
		        <td><?php echo date("Y-m-d H:i:s",$NoticeRow->update_time); ?></td>
		        <td><?php if($NoticeRow->status==1){echo '显示';}else{echo '隐藏';} ?></td>
		        <td align="center">
		            <a href="<?php echo $this->createUrl("notice/edit", array('id'=>$NoticeRow->id)); ?>" target="dialog" width="400" height="500" mask="true" >修改</a> |
		            <a href="<?php echo $this->createUrl("notice/del", array('id'=>$NoticeRow->id)); ?>" target="ajaxTodo" title="删除" >删除</a>
		        </td>
		    </tr>
		    <?php endforeach; endif; ?>
		</tbody>
	</table>
    <?php $this->widget('widget.Pager', array(
        'pages'=>$pages,
    )); ?>
</div>