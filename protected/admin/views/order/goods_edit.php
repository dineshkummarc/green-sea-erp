<script type="text/javascript">
$(function(){
	$(".goods-type").change(function () {
		var $this = $(this);
		if ($this.val() == 0)
		{
			var input = $("<input type='text' />")
			.attr("name", "Form[type_name]")
			.attr("class", "required textInput valid")
			.width(100)
			.css("margin-left", 5);
			$this.after(input);
			inputTip(input);
		}
		else if ($this.next().length > 0)
		{
			$this.next().remove();
		}
	}); 
});
</script>
<div class="pageContent">
    <form action="<?php echo $this->createUrl(''); ?>" class="pageForm required-validate"
        enctype="multipart/form-data" onsubmit="return iframeCallback(this, dialogAjaxDone);" method="post">
        <div class="pageFormContent" layoutH="60">
            <input type="hidden" name="Form[id]" value="<?php echo !empty($orderGoods->id) ? $orderGoods->id : ''; ?>" />
            
            <div class="unit">
                <label>类型</label>
                <select name="Form[type]" class="goods-type" default="<?php echo isset($orderGoods->type) ? $orderGoods->type : 1; ?>">
                	<?php foreach ($types as $type):?>
                    <option value="<?php echo $type->id?>" <?php if ($type->id == $orderGoods->type)echo "selected"?>><?php echo $type->name?></option>
                    <?php endforeach;?>
                    <option value="0" <?php if ($orderGoods->type == 0)echo "selected"?>>自定义</option>
                </select>
                <?php if ($orderGoods->type == 0)echo "<input value='$orderGoods->type_name' class='required textInput valid' type='text' name='Form[type_name]' style='width: 100px; margin-left: 5px;'>";?>
            </div>
            <div class="unit">
                <label>季节</label>
                <select name="Form[season]" class="combox required" default="<?php echo !empty($orderGoods->type) ? $orderGoods->season : 0; ?>">
                    <option value="0">不限</option>
                    <option value="1">春秋</option>
                    <option value="2">夏</option>
                    <option value="3">冬</option>
                </select>
            </div>
            <div class="unit">
                <label>性别</label>
                <select name="Form[sex]" class="combox required" default="<?php echo !empty($orderGoods->sex) ? $orderGoods->sex : 0; ?>">
                    <option value="0">不限</option>
                    <option value="1">男</option>
                    <option value="2">女</option>
                    <option value="3">情侣</option>
                </select>
            </div>
            <div class="unit">
                <label>拍摄类型</label>
                <select name="Form[shoot_type]" class="combox required" default="<?php echo !empty($orderGoods->shoot_type) ? $orderGoods->shoot_type : ''; ?>">
                	<?php foreach ($shootTypes as $shootType):?>
                    <option value="<?php echo $shootType->id?>"><?php echo $shootType->name?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="unit">
                <label>拍摄风格</label>
                <select name="Form[style]" class="combox required" default="<?php echo !empty($orderGoods->style) ? $orderGoods->style : ''; ?>">
                	<?php foreach ($styles as $style):?>
                    <option value="<?php echo $style->id?>"><?php echo $style->name?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="unit">
                <label>拍摄数量</label>
                <input type="text" name="Form[count]" value="<?php echo !empty($orderGoods->count) ? $orderGoods->count : '0'; ?>" class="required" />
            </div>
            <div class="unit">
                <label>备注</label>
                <textarea class="require textInput valid" style="width: 400px; height: 100px;" maxlength="200" name="Form[memo]"><?php echo !empty($orderGoods->memo) ? $orderGoods->memo : ''; ?></textarea>
            </div>
        </div>
        <div class="formBar">
            <ul>
                <li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
                <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
            </ul>
        </div>
    </form>
</div>