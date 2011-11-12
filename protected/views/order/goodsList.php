<div class="step active">添加拍摄物品</div>
<div class="step">填写拍摄要求</div>
<div class="step">选择付款方式</div>
<div class="step">生成订单</div>
<div class="clear"></div>
<?php  if (empty($goodsList)): ?>
<div class="title">
    您还没有添加任何拍摄物品，请<a href="<?php echo $this->createUrl("order/goodsEdit") ?>">点击这里</a>进行添加。
</div>
<?php else: ?>
<div class="title">
    您已经添加了下列拍摄物品，如果需要添加更多，请<a href="<?php echo $this->createUrl("order/goodsEdit") ?>">点击这里</a>进行添加。
</div>
<?php endif; ?>
<table id="goodsTable" class="list" cellpadding="0" cellspacing="0" width="600">
    <tr class="head">
        <th width="85">类别</th>
        <th width="40">季节</th>
        <th width="40">性别</th>
        <th width="60">拍摄类型</th>
        <th width="60">拍摄风格</th>
        <th width="60">拍摄数量</th>
        <th width="50">金额</th>
        <th width="55">操作</th>
    </tr>
    <?php $sun=0;if  (!empty($goodsList)) foreach ($goodsList as $key=>$goods): ?>
    <tr>
        <td><?php echo $goods->type_name; ?></td>
        <td><?php echo $season[$goods->season]; ?></td>
        <td><?php echo $sex[$goods->sex]; ?></td>
        <td><?php echo $shootType[$goods->shoot_type] ?></td>
        <td><?php echo $styles[$goods->style]; ?></td>
        <td><?php echo $goods->count; ?></td>
        <td class="price"><?php $goods->price; ?></td>
        <td>
            <a href="<?php echo $this->createUrl("order/goodsEdit", array("id"=>$goods->id)) ?>">修改</a>
            <a href="<?php echo $this->createUrl("order/goodsDel", array("id"=>$goods->id)) ?>" onclick="return confirm('删除之后将不能恢复，确认删除？')">删除</a>
        </td>
    </tr>
    <?php endforeach; ?>

</table>
<?php if (!empty($goodsList)): ?>
<div class="title">
    <input type="button" value="填写拍摄需求" onclick="window.location.href='<?php echo $this->createUrl("order/shootScene", array('save'=>false)); ?>'" />
</div>
<?php endif; ?>