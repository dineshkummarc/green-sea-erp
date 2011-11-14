<div class="pageContent">
    <form action="<?php echo $this->createUrl(''); ?>" class="pageForm required-validate"
        enctype="multipart/form-data" onsubmit="return iframeCallback(this, dialogAjaxDone);" method="post">
        <div class="pageFormContent" layoutH="60">
            <input type="hidden" name="Form[storage_id]" value="<?php echo !empty($storage_id) ? $storage_id : ''; ?>" />

            <div class="unit">
                <label>入库时间</label>
                <input type="text" name="Form[in_time]" value="<?php echo !empty($in_time) ? date('Y-m-d H:i:s', $in_time) : ''; ?>" class="date required" yearstart="0" yearend="5" format="yyyy-MM-dd HH:mm:ss" /><a class="inputDateButton" href="javascript:;">选择</a>
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