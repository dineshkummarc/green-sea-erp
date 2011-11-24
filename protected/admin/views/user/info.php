<div class="pageContent">
    <form action="<?php echo $this->createUrl(''); ?>"  class="pageFormrequiredd-validate" onsubmit="return validateCallback(this, dialogAjaxDone);" method="post">
        <div class="pageFormContent" layoutH="60">
            <?php if (!empty($user->id)): ?>
            <div class="unit">
                <label>客户编号</label>
                <span style="padding:2px;line-height:16px;">P<?php echo substr(strval($user->id + 1000),1,3); ?></span>
            </div>
            <?php endif; ?>
            <div class="unit">
                <label>用户名</label>
                <span><?php echo $user->name; ?></span>
            </div>
            <div class="unit">
            	<label>用户积分</label>
            	<span><?php echo $user->score; ?></span>
            </div>
            <div class="unit">
                <label>旺旺号</label>
                <span><?php echo $user->wangwang; ?></span>
            </div>
           <div class="unit">
                <label>QQ号</label>
                <span><?php echo $user->qq; ?></span>
            </div>
            <div class="unit">
                <label>手机号码</label>
                <span><?php echo $user->mobile_phone?></span>
            </div>
            <div class="unit">
                <label>电子邮箱</label>
                <span><?php echo $user->email; ?></span>
            </div>
            <div class="unit">
                <label>淘宝网地址</label>
                <span><?php echo $user->page; ?></span>
            </div>
            <div class="unit" >
            	<label>地区</label>
            	<span><?php echo $area = $this->getArea($user->area_id);?></span>
			</div>
			<?php if($receiver != false): ?>
			<h2 class="contentTitle" style="clear:both">收货信息</h2>
			<?php foreach ($receiver as $val): $val = (object)$val; ?>
            <div class="unit">
                <label>收货人姓名</label>
                <span><?php echo !empty($val->receive_name) ? $val->receive_name : '' ;  ?></span>
            </div>
            <div class="unit">
                <label>收货人手机</label>
                <span><?php echo $val->mobile_phone; ?></span>
            </div>
            <div class="unit">
            	<label>收货人电话</label>
            	<span><?php echo $val->phone; ?></span>
            </div>
            <div class="unit">
                <label>详细地址:</label>
                <span><?php echo $area = $this->getArea($val->area_id);?></span>
                <span><?php  echo!empty($val->street) ? $val-> street:'';   ?></span>
            </div>
            <div class="unit" >
                <label>邮政编码:</label>
                <span><?php  echo!empty($val->postalcode) ? $val->postalcode: '';?></span>
            </div>
            <div class="divider"></div>
        <?php endforeach; endif; ?>
        </div>
        <div class="formBar">
            <ul>
                <li><div class="buttonActive"><div class="buttonContent"><a href="<?php echo $this->createUrl('user/edit', array('id'=>$user->id)) ?>" target="dialog" width="600" height="600" mask="true" title="修改" style="color:#000;line-height:20px;">修改</a></div></div></li>
                <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
            </ul>
        </div>
    </form>
</div>




















