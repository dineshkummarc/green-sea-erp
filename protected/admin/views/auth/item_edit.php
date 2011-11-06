<div class="pageContent">
    <form action="<?php echo $this->createUrl("auth/editItem"); ?>"  class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);" method="post">
        <div class="pageFormContent" layoutH="60">
            <input type="hidden" name="Form[id]" value="<?php echo $item->id; ?>" />
            <div class="unit">
                <label>权限名</label>
                <input name="Form[rule]" class="required" type="text" sizt="50" value="<?php echo $item->rule; ?>" alt="权限组不能为空" />
            </div>
            <div class="unit">
                <label>描述</label>
                <textarea name="Form[description]" style="width: 200px; height: 30px"><?php echo $item->description; ?></textarea>
            </div>
            <div class="unit">
                <label>继承自</label>
                <select name="Form[parent_id]" class="combox" default="<?php echo $item->parent_id ?>">
                    <option value="0">无</option>
                    <?php foreach($allItems as $val): ?>
                    <option value="<?php echo $val->id ?>"><?php echo $val->name ?></option>
                    <?php endforeach; ?>
                </select>
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
