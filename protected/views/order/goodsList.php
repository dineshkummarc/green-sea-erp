<div class="step active">添加拍摄物品</div>
<div class="step">填写拍摄要求</div>
<div class="step">选择付款方式</div>
<div class="step">生成订单</div>
<div class="clear"></div>

<script>
function addItem(){
	var tb="myOrderList";
	var index=$("#"+tb+">tbody>tr").length;
	var firstTr=$("#"+tb+">tbody>tr:first");
	var tr="<tr onmouseover=\"this.style.backgroundColor='#eee'\" onmouseout=\"this.style.backgroundColor='#fff'\">"+firstTr.html()+"</tr>";
	var html=tr.replace(/Form\[\d+\]/g,"Form["+(index+1)+"]").replace(/<a><\/a>/i,"<a href='javascript:;' onclick='deleteItem(this)'>删除</a>").replace().replace(/background-color: #ffffbb/ig,'');
	var newTr=$(html);
	newTr.find("input[type='text']").val('');
	newTr.find("option").removeAttr("selected");
	newTr.appendTo($("#"+tb+">tbody"));
}
function deleteItem(n){
$(n).parents('tr').remove();
}
</script>

<form action="<?php echo $this->createUrl("order/shootStyle") ?>" method="post" onsubmit="return validateGoods()">
    <table id="myOrderList" class="table" cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th class="label">拍摄类型</th>
            <th class="label">类别&nbsp;&nbsp;/&nbsp;&nbsp;季节&nbsp;&nbsp;/&nbsp;&nbsp;性别</th>
            <th class="label">拍摄数量</th>
            <th class="label">备注</th>
            <th class="label"></th>
        </tr>
        </thead>

        <tbody>
        <?php// for ($i = 0; $i < $_POST['count']; $i++): ?>
        <tr onmouseover="this.style.backgroundColor='#eee'" onmouseout="this.style.backgroundColor='#fff'">
            <td>
                <select name="Form[1][shoot_type]">
                    <?php foreach ($shootType as $type): ?>
                    <option value="<?php echo $type['id']; ?>"><?php echo $type['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <select class="change-goods-type" name="Form[1][type]" num="<?php echo $i; ?>">
                    <?php foreach ($goodsList as $key=>$goods): ?>
                    <option value="$key"><?php echo $goods->type_name.','.$goods->season.','.$goods->sex;?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <input type="hidden" name="Form[1][id]" value="<?php echo $id; ?>" />
                <input type="text" name="Form[1][count]" class="input check-count" style="width: 50px;" tip="只能填数字" />
            </td>
            <td><textarea name="Form[1][memo]" class="text" style="width: 150px; height: 30px;"><?php if (isset($goods->memo)) echo $goods->memo; ?></textarea></td>
            <td class="acenter" width="50"><a></a></td>
        </tr>
        <?php //endfor;?>
        </tbody>
    </table>
    <input type="button" onclick="addItem() " value="添加一项">
    <input type="submit" value="保存" />
</form>