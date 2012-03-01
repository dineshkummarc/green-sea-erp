<div class="pageContent">
    <form action="<?php echo $this->createUrl(''); ?>"  class="pageFormrequiredd-validate" onsubmit="return validateCallback(this, dialogAjaxDone);" method="post">
        <div class="pageFormContent" layoutH="60">
            <input type="hidden" name="Form[id]" value="<?php if(!empty($shootbase->id)){echo $shootbase->id;}?>" />
            <div class="unit">
                <label>拍摄基地：</label>
                <input type="text" maxlength="40" name="Form[name]" class="required" value="<?php if(!empty($shootbase->name)){echo $shootbase->name;}?>" alt="拍摄基地不能为空" />
            </div>
        </div>
        <div class="formBar">
            <ul>
                <li><div class="buttonActive"><div class="buttonContent"><button type="submit">确定 </button></div></div></li>
                <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
            </ul>
        </div>
    </form>
</div>
