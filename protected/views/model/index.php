<table class="list" cellpadding="0" cellspacing="0" width="90%">
    <tr class="head">
        <th width="10%">昵称</th>
        <th width="15%">中文名</th>
        <th width="15%">英文名</th>
        <th>身高/体重</th>
        <th width="10%">三围</th>
        <th width="10%">所在地区</th>
        <th width="10%">签约等级</th>
        <th width="15%" align="center">操作</th>
    </tr>
    <?php foreach ($modelList as $model): ?>
    <tr>
        <td><?php echo $model->nick_name; ?></td>
        <td><?php echo $model->china_name; ?></td>
        <td><?php echo $model->english_name; ?></td>
        <td><?php echo $model->height.'/'.$model->weight; ?></td>
        <td><?php echo $model->chest.'/'.$model->waist.'/'.$model->hip; ?></td>
        <td><?php echo $model->Area->name; ?></td>
        <td><?php echo $model->level; ?></td>
        <td align="center">
            <a href="<?php echo $this->createUrl("model/edit", array('id'=>$model->id)); ?>">修改</a> |
            <a href="<?php echo $this->createUrl("model/del", array('id'=>$model->id)); ?>" onclick="return confirm('确定删除？')">删除</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>