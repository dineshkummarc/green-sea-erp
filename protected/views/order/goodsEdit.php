<div class="step active">添加拍摄物品</div>
<div class="step">填写拍摄要求</div>
<div class="step">选择付款方式</div>
<div class="step">生成订单</div>
<div class="clear"></div>

<script>
function addItem(){
	var tb="myOrder";
	var index=$("#"+tb+">tbody>tr").length;
	var firstTr=$("#"+tb+">tbody>tr:first");
	var tr="<tr onmouseover=\"this.style.backgroundColor='#eee'\" onmouseout=\"this.style.backgroundColor='#fff'\">"+firstTr.html()+"</tr>";
	var html=tr.replace(/Form\[\d+\]/g,"Form["+(index+1)+"]").replace(/<a><\/a>/i,"<a href='javascript:;' onclick='deleteItem(this)'>删除</a>").replace(/<input>/g,'').replace(/background-color: #ffffbb/ig,'');
	var newTr=$(html);
	newTr.find("input[type='text']").val('');
	newTr.find("option").removeAttr("selected");
	newTr.appendTo($("#"+tb+">tbody"));

}
function deleteItem(n){
$(n).parents('tr').remove();
}
</script>

<form action="<?php echo $this->createUrl("order/goodsEdit") ?>" method="post" onsubmit="return validateGoods()">
    <table id="myOrder" class="table" cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th class="label">类别</th>
            <th class="label">季节</th>
            <th class="label">性别</th>
            <th class="label">拍摄数量</th>
            <th class="label">备注</th>
            <th class="label"></th>
        </tr>
        </thead>

        <tbody>
        <tr onmouseover="this.style.backgroundColor='#eee'" onmouseout="this.style.backgroundColor='#fff'">
            <td>
                <select class="change-goods-type" name="Form[1][type]" num="<?php echo $i; ?>">
                    <?php foreach ($goodsType as $type): ?>
                    <option value="<?php echo $type->id ?>" <?php if (isset($goods->type) && $goods->type == $type->id) echo "selected"; ?>><?php echo $type->name; ?></option>
                    <?php endforeach; ?>
                    <option value="0">自定义</option>
                </select>
            </td>
            <td>
                <select name="Form[1][season]">
                    <option value="0" <?php if (isset($goods->season) && $goods->season == 0) echo "selected"; ?>>不限</option>
                    <option value="1" <?php if (isset($goods->season) && $goods->season == 1) echo "selected"; ?>>春秋</option>
                    <option value="2" <?php if (isset($goods->season) && $goods->season == 2) echo "selected"; ?>>夏</option>
                    <option value="3" <?php if (isset($goods->season) && $goods->season == 3) echo "selected"; ?>>冬</option>
                </select>
            </td>
            <td>
                <select name="Form[1][sex]">
                    <option value="0" <?php if (isset($goods->sex) && $goods->sex == 0) echo "selected"; ?>>不限</option>
                    <option value="1" <?php if (isset($goods->sex) && $goods->sex == 1) echo "selected"; ?>>男</option>
                    <option value="2" <?php if (isset($goods->sex) && $goods->sex == 2) echo "selected"; ?>>女</option>
                    <option value="3" <?php if (isset($goods->sex) && $goods->sex == 3) echo "selected"; ?>>情侣</option>
                </select>
            </td>
            <td>
                <input //type="hidden" name="Form[1][id]" value="<?php echo $id; ?>" />
                <input type="text" name="Form[1][count]" class="input check-count" style="width: 50px;" tip="只能填数字" />
            </td>
            <td><textarea name="Form[1][memo]" class="text" style="width: 150px; height: 30px;"><?php if (isset($goods->memo)) echo $goods->memo; ?></textarea></td>
            <td class="acenter" width="50"><a></a></td>
        </tr>
        </tbody>
    </table>
    <div class="fc999" style="line-height:25px">如有不同类别的商品，请点击添加。物品类别的区分，对我们选择模特、提高拍摄质量有很大帮助。</div>
    <input type="button" onclick="addItem() " value="添加一项">
    <input type="submit" value="保存" />
</form>