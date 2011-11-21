<script type="text/javascript">
$(function(){
	function trim(str){  //删除左右两端的空格
		return str.replace(/(^\s*)|(\s*$)/g, "");
	}
	$("#click_a").click(function(){
		$("#click_optional").html('<input type="hidden" name="Form[receive][optional]" value="1"/>');
		$("#receive").show();
	});
	$("button[type=submit]").click(function(){
		$qq = $("input[name='Form[qq]']").val();
		$reg = /^\d{5,10}$/;
		if(!$reg.test($qq)){
			alert('请输入正确的QQ号码');
			return false;
		}

		$mobile_phone = $("input[name='Form[mobile_phone]']").val();
		$reg = /^1[358][0-9]{9}$/;
		if(!$reg.test($mobile_phone)){
			alert('请输入正确的手机号码');
			return false;
		}

		$email = $("input[name='Form[email]']").val();
		if($email != "")
		{
			$reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
			if(!$reg.test($email)){
				alert('邮箱格式不正确');
				return false;
			}
		}

		$score = $("input[name='Form[score]']").val();
		$reg = /^[1-9]d*|0$/;
		if(!$reg.test($score)){
			alert('积分只能为0或正整数');
			return false;
		}

		$optional = $("input[name='Form[receive][optional]']").val();
		if($optional == 1)
		{
			$receive_name = $("input[name='Form[receive][receive_name]']").val();
			if(trim($receive_name) == "")
			{
				alert('收货人不能为空');
				return false;
			}

			$receive_mobile_phone = $("input[name='Form[receive][mobile_phone]']").val();
			$phone_1 = $("input[name='Form[receive][phone-1]']").val();
			$phone_2 = $("input[name='Form[receive][phone-2]']").val();
			$phone_3 = $("input[name='Form[receive][phone-3]']").val();
			$phone = "";
			if(trim($phone_1)!="" && trim($phone_2)!="")
			{
				$phone = $phone_1+"-"+$phone_2;
			}else{
				$phone = $phone_1;
			}
			if(trim($phone_2)!="" && trim($phone_3)!="")
			{
				$phone = $phone+"-"+$phone_3;
			}else if(trim($phone_2)!="")
			{
				$phone = $phone_2;
			}else if(trim($phone_3)!="")
			{
				$phone = $phone_3;
			}

			if(trim($receive_mobile_phone)=="" && trim($phone)=="")
			{
				alert('[收货人]请填写手机号码或座机号码任意一个');
				return false;
			}
			if(trim($receive_mobile_phone)!="")
			{
				$reg = /^1[358][0-9]{9}$/;
				if(!$reg.test($receive_mobile_phone)){
					alert('[收货人手机]请输入正确的手机号码');
					return false;
				}
			}
			if(trim($phone)!="")
			{
				$reg = /^((\d{3,4})|\d{3,4}-)?\d{7,8}(-\d{1,4})?$/;
				if(!$reg.test($phone)){
					alert('座机号码格式不正确');
					return false;
				}
			}
			$area_1 = $("input[name='Form[receive][area_1]']").val();
			if($area_1 == 0)
			{
				alert('请选择省份');
				return false;
			}

			$area_2 = $("input[name='Form[receive][area_2]']").val();
			if($area_2 == 0)
			{
				alert('请选择城市');
				return false;
			}

			$area_id = $("input[name='Form[receive][area_id]']").val();
			if($area_id == 0)
			{
				alert('请选择地区');
				return false;
			}

			$street = $("textarea[name='Form[receive][street]']").val();
			if(trim($street) == "")
			{
				alert('详细地址不能为空');
				return false;
			}

			$postalcode = $("input[name='Form[receive][postalcode]']").val();
			$reg = /^[0-9]{6}$/;
			if(!$reg.test($postalcode)){
				alert('请输入正确的邮编');
				return false;
			}
		}
	});
});
</script>
<div class="pageContent">
    <form action="<?php echo $this->createUrl(''); ?>"  class="pageFormrequiredd-validate" onsubmit="return validateCallback(this, dialogAjaxDone);" method="post">
        <div class="pageFormContent" layoutH="60">
            <input type="hidden" name="Form[id]" value="<?php echo $user->id; ?>" />
            <input type="hidden" name="Form[receiver_id]" value="<?php echo $receiver->id; ?>" />
            <?php if (!empty($user->id)): ?>
            <div class="unit">
                <label>客户编号</label>
                <span style="padding:2px;line-height:16px;">P<?php echo substr(strval($user->id + 1000),1,3); ?></span>
            </div>
            <?php endif; ?>
            <div class="unit">
                <label>用户名</label>
                <input type="text" maxlength="20" name="Form[name]" class="required" value="<?php echo $user->name; ?>" alt="客户名不能为空" />
            </div>
            <div class="unit">
                <?php if (!empty($user->password)): ?>
                <label>密码重置</label>
                <input id="pwdequal" maxlength="20" type="password" name="Form[password]"/>
                <?php else: ?>
                <label>密码</label>
                <input id="pwdequal" maxlength="20" type="password" name="Form[password]" class="required" alt="密码不能为空" />
                <?php endif; ?>
            </div>
            <div class="unit">
                <label>确认密码</label>
                <input type="password" maxlength="20" class="<?php if (empty($user->id))echo "required"?>" id="rePwd" equalto="#pwdequal" />
            </div>
            <div class="unit">
                <label>旺旺号</label>
                <input type="text" name="Form[wangwang]" value="<?php echo $user->wangwang; ?>" />
            </div>
           <div class="unit">
                <label>QQ号</label>
                <input type="text" maxlength="15" name="Form[qq]" value="<?php echo $user->qq; ?>" class="required" alt="QQ号码不能为空" />
            </div>
            <div class="unit">
                <label>手机号码</label>
                <input type="text" maxlength="11" class="required" name="Form[mobile_phone]" value="<?php echo $user->mobile_phone; ?>" alt="手机不能为空" />
            </div>
            <div class="unit">
                <label>电子邮箱</label>
                <input type="text" name="Form[email]" value="<?php echo $user->email; ?>" />
            </div>
            <div class="unit">
                <label>淘宝网地址</label>
                <input type="text" name="Form[page]" value="<?php echo $user->page; ?>" />
            </div>
            <div class="unit">
                <label>会员积分</label>
                <input type="text" name="Form[score]" value="<?php if (empty($user->score))echo '0';else echo $user->score; ?>" class="required number" alt="积分不能为空，为数字" />
            </div>
            <div class="unit" id="click_optional">
            	<?php if (empty($receiver->id)):?>
            	<label>&nbsp;</label>
				<a href="javascript:;" style="padding:2px;display:block;" id="click_a">点击查看详细信息</a>
            	<?php else:?>
            	<input type="hidden" name="Form[receive][optional]" value="1"/>
            	<?php endif;?>
			</div>
            <div id="receive" <?php if (empty($receiver->id))echo "style='display:none;'"?>>
            <div class="unit">
                <label>收货人姓名</label>
                <input type="text" name="Form[receive][receive_name]" value="<?php echo !empty($receiver->receive_name) ? $receiver->receive_name : '' ;  ?>"/><span style="color:red;">*</span>
            </div>
            <div class="unit">
                <label>收货人手机</label>
                <input type="text" maxlength="11" name="Form[receive][mobile_phone]" value="<?php echo $receiver->mobile_phone; ?>"/><span style="color:red;">*(手机或座机选填一个或多个)</span>
            </div>
            <div class="unit">
                <label>电话号码:</label>
                <?php $phone = explode('-', $receiver->phone); ?>
                <input type="text" name="Form[receive][phone-1]" style="width: 50px" value="<?php echo empty($phone[1]) ? '' : $phone[0] ?>"/>
                <span style="float:left;padding:2px;line-height:16px">-</span>
                <input type="text" name="Form[receive][phone-2]" style="width: 120px" value="<?php echo empty($phone[1]) ? $phone[0] : $phone[1] ?>"/>
                <span style="float:left;padding:2px;line-height:16px">-</span>
                <input type="text" name="Form[receive][phone-3]" style="width: 50px" value="<?php echo empty($phone[2]) ? '' : $phone[2] ?>"/><span style="color:red;">*</span>
                <span class="form-prompt">区号-电话号码-分机</span>
            </div>
            <div class="unit" >
                <label>客户地址:</label>
                <select class="combox" isAjax="true" url="<?php echo $this->createUrl("user/area"); ?>" default="<?php echo !isset($area_list['default']['1']) ? '0' : $area_list['default']['1'];?>" name="Form[receive][area_1]" ref="Form[receive][area_2]" >
                    <option value="0">选择省份</option>
                    <?php foreach ($area_list['1'] as $val):?>
                    <option value="<?php echo $val['id']?>"><?php echo $val['name']?></option>
                    <?php endforeach;?>
                </select>
                <span style="float:left;padding:2px;line-height:16px">省</span>
                <select class="combox" isAjax="true" url="<?php echo $this->createUrl("user/area"); ?>" default="<?php echo !isset($area_list['default']['2'])?'0':$area_list['default']['2']?>" name="Form[receive][area_2]" ref="Form[receive][area_id]">
                    <option value="0">选择城市</option>
                    <?php if (isset($area_list['default']['2'])) foreach ($area_list['2'] as $val):?>
                    <option value="<?php echo $val['id']?>"><?php echo $val['name']?></option>
                    <?php endforeach;?>
                </select>
                <span style="float:left;padding:2px;line-height:16px">市</span>
                <select class="combox" name="Form[receive][area_id]" default="<?php echo !isset($area_list['default']['3'])?'0':$area_list['default']['3']?>">
                    <option value="0">选择地区</option>
                    <?php if (isset($area_list['default']['3'])) foreach ($area_list['3'] as $val):?>
                    <option value="<?php echo $val['id']?>"><?php echo $val['name']?></option>
                    <?php endforeach;?>
                </select>
                <span style="float:left;padding:2px;line-height:16px">区</span><span style="color:red;">*</span>
            </div>
            <div class="unit">
                <label>详细地址:</label>
                <textarea name="Form[receive][street]" style="width: 300px; height: 50px;" ><?php  echo!empty($receiver->   street)?$receiver-> street:null   ?></textarea>
                <span style="color:red;">*</span><span class="form-prompt">不需要重复填写省/市/区</span>
            </div>
            <div class="unit" >
                <label>邮政编码:</label>
                <input type="text" maxlength="6" name="Form[receive][postalcode]" style="width: 80px"  value="<?php  echo!empty($receiver->postalcode)?$receiver->postalcode:null   ?>"   />
            	<span style="color:red;">*</span>
            </div>
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




















