<div class="pageContent">
    <form action="<?php echo $this->createUrl("auth/editAdmin"); ?>"  class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);" method="post">
        <div class="pageFormContent" layoutH="60">
            <input type="hidden" name="Form[id]" value="<?php echo $admin->id; ?>" />
            <div class="unit">
            	<label>工号</label>
                <input name="Form[number]" class="required" type="text" sizt="50" value="<?php echo $admin->number; ?>" alt="工号不能为空" />
            </div>
            <div class=”unit”>
            <label>所属基地</label>
                <select name=”Form[city_id]” class=”combox” default=”<?php echo $admin->city_id ?>”>
                <?php foreach($bases as $base): ?>
                    <option value=”<?php echo $base->id; ?>”><?php echo $base->name;  ?></option>
                <?php endforeach; ?>
                </select>
            </div>
            <div class="unit">
                <label>登录名</label>
                <input name="Form[name]" class="required" type="text" sizt="50" value="<?php echo $admin->name; ?>" alt="姓名不能为空" />
            </div>
            <div class="unit">
                <label>登录密码</label>
                <input name="Form[password]" type="password" sizt="50" value=""<?php if ($admin->id > 0): ?> alt="密码为空则不修改" <?php else: ?> class="required" alt="密码不能为空" <?php endif; ?> />
            </div>
            <div class="unit">
                <label>权限组</label>
                <select name="Form[role_id]" class="combox" default="<?php echo $admin->role_id ?>">
                    <?php foreach($roles as $val): ?>
                    <option value="<?php echo $val->id ?>"><?php echo $val->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="unit">
                <label>超级管理员</label>
                <span><input type="radio" value="1" name="Form[is_supper]" <?php if (!isset($admin->is_supper) || $admin->is_supper) echo "checked='1'"; ?> />是</span>
                <span><input type="radio" value="0" name="Form[is_supper]" <?php if (isset($admin->is_supper) && !$admin->is_supper) echo "checked='1'"; ?> />否</span>
            </div>
            <div class=”unit”>
                <label>所属基地</label>
                <select name=”Form[city_id]” class=”combox” default=”<?php echo $admin->city_id ?>”>
                    <?php foreach($bases as $base): ?>
                    <option value=”<?php echo $base->id; ?>”><?php echo $base->name;  ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="unit">
                <label>是否启用</label>
                <span><input type="radio" value="1" name="Form[status]" <?php if (!isset($admin->status) || $admin->status) echo "checked='1'"; ?> />是</span>
                <span><input type="radio" value="0" name="Form[status]" <?php if (isset($admin->status) && !$admin->status) echo "checked='1'"; ?> />否</span>
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