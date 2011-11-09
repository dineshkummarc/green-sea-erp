
<div class="pageContent">
    <form action="<?php echo $this->createUrl(''); ?>"  class="pageFormrequiredd-validate" onsubmit="return validateCallback(this, dialogAjaxDone);" method="post">
        <div class="pageFormContent" layoutH="60">
            <div class="unit">
                <label>收货人姓名</label>
                <span class="unit"><input type="text" name="Form[receive_name]" class="required" value="<?php  echo!empty($receiver->receive_name)?$receiver->receive_name:null   ?>" alt="收货人姓名不能为空" /></span>
            </div>

            <div class="unit" >
                <label>所在省:</label>
	            <select class="combox" name="Form[area_1]" ref="w_combox_city" default="5" refUrl="<?php echo $this->createUrl('user/area');?>&type={value}">

					<?php if ($area_list != null):?>
						<?php foreach ($area_list['1'] as $val):?>
						<option value="<?php echo $val['id']?>" <?php if ($val['id'] == $area_list['default']['1'])echo "selected";?>><?php echo $val['name']?></option>
						<?php endforeach;?>
					<?php else:?>
						<option value="all">所有省市</option>
						<?php foreach ($areas as $val):?>
						<option value="<?php echo $val->id?>" ><?php echo $val->name?></option>
						<?php endforeach;?>
					<?php endif;?>
				</select>
            </div>

            <div class="unit">
	            <label>所在市:</label>
				<select class="combox" name="Form[area_2]" id="w_combox_city" ref="w_combox_area" refUrl="<?php echo $this->createUrl('user/area');?>&type={value}">
					<?php if ($area_list != null):?>
						<?php foreach ($area_list['2'] as $val):?>
						<option value="<?php echo $val['id']?>" <?php if ($val['id'] == $area_list['default']['2'])echo "selected";?>><?php echo $val['name']?></option>
						<?php endforeach;?>
					<?php else:?>
					<option value="all">所有城市</option>
					<?php endif;?>
				</select>
            </div>

            <div class="unit">
	            <label>所在区:</label>
				<select class="combox" name="Form[area_id]" id="w_combox_area">
					<?php if ($area_list != null):?>
						<?php foreach ($area_list['3'] as $val):?>
						<option value="<?php echo $val['id']?>" <?php if ($val['id'] == $area_list['default']['3'])echo "selected";?>><?php echo $val['name']?></option>
						<?php endforeach;?>
					<?php else:?>
					<option value="all">所有区县</option>
					<?php endif;?>
				</select>
            </div>

            <div class="unit">
	            <label>电话号码:</label>
	            <?php $phone = explode('-', $receiver->phone); ?>
	            <input type="text" name="Form[phone-1]" class="required" style="width: 50px" value="<?php echo empty($phone[1]) ? '' : $phone[0] ?>"/>
	            <span style="float: left;">-</span>
	            <input type="text" name="Form[phone-2]" class="required" style="width: 120px" value="<?php echo empty($phone[1]) ? $phone[0] : $phone[1] ?>"/>
	            <span style="float: left;">-</span>
	            <input type="text" name="Form[phone-3]" style="width: 50px" value="<?php echo empty($phone[2]) ? '' : $phone[2] ?>"/>
	            <span class="form-require">*</span>
	            <span class="form-prompt">区号-电话号码-分机</span>
        </div>


          <div class="unit">
	            <label>详细地址:</label>
	            <textarea name="Form[street]" class="required" style="width: 300px; height: 50px;" ><?php  echo!empty($receiver->	street)?$receiver->	street:null   ?></textarea>
	            <span class="form-prompt">不需要重复填写省/市/区</span>
        	</div>

            <div class="unit" >
            <label>邮政编码:</label>
            <input type="text" name="Form[postalcode]" class="required" style="width: 80px"  value="<?php  echo!empty($receiver->postalcode)?$receiver->postalcode:null   ?>"   />
            </div>
            <input type="hidden" name="Form[id]" value="<?php echo $user->id; ?>" />
            <?php if (!empty($user->id)): ?>
            <div class="unit">
                <label>客户编号</label>
                <span class="unit">P<?php echo substr(strval($user->id + 1000),1,3); ?></span>
            </div>
            <?php endif; ?>
            <div class="unit">
                <label>用户名</label>
                <span class="unit"><input type="text" name="Form[name]" class="required" value="<?php echo $user->name; ?>" alt="客户名不能为空" /></span>
            </div>
            <div class="unit">
                <?php if (!empty($user->password)): ?>
                <label>密码重置</label>
                <input id="pwdequal" type="text" name="Form[password]"  />
                <?php else: ?>
                <label>密码</label>
                <input id="pwdequal" type="text" name="Form[password]" class="required" alt="密码不能为空" />
                <?php endif; ?>
            </div>
            <div class="unit">
                <label>确认密码</label>
                <input type="text" id="rePwd" equalto="#rePwd" />
            </div>
            <div class="unit">
                <label>旺旺号</label>
                <input type="text" name="Form[wangwang]" value="<?php echo $user->wangwang; ?>" />
            </div>
           <div class="unit">
                <label>QQ号</label>
                <input type="text" name="Form[qq]" value="<?php echo $user->qq; ?>" class="required" alt="QQ号码不能为空" />
            </div>
            <div class="unit">
                <label>手机号码</label>
                <input type="text" name="Form[mobile_phone]" value="<?php echo $user->mobile_phone; ?>" class="required" alt="手机不能为空" />
            </div>
            <div class="unit">
                <label>淘宝网地址</label>
                <input type="text" name="Form[page]" value="<?php echo $user->page; ?>" />
            </div>
            <div class="unit">
                <label>会员积分</label>
                <input type="text" name="Form[score]" value="<?php echo $user->score; ?>" class="required number" alt="积分不能为空，为数字" />
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






















