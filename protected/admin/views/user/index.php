<?php $this->widget('widget.Search', array(
    'searchCondition'=>array(
		'用户编号'=>array('type'=>'text','name'=>'params[id]', 'defaultValue'=>empty($params['id'])? '' : $params['id']),
        '用户名：'=>array('type'=>'text', 'name'=>'params[name]', 'defaultValue'=>empty($params['name']) ? '' : $params['name'], 'alt'=>'支持模糊搜索'),
        '手机号：'=>array('type'=>'text', 'name'=>'params[phone]', 'defaultValue'=>empty($params['phone']) ? '' : $params['phone']),
        '邮箱：'=>array('type'=>'text', 'name'=>'params[mail]', 'defaultValue'=>empty($params['mail']) ? '' : $params['mail'], 'alt'=>'支持模糊搜索'),
        ),
	)
); ?>
<div class="pageContent  width="100%"  layoutH="89">
    <div class="panelBar" >
        <ul class="toolBar">
           <li><a class="add" href="<?php echo $this->createUrl("user/edit") ?>" target="dialog" width="630" height="320" mask="true" title="添加用户"><span>添加用户</span></a></li>
           <li><a class="delete" href="<?php echo $this->createUrl("user/del"); ?>" target="selectedTodo" title="优惠券也会删除，确定删除选定数据吗？" rel="id[]" ><span>删除选定</span></a></li>
        </ul>
    </div>
    <table class="table" width="100%" >
    <thead>
        <tr>
            <th width="20"><input type="checkbox" class="checkboxCtrl" group="id[]" /></th>
            <th>用户编号</th>
            <th>用户名</th>
            <th>旺旺号</th>
            <th >QQ号</th>
            <th >手机号码</th>
            <th width="220">淘宝网地址</th>
            <th >剩余积分</th>
            <th >详细信息</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($userList)) foreach ($userList as $user): ?>
        <tr>
            <td><input type="checkbox" name="id[]" value="<?php echo $user->id ?>" /></td>
            <td>P<?php echo substr(strval($user->id + 1000),1,3); ?></td>
            <td><?php echo $user->name; ?></td>
            <td><?php echo $user->wangwang; ?></td>
            <td><?php echo $user->qq; ?></td>
            <td><?php echo $user->mobile_phone?></td>
            <td><?php echo $user->page; ?></td>
           <td>
               <span id="<?php echo $user->id ?>" name="score" title="点击即可修改会员积分" url="<?php echo $this->createUrl('user/changeScore'); ?>" class="changeBtn"><?php echo $user->score; ?></span>
            </td>
            <td><?php echo !empty ($user->ReceiveAddress->receive_name)?$user->ReceiveAddress->receive_name:null ?>,
					<?php echo !empty ($user->ReceiveAddress->phone)?$user->ReceiveAddress->phone:null ?>,

					<?php echo !empty($user->ReceiveAddress->area_id) ? $area['sheng'][ $area['shi'][ $area['qu'][$user->ReceiveAddress->area_id]['parent_id'] ]['parent_id'] ]['name'] : null;?>,
					<?php echo !empty($user->ReceiveAddress->area_id) ? $area['shi'][ $area['qu'][$user->ReceiveAddress->area_id]['parent_id'] ]['name'] : null?>,
					<?php echo !empty($user->ReceiveAddress->area_id) ? $area['qu'] [$user->ReceiveAddress->area_id] ['name']:null?>
					<?php echo !empty ($user->ReceiveAddress->street)?$user->ReceiveAddress->street:null ?>
            </td>
            <td>
                <a href="<?php echo $this->createUrl('user/edit', array('id'=>$user->id)) ?>" target="dialog" width="630" height="350" mask="true" title="修改">修改</a>
                <a href="<?php echo $this->createUrl('user/del', array('id'=>$user->id)); ?>" target="ajaxTodo" title="相对应的数据也将删除，确认删除？">删除</a>
            </td>
        </tr>
        </tbody>
        <?php endforeach; ?>
    </table>
</div>
<?php $this->widget('widget.Pager', array(
    'pages'=>$pages,
)); ?>
