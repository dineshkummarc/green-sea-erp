<div class="pageContent">
    <div class="panelBar">
        <ul class="toolBar">
            <li><a class="add" href="<?php echo $this->createUrl("auth/editRole") ?>" target="dialog" width="300" height="180" resizable="false" maxable="false" mask="true" title="添加权限组"><span>添加权限组</span></a></li>
            <li><a class="delete" href="<?php echo $this->createUrl("auth/delRole"); ?>" target="selectedTodo" title="确定删除选定数据吗？" rel="id[]" ><span>删除选定</span></a></li>
            <li><a class="icon" href="<?php echo $this->createUrl("auth/admin") ?>" target="navTab" rel="auth-admin"><span>管理员列表</span></a></li>
        </ul>
    </div>
    <table class="table" width="100%">
        <thead>
            <tr>
                <th width="30"><input type="checkbox" class="checkboxCtrl" group="id[]" /></th>
                <th>权限组</th>
                <th width="80">管理员数量</th>
                <th width="80">状态</th>
                <th width="200">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($roles as $role) : ?>
            <tr>
                <td><input type="checkbox" name="id[]" value="<?php echo $role->id; ?>" /></td>
                <td><?php echo $role->name; ?></td>
                <td><?php echo $role->getAdminCount(); ?></td>
                <td>
                    <?php if ($role->status): ?>
                    启用&nbsp;&nbsp;&nbsp;
                    <a href="<?php echo $this->createUrl("auth/toggleStatus", array('id'=>$role->id, 'status'=>'0')); ?>" target="ajaxTodo">停用</a>
                    <?php else: ?>
                    <a href="<?php echo $this->createUrl("auth/toggleStatus", array('id'=>$role->id, 'status'=>'1')); ?>" target="ajaxTodo">启用</a>
                    &nbsp;&nbsp;&nbsp;停用
                    <?php endif; ?>
                </td>
                <td>
                    <a href="<?php echo $this->createUrl("auth/delRole", array('id'=>$role->id)); ?>" target="ajaxTodo" title="删除权限组将会导致其下属管理员删除，是否删除">删除</a>
                    <a href="<?php echo $this->createUrl("auth/editRole", array('id'=>$role->id)); ?>" target="dialog" width="300" height="180" resizable="false" maxable="false" mask="true" title="修改权限组">修改</a>
                    <a href="<?php echo $this->createUrl("auth/item", array('id'=>$role->id)); ?>" target="navTab" rel="auth-role-config">权限配置</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
