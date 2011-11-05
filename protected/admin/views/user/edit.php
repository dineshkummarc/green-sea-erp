<div class="pageContent">
    <form action="<?php echo $this->createUrl(''); ?>"  class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);" method="post">
        <div class="pageFormContent" layoutH="60">
            <input type="hidden" name="Form[id]" value="<?php echo $user->id; ?>" />
            <div class="unit">
                <label>用户名</label>
                <span class="unit"><?php echo $user->name; ?></span>
            </div>
            <div class="unit">
                <label>密码重置</label>
                <input id="pwdequal" type="text" name="Form[re_pwd]" class="textInput" min="5" />
            </div>
            <div class="unit">
                <label>旺旺号</label>
                <input type="text" name="Form[wangwang]" class="textInput require" value="<?php echo $user->wangwang; ?>" alt="旺旺号不能为空" />
            </div>
           <div class="unit">
                <label>QQ号</label>
                <input type="text" name="Form[qq]" class="textInput require" value="<?php echo $user->qq; ?>" alt="QQ号不能为空" />
            </div>
            <div class="unit">
                <label>手机号码</label>
                <input type="text" name="Form[mobile_phone]" class="textInput require" value="<?php echo $user->mobile_phone; ?>" alt="手机号码不能为空" />
            </div>
            <div class="unit">
                <label>淘宝网地址</label>
                <input type="text" name="Form[oage]" class="textInput require" value="<?php echo $user->page; ?>" alt="淘宝网地址不能为空" />
            </div>
            <div class="unit">
                <label>会员积分</label>
                <input type="text" name="Form[score]" class="textInput require number" value="<?php echo $user->score; ?>" alt="积分不能为空，且为数字" />
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