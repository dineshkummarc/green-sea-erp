<?php if (empty($_POST['count'])): ?>
<div style="width: 500px; margin: 0 auto; padding: 40px 0;">
    <form action="<?php echo $this->createUrl("order/goodsEdit") ?>" method="post">
        您需要拍摄几种物品呢？&nbsp;
        <input type="text" name="count" class="input" />&nbsp;
        <input type="submit" value="提交" />&nbsp;
        <input type="button" value="查看已添加物品" onclick="window.location.href='<?php echo $this->createUrl("order/goodsList") ?>'" />
    </form>
</div>
<?php else: if ((int)$_POST['count'] <= 0) $_POST['count'] = 1; ?>
<div class="step active">添加拍摄物品</div>
<div class="step">填写拍摄要求</div>
<div class="step">选择付款方式</div>
<div class="step">生成订单</div>
<div class="clear"></div>
<form action="<?php echo $this->createUrl("order/goodsEdit") ?>" method="post" onsubmit="return validateGoods()">
    <table class="table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="label">拍摄类型</td>
            <td class="label">类别</td>
            <td class="label">季节</td>
            <td class="label">性别</td>
            <td class="label">拍摄风格</td>
            <td class="label">拍摄数量</td>
            <td class="label">备注</td>
        </tr>
        <?php for ($i = 0; $i < $_POST['count']; $i++): ?>
        <tr>
            <td>
                <select name="Form[<?php echo $i;?>][shoot_type]">
                    <?php foreach ($shootType as $type): ?>
                    <option value="<?php echo $type['id']; ?>" <?php if (isset($goods->shoot_type) && $goods->shoot_type == $type['id']) echo "selected"; ?>><?php echo $type['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <select id="goods-type" name="Form[<?php echo $i;?>][type]">
                    <?php foreach ($goodsType as $type): ?>
                    <option value="<?php echo $type->id ?>" <?php if (isset($goods->type) && $goods->type == $type->id) echo "selected"; ?>><?php echo $type->name; ?></option>
                    <?php endforeach; ?>
                    <option value="0">自定义</option>
                </select>
            </td>
            <td>
                <select name="Form[<?php echo $i;?>][season]">
                    <option value="0" <?php if (isset($goods->season) && $goods->season == 0) echo "selected"; ?>>不限</option>
                    <option value="1" <?php if (isset($goods->season) && $goods->season == 1) echo "selected"; ?>>春秋</option>
                    <option value="2" <?php if (isset($goods->season) && $goods->season == 2) echo "selected"; ?>>夏</option>
                    <option value="3" <?php if (isset($goods->season) && $goods->season == 3) echo "selected"; ?>>冬</option>
                </select>
            </td>
            <td>
                <select name="Form[<?php echo $i;?>][sex]">
                    <option value="0" <?php if (isset($goods->sex) && $goods->sex == 0) echo "selected"; ?>>不限</option>
                    <option value="1" <?php if (isset($goods->sex) && $goods->sex == 1) echo "selected"; ?>>男</option>
                    <option value="2" <?php if (isset($goods->sex) && $goods->sex == 2) echo "selected"; ?>>女</option>
                    <option value="3" <?php if (isset($goods->sex) && $goods->sex == 3) echo "selected"; ?>>情侣</option>
                </select>
            </td>
            <td>
                <select name="Form[<?php echo $i;?>][style]">
                    <option value="0">不限</option>
                    <?php foreach ($styles as $style): ?>
                    <option value="<?php echo $style['id']; ?>" <?php if (isset($goods->style) && $goods->style == $style['id']) echo "selected"; ?>><?php echo $style['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <input type="hidden" name="Form[<?php echo $i;?>][id]" value="<?php echo $id; ?>" />
                <input id="goods-count" type="text" name="Form[<?php echo $i;?>][count]" class="input" style="width: 60px;" tip="只能填数字" value="<?php if (isset($goods->count)) echo $goods->count; ?>" />
            </td>
            <td><textarea name="Form[<?php echo $i;?>][memo]" class="text" style="width: 150px; height: 80px;"><?php if (isset($goods->memo)) echo $goods->memo; ?></textarea></td>
        </tr>
        <?php endfor; ?>
    </table>
    <input type="button" value="返回" onclick="window.location.href='<?php echo $this->createUrl("order/goodsEdit") ?>'" />
    <input type="button" value="查看已添加物品" onclick="window.location.href='<?php echo $this->createUrl("order/goodsList") ?>'" />
    <input type="submit" value="保存" />
</form>
<?php endif; ?>