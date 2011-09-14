<form action="<?php echo $this->createUrl("user/editInfo") ?>" method="post">
    <div class="info">
        <div>
            <label>客户名：</label><?php echo $info->name; ?>
        </div>
        <div>
            <label>旺旺号：</label><input type="text" name="Form[wangwang]" class="input" value="<?php echo $info->wangwang; ?>" />
        </div>
        <div>
            <label>QQ号：</label><input type="text" name="Form[qq]" class="input" value="<?php echo $info->qq; ?>" />
        </div>
        <div>
            <label>手机号：</label><input type="text" name="Form[mobile_phone]" class="input" value="<?php echo $info->mobile_phone; ?>" />
        </div>
        <div>
            <label>淘宝网店地址：</label><input type="text" name="Form[page]" class="input" value="<?php echo $info->page; ?>" />
        </div>
        <div style="margin-top: 10px; padding-left: 120px;">
            <a id="submit" href="javascript: void(0);" class="button"><span>提交修改</span></a>
        </div>
    </div>
</form>