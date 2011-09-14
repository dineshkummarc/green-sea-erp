<div class="contact">
    <form action="<?php echo $this->createUrl("user/contact");?>" method="post">
        <p class="imp"><?php echo Yii::app()->user->name; ?>：</p>
        <p>您对<span class="imp">绿浪视觉</span>有任何建议，或使用中遇到问题，请在本页面反馈。</p>
        <p>投诉、举报或纠纷处理，请联系<span class="imp">绿浪视觉</span>客服中心：</p>
        <p>请留下您对<span class="imp">绿浪视觉</span>的意见和建议！</p>
        <p><textarea name="Form[content]" class="text" style="width: 530px; height: 210px;"></textarea></p>
        <span class="msg info-small" style="margin-right: 250px;">您最多可以输入1000字</span>
        <p><input type="submit" value="提交" /></p>
    </form>
</div>