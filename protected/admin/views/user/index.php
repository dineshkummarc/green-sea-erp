<?php $this->widget('widget.Search', array(
    'panleStyle'=>'width: 840px;',
    'searchCondition'=>array(
        '用户名：'=>array('type'=>'text', 'name'=>'params[name]', 'defaultValue'=>empty($params['name']) ? '' : $params['name'], 'alt'=>'支持模糊搜索'),
        '手机号：'=>array('type'=>'text', 'name'=>'params[phone]', 'defaultValue'=>empty($params['phone']) ? '' : $params['phone']),
        '邮箱：'=>array('type'=>'text', 'name'=>'params[mail]', 'defaultValue'=>empty($params['mail']) ? '' : $params['mail'], 'alt'=>'支持模糊搜索'),
       											 ),
    												)
); ?>
<div class="pageContent" width="100%">
	<div class="panelBar">
        <ul class="toolBar">
            <li><a class="add" href="<?php echo $this->createUrl("user/addUser"); ?>" target="dialog" width="400" height="320" title="添加一个新用户" ><span>添加用户</span></a></li>
            <li><a class="delete" href="<?php echo $this->createUrl("user/delete"); ?>" target="selectedTodo" title="删除选定用户" rel="id[]" ><span>删除选定用户</span></a></li>
        </ul>
    </div>
	<table class="table" width="100%">
		<thead>
			<tr>
				<th width="30"><input type="checkbox" class="checkboxCtrl" group="id[]" /></th>
				<th width="100">客户名</th>
				<th width="100">旺旺号</th>
				<th width="50">QQ号</th>
				<th width="50">手机号</th>
				<th width="100">淘宝网店地址</th>
				<th width="100">当前积分</th>
				<th width="100">操作</th>
			</tr>
		</thead>

		<tbody>
	        <?php if (empty($userList)): ?>
	        <tr>
	            <td colspan="10">无数据</td>
	        </tr>
	        <?php else: foreach ($userList as $user): ?>
	        <tr>
	            <td><input type="checkbox" name="id[]" value="<?php echo $user->id ?>" /></td>
	            <td><?php echo $user->name; ?></td>
	            <td><?php echo $user->wangwang; ?></td>
	            <td><?php echo $user->qq; ?></td>
	            <td><?php echo $user->mobile_phone; ?></td>
	            <td><?php echo $user->page; ?></td>
	            <td>
             		 <span id="<?php echo $user->id ?>" name="score" title="点击即可修改会员积分" url="<?php echo $this->createUrl('user/changeScore'); ?>" class="changeBtn"><?php echo $user->score; ?></span>
           			 </td>
	            <td>
					<a href="<?php echo $this->createUrl('user/delete',array('id'=>$user->id));?>" target="selectedTodo" rel="id[]">删除</a>
	                &nbsp;
	                <a href="<?php echo $this->createUrl('user/edit', array('id'=>$user->id)) ?>" target="dialog" width="350" height="320" title="修改">修改</a>
	                &nbsp;
	            </td>
	        </tr>
	        <?php endforeach; endif; ?>
        </tbody>
	</table>
	 <?php $this->widget('widget.Pager', array(
        'pages'=>$pages,
    )); ?>
</div>