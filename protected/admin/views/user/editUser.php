<div class="pageContent" >
	<form action="<?php echo $this->createUrl("user/addUser") ?>" method="post" onsubmit="return validateCallback(this, dialogAjaxDone);">
	    <div class="pageFormContent" layoutH="60">
	        <div class="unit">
	            <label>客户名：</label><input type="text" name="Form[name]" class="required" value="<?php echo !empty($user->name) ? $user->name : ''; ?>"/>
	        </div>
	        <div class="unit">
	            <label>手机号：</label><input type="text" name="Form[mobile_phone]" class="required number" value="<?php echo !empty($user->mobile_phone) ? $user->mobile_phone : ''; ?>"/>
	        </div>
	        <div class="unit">
	            <label>密码：</label><input type="password" name="Form[password]"   id="pwd"   class="required alphanumeric" minlength="6" maxlength="20" value="<?php echo !empty($user->password) ? $user->password : ''; ?>"/>
	        </div>
	        <div class="unit">
	            <label>密码验证：</label><input  type="password" class="required"  equalto="#pwd" />
	        </div>
	        <div class="unit">
	            <label>邮箱地址：</label><input type="text" name="Form[email]" class="required  email" value="<?php echo !empty($user->email) ? $user->email : ''; ?>" />
	        </div>
	        <div class="unit">
	            <label>所在地区：</label>
	            <select name="Form[area_id]" class="combox" default="<?php echo !empty($user->area_id) ? $user->area_id : 1; ?>">
	                <option value="0">请选择</option>
	                <?php foreach ($areaList as $area): ?>
	                <option value="<?php echo $area->id ?>"><?php echo $area->name ?></option>
	                <?php endforeach; ?>
	            </select>
	        </div>
	        <div class="unit">
	            <label>旺旺号：</label><input type="text" name="Form[wangwang]"  value="<?php echo !empty($user->wangwang) ? $user->wangwang : ''; ?>"/>
	        </div>
	        <div class="unit">
	            <label>QQ号：</label><input type="text" name="Form[qq]"  value="<?php echo !empty($user->qq) ? $user->qq : ''; ?>"/>
	        </div>
	        <div class="unit">
	            <label>淘宝网店地址：</label><input type="text" name="Form[page]"  value="<?php echo !empty($user->page) ? $user->page : ''; ?>"/>
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