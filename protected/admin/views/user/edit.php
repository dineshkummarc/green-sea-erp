<div class="pageContent">
    <form action="<?php echo $this->createUrl(''); ?>"  class="pageFormrequiredd-validate" onsubmit="return validateCallback(this, dialogAjaxDone);" method="post">
        <div class="pageFormContent" layoutH="60">
            <input type="hidden" name="Form[id]" value="<?php echo $user->id; ?>" />
            <?php if (!empty($user->id)): ?>
            <div class="unit">
                <label>客户编号</label>
                <span class="unit">P<?php echo substr(strval($user->id + 1000),1,3); ?></span>
            </div>
            <?php endif; ?>
            <div class="unit">
                <label>用户名</label>
                <span class="unit"><input type="text" name="Form[name]" class="required" value="<?php echo $user->name; ?>" alt="客户名不能为空" /></span>
            </div>
            <div class="unit">
                <?php if (!empty($user->password)): ?>
                <label>密码重置</label>
                <input id="pwdequal" type="text" name="Form[password]" />
                <?php else: ?>
                <label>密码</label>
                <input id="pwdequal" type="text" name="Form[password]" class="required" alt="密码不能为空" />
                <?php endif; ?>
            </div>
            <div class="unit">
                <label>确认密码</label>
                <input type="text" id="rePwd" equalto="#rePwd" />
            </div>
            <div class="unit">
                <label>旺旺号</label>
                <input type="text" name="Form[wangwang]" value="<?php echo $user->wangwang; ?>" />
            </div>
           <div class="unit">
                <label>QQ号</label>
                <input type="text" name="Form[qq]" value="<?php echo $user->qq; ?>" class="required" alt="QQ号码不能为空" />
            </div>
            <div class="unit">
                <label>手机号码</label>
                <input type="text" name="Form[mobile_phone]" value="<?php echo $user->mobile_phone; ?>" class="required" alt="手机不能为空" />
            </div>
            <div class="unit">
                <label>淘宝网地址</label>
                <input type="text" name="Form[page]" value="<?php echo $user->page; ?>" />
            </div>
            <div class="unit">
                <label>会员积分</label>
                <input type="text" name="Form[score]" value="<?php echo $user->score; ?>" class="required number" alt="积分不能为空，为数字" />
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