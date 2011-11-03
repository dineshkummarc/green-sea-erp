<table class="list" cellpadding="0" cellspacing="0" width="90%">
    <tr class="head">
        <th width="5%">ID</th>
        <th width="10%">客户名</th>
        <th width="15%" align="center">手机号码</th>
        <th>邮箱</th>
        <th width="10%">积分</th>
        <th width="10%">所在地区</th>
        <th width="15%" align="center">操作</th>
    </tr>
    <?php foreach ($userList as $user): ?>
    <tr>
        <td><?php echo $user->id; ?></td>
        <td><?php echo $user->name; ?></td>
        <td align="center"><?php echo $user->mobile_phone; ?></td>
        <td><?php echo $user->email; ?></td>
        <td><?php echo $user->score; ?></td>
        <td><?php echo $user->Area->name; ?></td>
        <td align="center">
            <!-- <a href="<?php echo '#';//$this->createUrl("user/edit", array('id'=>$user->id)); ?>">修改</a> | -->
            <a href="<?php echo $this->createUrl("user/delUser", array('id'=>$user->id)); ?>" onclick="return confirm('确定删除？')">删除</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>