<div class="pageContent">
    <form action="<?php echo $this->createUrl(''); ?>"  class="pageFormrequiredd-validate" onsubmit="return validateCallback(this, dialogAjaxDone);" method="post">
        <div class="pageFormContent" layoutH="60">
            <input type="hidden" name="Form[id]" value="<?php if(!empty($star->id)){echo $star->id;}?>" />
            <div class="unit">
                <label>明星一：</label>
                <input type="text" maxlength="20" name="Form[star1]" class="required" value="<?php if(!empty($star->star1)){echo $star->star1;}?>" />
            </div>
            <div class="unit">
                <label>明星二：</label>
                <input type="text" maxlength="20" name="Form[star2]" value="<?php if(!empty($star->star2)){echo $star->star2;}?>" />
            </div>
            <div class="unit">
                <label>明星三：</label>
                <input type="text" maxlength="20" name="Form[star3]" value="<?php if(!empty($star->star3)){echo $star->star3;}?>" />
            </div>
                        <div class="unit">
                <label>明星四：</label>
                <input type="text" maxlength="20" name="Form[star4]" value="<?php if(!empty($star->star4)){echo $star->star4;}?>" />
            </div>
            <div class="unit">
                <label>明星五：</label>
                <input type="text" maxlength="20" name="Form[star5]" value="<?php if(!empty($star->star5)){echo $star->star5;}?>" />
            </div>
            <div class="unit">
                <label>评比时间：</label>
                <input type="text" name="Form[time]" value="<?php echo !empty($star->time) ? date('Y-m-d H:i', $star->time) : ''; ?>" class="date required" yearstart="0" yearend="5" format="yyyy-MM-dd HH:mm" /><a class="inputDateButton" href="javascript:;">选择</a>
            </div>
            <div class="unit">
                <label>评比方式：</label>
                <input type="radio" name="Form[is_month]" value="0" checked />周评
                <input type="radio" name="Form[is_month]" value="1" <?php if(isset($star->is_month) and $star->is_month == 1){echo 'checked';}?>/>月评
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
