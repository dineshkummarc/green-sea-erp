<div class="pageContent">
    <form action="<?php echo $this->createUrl("auth/editRole"); ?>"  class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);" method="post">
        <div class="pageFormContent" layoutH="60">
            <input type="hidden" name="Form[id]" value="<?php echo $role->id; ?>" />
            <div class="unit">
                <label>权限组名</label>
                <input name="Form[name]" class="required" type="text" value="<?php echo $role->name; ?>" alt="权限组不能为空" />
            </div>
            <div class="unit">
                <label>是否启用</label>
                <span><input type="radio" name="Form[status]" value="1" <?php if (!isset($role->status) || $role->status) echo "checked='checked'"; ?> />是</span>
                <span><input type="radio" name="Form[status]" value="0" <?php if (isset($role->status) && !$role->status) echo "checked='checked'"; ?> />否</span>
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
