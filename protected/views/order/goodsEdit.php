<?php if (empty($_POST['count'])): ?>
<div style="width: 500px; margin: 0 auto; padding: 40px 0;">
    <form action="<?php echo $this->createUrl("order/goodsEdit") ?>" method="post">
        您需要拍摄几种物品呢？&nbsp;
        <input type="text" name="count" class="input" />&nbsp;
        <input type="submit" value="提交" />
    </form>
</div>
<?php else: ?>
<div class="step active">添加拍摄物品</div>
<div class="step">填写拍摄要求</div>
<div class="step">选择付款方式</div>
<div class="step">生成订单</div>
<div class="clear"></div>
<form action="<?php echo $this->createUrl("order/goodsEdit") ?>" method="post" onsubmit="return validateGoods()">
    <table class="table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="label">类别</td>
            <td class="label">季节</td>
            <td class="label">性别</td>
            <td class="label">拍摄类型</td>
            <td class="label">拍摄风格</td>
            <td class="label">拍摄数量</td>
            <td class="label">备注</td>
        </tr>
        <?php for ($i = 0; $i < $_POST['count']; $i++): ?>
        <tr>
            <td>
                <select id="goods-type" name="Form[<?php echo $i;?>][type]">
                    <?php foreach ($goodsType as $type): ?>
                    <option value="<?php echo $type->id ?>"><?php echo $type->name; ?></option>
                    <?php endforeach; ?>
                    <option value="0">自定义</option>
                </select>
            </td>
            <td>
                <select name="Form[<?php echo $i;?>][season]">
                    <option value="0">不限</option>
                    <option value="1">春秋</option>
                    <option value="2">夏</option>
                    <option value="3">冬</option>
                </select>
            </td>
            <td>
                <select name="Form[<?php echo $i;?>][sex]">
                    <option value="0">不限</option>
                    <option value="1">男</option>
                    <option value="2">女</option>
                    <option value="3">情侣</option>
                </select>
            </td>
            <td>
                <select name="Form[<?php echo $i;?>][shoot_type]">
                    <?php foreach ($shootType as $type): ?>
                    <option value="<?php echo $type['id']; ?>"><?php echo $type['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <select name="Form[<?php echo $i;?>][style]">
                    <option value="0">不限</option>
                    <?php foreach ($styles as $style): ?>
                    <option value="<?php echo $style['id']; ?>"><?php echo $style['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <input id="goods-count" type="text" name="Form[<?php echo $i;?>][count]" class="input" style="width: 100px;" tip="只能填数字" />
            </td>
            <td><textarea name="Form[<?php echo $i;?>][memo]" class="text" style="width: 150px; height: 80px;"></textarea></td>
        </tr>
        <?php endfor; ?>
    </table>
    <input type="button" value="返回" onclick="window.location.href='<?php echo $this->createUrl("order/goodsEdit") ?>'" />
    <input type="submit" value="保存" />
</form>
<?php endif; ?>