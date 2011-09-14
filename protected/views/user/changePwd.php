<form action="<?php echo $this->createUrl("user/changePwd") ?>" method="post" onsubmit="return validatePassword()">
    <div class="info">
        <div>
            <label>旧密码：</label><input type="password" name="Form[oldpassword]" class="input" value="" />
        </div>
        <div>
            <label>新密码：</label><input type="password" name="Form[newpassword]" class="input" value="" />
        </div>
        <div>
            <label>新密码验证：</label><input type="password" name="Form[repassword]" class="input" value="" />
        </div>
        <div style="margin-top: 10px; padding-left: 120px;">
            <a id="submit" href="javascript: void(0);" class="button"><span>修改</span></a>
        </div>
    </div>
</form>