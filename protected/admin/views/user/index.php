<?php $this->widget('widget.Search', array(
    'panleStyle'=>'width: 840px;',
    'searchCondition'=>array(
        '用户名：'=>array('type'=>'text', 'name'=>'params[name]', 'defaultValue'=>empty($params['name']) ? '' : $params['name'], 'alt'=>'支持模糊搜索'),
        '手机号：'=>array('type'=>'text', 'name'=>'params[phone]', 'defaultValue'=>empty($params['phone']) ? '' : $params['phone']),
        '邮箱：'=>array('type'=>'text', 'name'=>'params[mail]', 'defaultValue'=>empty($params['mail']) ? '' : $params['mail'], 'alt'=>'支持模糊搜索'),
       											 ),
    																	)
); ?>
<div class="pageContent">
    <div class="panelBar" style="width: 850px">
        <ul class="toolBar">
            <li><a class="delete" href="<?php echo $this->createUrl("user/del"); ?>" target="selectedTodo" title="优惠券也会删除，确定删除选定数据吗？" rel="id[]" ><span>删除选定</span></a></li>
           <li><a class="add" href="<?php echo $this->createUrl("user/addUser") ?>" target="dialog" width="520" height="400" mask="true" title="添加用户"><span>添加用户</span></a></li>
        </ul>
    </div>
    <table class="list" width="850">
        <tr>
            <th width="20"><input type="checkbox" class="checkboxCtrl" group="id[]" /></th>
            <th>用户名</th>
            <th>旺旺号</th>
            <th width="80">QQ号</th>
            <th width="115">手机号码</th>
            <th width="200">淘宝网地址</th>
            <th width="65">剩余积分</th>
            <th>操作</th>
        </tr>
        <?php if (!empty($userList)) foreach ($userList as $user): ?>
        <tr>
            <td><input type="checkbox" name="id[]" value="<?php echo $user->id ?>" /></td>
            <td><?php echo $user->name; ?></td>
            <td><?php echo $user->wangwang; ?></td>
            <td><?php echo $user->qq; ?></td>
            <td><?php echo $user->mobile_phone?></td>
            <td><?php echo $user->page; ?></td>
           <td>
               <span id="<?php echo $user->id ?>" name="score" title="点击即可修改会员积分" url="<?php echo $this->createUrl('user/changeScore'); ?>" class="changeBtn"><?php echo $user->score; ?></span>
            </td>
            <td>
                <a href="<?php echo $this->createUrl('user/edit', array('id'=>$user->id)) ?>" target="dialog" width="350" height="320" title="修改">修改</a>
                &nbsp;
                <a href="<?php echo $this->createUrl('user/del', array('id'=>$user->id)); ?>" target="ajaxTodo" title="相对应的数据也将删除，确认删除？">删除</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php $this->widget('widget.Pager', array(
        'pages'=>$pages,
        'style'=>'width: 850px',
    )); ?>
</div>