`<div class="step">添加拍摄物品</div>
<div class="step active">填写拍摄要求</div>
<div class="step">选择付款方式</div>
<div class="step">生成订单</div>
<div class="clear"></div>
<form id="model-form" action="<?php echo $this->createUrl("order/selectModels", array('save'=>false)); ?>"
        onsubmit="return validateModel()" method="post">
    <div class="models">
        <h2>B类模特</h2>
        <?php foreach ($models as $model): ?>
        <div id="model-<?php echo $model->id ?>" class="model">
            <a href="<?php echo Yii::app()->baseUrl.'/'.$model->picture; ?>" target="_blank" title="点击看大图"><img src="<?php echo $model->head_img; ?>" /></a>
            <div class="name">
                <?php echo $model->nick_name; ?>
            </div>
            <div class="action">
                <label><input type="checkbox" name="Form[models][]" value="<?php echo $model->id; ?>" <?php if (in_array($model->id, $selectedModels)) echo "checked"; ?> /> 选择</label>
                &nbsp;&nbsp;&nbsp;<a href="<?php echo $this->createUrl("model/show", array('id'=>$model->id)) ?>" target="_blank">查看详情</a>
            </div>
        </div>
        <?php endforeach; ?>
        <div class='clear'></div>
        <div class="selected">
            您选择的模特：
            <?php if (!empty($selectedModels)): foreach ($selectedModels as $model): ?>
            <span id="model-<?php echo $model; ?>"><?php echo $models[$model]->nick_name; ?></span>
            <?php endforeach; else: ?>
            <span id="model-0">无</span>
            <?php endif; ?>
        </div>
        <input type="button" value="返回上一步" onclick="window.location.href='<?php echo $this->createUrl("order/goodsList") ?>'" />
        <input type="submit" value="完成，进入下一步" />
    </div>
</form>