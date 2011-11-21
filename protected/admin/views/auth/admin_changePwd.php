<div class="pageContent">
    <form action="<?php echo $this->createUrl("auth/changePwd"); ?>"  class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);" method="post">
        <div class="pageFormContent" layoutH="60">
        	<div class="unit">
        		<label>用户名</label>
        		<?php echo Yii::app()->user->name; ?>
        	</div>
            <div class="unit">
                <label>旧密码</label>
                <input name="Form[oldPwd]" type="password" class="required" size="30" value="" alt="密码不能为空" />
            </div>
            <div class="unit">
                <label>新密码</label>
                <input name="Form[newPwd]" type="password" id="password" class="required" size="30" value="" alt="密码不能为空" />
            </div>
            <div class="unit">
                <label>确认新密码</label>
                <input name="Form[newPwd1]" type="password" id="rePwd" class="required" equalto="#password" class="compare" size="30" value="" alt="必须与新密码相同" />
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
