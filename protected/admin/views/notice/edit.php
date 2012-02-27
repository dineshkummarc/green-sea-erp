<div class="pageContent">
    <form action="<?php echo $this->createUrl(''); ?>"  class="pageFormrequiredd-validate" onsubmit="return validateCallback(this, dialogAjaxDone);" method="post">
        <div class="pageFormContent" layoutH="60">
            <input type="hidden" name="Form[id]" value="<?php if(!empty($notice->id)){echo $notice->id;}?>" />
            <div class="unit">
                <label>标题：</label>
                <input type="text" size="50" maxlength="40" name="Form[title]" class="required" value="<?php if(!empty($notice->title)){echo $notice->title;}?>" alt="标题不能为空" />
            </div>
            <div class="unit">
            <label>内容：</label>
            <textarea rows="18" cols="45" name="Form[content]" value="" class="required" alt="公告内容不为空" ><?php if(!empty($notice->content)){echo $notice->content;}?></textarea>
            </div>
            <div class="unit">
            <label>公告设置：</label>
            <input type="radio" name="Form[status]" value="1" checked />显示
            <input type="radio" name="Form[status]" value="0" <?php if(isset($notice->status) and $notice->status == 0){echo 'checked';}?>/>隐藏
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
