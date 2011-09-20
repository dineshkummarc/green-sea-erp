<div class="step active">添加拍摄物品</div>
<div class="step">填写拍摄要求</div>
<div class="step">选择付款方式</div>
<div class="step">生成订单</div>
<div class="clear"></div>
<form action="<?php $this->createUrl("order/edit", array("step"=>"goodsEdit")) ?>" method="post" onsubmit="return validateGoods()">
    <table class="table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="label">类别：</td>
            <td>
                <select id="goods-type" name="Form[type]">
                    <?php foreach ($goodsType as $type): ?>
                    <option value="<?php echo $type->id ?>" <?php if (isset($goods->type) && $goods->type == $type->id) echo "selected"; ?>><?php echo $type->name; ?></option>
                    <?php endforeach; ?>
                    <option value="0">自定义</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="label">季节：</td>
            <td>
                <select name="Form[season]">
                    <option value="0" <?php if (isset($goods->season) && $goods->season == 0) echo "selected"; ?>>不限</option>
                    <option value="1" <?php if (isset($goods->season) && $goods->season == 1) echo "selected"; ?>>春秋</option>
                    <option value="2" <?php if (isset($goods->season) && $goods->season == 2) echo "selected"; ?>>夏</option>
                    <option value="3" <?php if (isset($goods->season) && $goods->season == 3) echo "selected"; ?>>冬</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="label">性别：</td>
            <td>
                <select name="Form[sex]">
                    <option value="0" <?php if (isset($goods->sex) && $goods->sex == 0) echo "selected"; ?>>不限</option>
                    <option value="1" <?php if (isset($goods->sex) && $goods->sex == 1) echo "selected"; ?>>男</option>
                    <option value="2" <?php if (isset($goods->sex) && $goods->sex == 2) echo "selected"; ?>>女</option>
                    <option value="3" <?php if (isset($goods->sex) && $goods->sex == 3) echo "selected"; ?>>情侣</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="label">拍摄类型：</td>
            <td>
                <select name="Form[shoot_type]">
                    <?php foreach ($shootType as $type): ?>
                    <option value="<?php echo $type['id']; ?>" <?php if (isset($goods->shoot_type) && $goods->shoot_type == $type['id']) echo "selected"; ?>><?php echo $type['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="label">拍摄风格：</td>
            <td>
                <select name="Form[style]">
                    <option value="0">不限</option>
                    <?php foreach ($styles as $style): ?>
                    <option value="<?php echo $style['id']; ?>" <?php if (isset($goods->style) && $goods->style == $style['id']) echo "selected"; ?>><?php echo $style['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="label">
                拍摄数量：<br />
                (不含搭配用服饰)
            </td>
            <td>
                <input id="goods-count" type="text" name="Form[count]" class="input" style="width: 100px;" value="<?php if (isset($goods->count)) echo $goods->count; ?>" tip="请填写" />
                只能填数字
            </td>
        </tr>
        <tr>
            <td class="label">备注：</td>
            <td><textarea name="Form[memo]" class="text" style="width: 532px; height: 80px;"></textarea></td>
        </tr>
    </table>
    <input type="hidden" name="id" value="<?php //echo $id; ?>" />
    <input type="button" value="查看已添加的物品" onclick="window.location.href='<?php echo $this->createUrl("order/goodsList") ?>'" />
    <input type="submit" value="保存" />
</form>
