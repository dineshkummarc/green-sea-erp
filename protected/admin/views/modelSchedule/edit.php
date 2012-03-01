<div class="pageContent">
    <form action="<?php echo $this->createUrl(''); ?>"  class="pageFormrequiredd-validate" onsubmit="return validateCallback(this, dialogAjaxDone);" method="post">
        <div class="pageFormContent" layoutH="60">
            <div class="unit">
                <label>档期安排：</label>
                <?php if(!empty($modelscheduleRow->date)){
                    echo $modelscheduleRow->date;?>
                    <input type="hidden" name="Form[date]" value="<?php echo $modelscheduleRow->date;?>" />
                <?php }else{?>
                    <input type="hidden" name="Form[update]" value = "1" />
                    <input type="text" name="Form[date]" value="" class="date required" yearstart="0" yearend="5" format="yyyy-MM-dd" /><a class="inputDateButton" href="javascript:;">选择</a>
                <?php }?>
            </div>
            <div class="unit">
                <label>模特昵称：</label>
                <?php if(!empty($modelscheduleRow->model_id)){?>
                <input type="hidden" name="Form[model_id]" value="<?php echo $modelscheduleRow->model_id;?>" />
                <?php
                    foreach ($modelsList as $modelsRow){
                        if($modelscheduleRow->model_id == $modelsRow->id){
                            echo $modelsRow->nick_name;
                        }
                    }
                }else{?>
                    <select name="Form[model_id]">
                    <?php foreach($modelsList as $modelsRow){?>
                    <option value="<?php echo $modelsRow->id;?>"><?php echo $modelsRow->nick_name;?></option>
                    <?php }?>
                    </select>
                <?php }?>
            </div>
            <div class="unit">
                <label>是否有档期：</label>
                <input type="radio" name="Form[scheduled]" value="1" checked/> 有
                <input type="radio" name="Form[scheduled]" value="0" <?php if(isset($modelscheduleRow->scheduled)){if($modelscheduleRow->scheduled == 0){echo 'checked';}}?> /> 无
            </div>
            <div class="unit">
                <label>最大拍摄数量：</label>
                <input type="text" name="Form[max_count]" value="<?php if(!empty($modelscheduleRow->max_count)){echo $modelscheduleRow->max_count;}?>"/>
            </div>
            <div class="unit">
                <label>最小拍摄数量：</label>
                <input type="text" name="Form[min_count]" value="<?php if(!empty($modelscheduleRow->min_count)){echo $modelscheduleRow->min_count;}?>"/>
            </div>
            <div class="unit">
                <label>当前数量：</label>
                <input type="text" name="Form[cur_count]" value="<?php if(!empty($modelscheduleRow->cur_count)){echo $modelscheduleRow->cur_count;}?>"/>
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
