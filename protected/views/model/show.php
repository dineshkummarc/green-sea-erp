<div class="detail" style="width: 500px;">
    <div class="line">
        <label>昵称</label>
        <span><?php echo $model->nick_name; ?></span>
    </div>
    <div class="line">
        <label>图片</label>
        <span><a href="<?php echo Yii::app()->baseUrl.'/'.$model->picture; ?>" target="blank">查看图片</a></span>
    </div>
    <div class="line">
        <label>中文名</label>
        <span><?php echo  $model->china_name; ?></span>
    </div>
    <div class="line">
        <label>英文名</label>
        <span><?php echo  $model->english_name; ?></span>
    </div>
    <div class="line">
        <label>身高/体重</label>
        <span><?php echo  $model->height.'/'.$model->weight; ?></span>
    </div>
    <div class="line">
        <label>三围</label>
        <span><?php echo  $model->chest.'/'.$model->waist.'/'.$model->hip; ?></span>
    </div>
    <div class="line">
        <label>鞋码</label>
        <span><?php echo  $model->shoes ?></span>
    </div>
    <div class="line">
        <label>所在地区</label>
        <span><?php echo  $model->Area->name; ?></span>
    </div>
    <div class="line">
        <label>签约等级</label>
        <span><?php echo  $model->level; ?></span>
    </div>
</div>