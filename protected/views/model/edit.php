<script type="text/javascript">
function reload(id, name)
{
    var $dom = $('#'+id);
    $dom.hide();
    var input = $("<div id='file-"+id+"' style='display: inline;'><input type='file' name='"+name+"' />&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript: void(0);' onclick='cancel(\""+id+"\")'>取消</a></div>");
    $dom.parent().append(input);
}
function cancel(id)
{
    $('#file-'+id).remove();
    $('#'+id).show();
}
</script>
<form action="<?php echo $this->createUrl("") ?>" enctype="multipart/form-data" method="post">
    <div class="detail" style="width: 500px;">
        <div class="line">
            <label>昵称</label>
            <span><input type="text" name="Form[nick_name]" value="<?php echo $model->nick_name; ?>" /></span>
        </div>
        <div class="line">
            <label>头像</label>
            <span>
                <?php if (empty($model->head_img)): ?>
                <input type="file" name="head_img" />
                <?php else: ?>
                <div id="reload1" style="display: inline;">
                    <a href="<?php echo Yii::app()->baseUrl.'/'.$model->head_img; ?>" target="blank">查看图片</a>&nbsp;&nbsp;&nbsp;
                    <a href="javascript: void(0)" onclick="reload('reload1', 'head_img')" target="blank">重新上传</a>
                </div>
                <?php endif; ?>
            </span>
        </div>
        <div class="line">
            <label>图片</label>
            <span>
                <?php if (empty($model->picture)): ?>
                <input type="file" name="picture" />
                <?php else: ?>
                <div id="reload2" style="display: inline;">
                    <a href="<?php echo Yii::app()->baseUrl.'/'.$model->picture; ?>" target="blank">查看图片</a>&nbsp;&nbsp;&nbsp;
                    <a href="javascript: void(0)" onclick="reload('reload2', 'picture')" target="blank">重新上传</a>
                </div>
                <?php endif; ?>
            </span>
        </div>
        <div class="line">
            <label>中文名</label>
            <span><input type="text" name="Form[china_name]" value="<?php echo  $model->china_name; ?>" /></span>
        </div>
        <div class="line">
            <label>英文名</label>
            <span><input type="text" name="Form[english_name]" value="<?php echo  $model->english_name; ?>" /></span>
        </div>
        <div class="line">
            <label>身高</label>
            <span><input type="text" name="Form[height]" value="<?php echo  $model->height; ?>" /></span>
        </div>
        <div class="line">
            <label>体重</label>
            <span><input type="text" name="Form[weight]" value="<?php echo  $model->weight; ?>" /></span>
        </div>
        <div class="line">
            <label>胸围</label>
            <span><input type="text" name="Form[chest]" value="<?php echo  $model->chest; ?>" /></span>
        </div>
        <div class="line">
            <label>腰围</label>
            <span><input type="text" name="Form[waist]" value="<?php echo  $model->waist; ?>" /></span>
        </div>
        <div class="line">
            <label>臀围</label>
            <span><input type="text" name="Form[hip]" value="<?php echo  $model->hip; ?>" /></span>
        </div>
        <div class="line">
            <label>鞋码</label>
            <span><input type="text" name="Form[shoes]" value="<?php echo  $model->shoes ?>" /></span>
        </div>
        <div class="line">
            <label>所在地区</label>
            <span>
                <select name="Form[area_id]">
                    <?php foreach ($areaList as $area): ?>
                    <option value="<?php echo $area->id ?>"><?php echo $area->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </span>
        </div>
        <div class="line">
            <label>签约等级</label>
            <span><input type="text" name="Form[level]" value="<?php echo  $model->level; ?>" /></span>
        </div>
        <div class="line" style="padding: 10px; text-align: center;">
            <input type="hidden" name="Form[id]"  value="<?php echo $model->id; ?>"/>
            <input type="submit" value="提交"/>
        </div>
    </div>
</form>