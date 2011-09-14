<div class="step">添加拍摄物品</div>
<div class="step active">填写拍摄要求</div>
<div class="step">选择付款方式</div>
<div class="step">生成订单</div>
<div class="clear"></div>
<form action="<?php $this->createUrl("order/edit", array("step"=>"save")); ?>"
    onsubmit="" method="post">
    <table class="table" cellpadding="0" cellspacing="0">

    </table>
    <input type="button" value="返回上一步" onclick="window.location.href='<?php echo Yii::app()->request->urlReferrer; ?>'" />
    <input type="submit" value="完成" />
</form>
