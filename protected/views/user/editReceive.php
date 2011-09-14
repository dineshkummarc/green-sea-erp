<form action="<?php echo $this->createUrl("user/receive") ?>" method="post" onsubmit="return validateReceive()">
    <input id="default" type="hidden" value="<?php echo $info->receive_id; ?>" />
    <input type="hidden" name="Form[id]" value="" />
    <div class="add-address">
        <div>
            <label style="color: #FC6210">新增收货地址</label>
            <span>电话号码、手机号选填一项,其余均为必填项</span>
        </div>
        <div>
            <label>收货人姓名:</label>
            <input type="input" name="Form[receive_name]" class="input" style="width: 100px" />
            <span class="form-require">*</span>
        </div>
        <div>
            <label>所在地区:</label>
            <select id="sheng">
                <option value="0"> </option>
                <?php foreach ($area as $sheng): ?>
                <option value="<?php echo $sheng['id'] ?>"><?php echo $sheng['name']; ?></option>
                <?php endforeach; ?>
            </select>
            省
            <select id="shi">
                <option value="0"> </option>
            </select>
            市
            <select id="qu" name="Form[area_id]">
                <option value="0"> </option>
            </select>
            区
            <span class="form-require">*</span>
        </div>
        <div>
            <label>详细地址:</label>
            <textarea name="Form[street]" class="text" style="width: 300px; height: 50px;"></textarea>
            <span class="form-require">*</span>
            <span class="form-prompt">不需要重复填写省/市/区</span>
        </div>
        <div>
            <label>邮政编码:</label>
            <input type="text" name="Form[postalcode]" class="input" style="width: 80px" />
            <span class="form-require">*</span>
        </div>
        <div>
            <label>电话号码:</label>
            <input type="text" name="Form[phone-1]" class="input" style="width: 50px" />
            -
            <input type="text" name="Form[phone-2]" class="input" style="width: 120px" />
            -
            <input type="text" name="Form[phone-3]" class="input" style="width: 50px" />
            <span class="form-require">*</span>
            <span class="form-prompt">区号-电话号码-分机</span>
        </div>
        <div>
            <label>手机号码:</label>
            <input type="text" name="Form[mobile_phone]" class="input" />
        </div>
        <div>
            <label>设为默认:</label>
            <input type="checkbox" name="Form[default]" value="1" />
        </div>
        <div style="text-align: center;">
            <input type="submit" value="保存" />
        </div>
    </div>
</form>
<strong style="color: #FC6210">已保存的有效地址</strong>
<table class="receive-list" cellpadding="0" cellspacing="0" width="100%">
    <tr class="head">
        <th width="64">收货人</th>
        <th width="130">所在地区</th>
        <th width="140">街道地址</th>
        <th width="60">邮编</th>
        <th>电话/手机</th>
        <th width="100">&nbsp;</th>
        <th width="70">操作</th>
    </tr>
    <?php if ($info->ReceiveAddresses !== null): foreach($info->ReceiveAddresses as $address): ?>
    <tr <?php if ($info->receive_id == $address->id) echo "bgcolor=''" ?>>
        <td align="center"><?php echo $address->receive_name; ?></td>
        <td><?php echo $address->Area->getFullArea(); ?></td>
        <td><?php echo $address->street; ?></td>
        <td align="center"><?php echo $address->postalcode; ?></td>
        <td><?php echo $address->phone . "<br/>" . $address->mobile_phone; ?></td>
        <td align="center"><?php
        if ($info->receive_id == $address->id) echo "<span style='color: #090'>默认地址</span>";
        else echo "<a href='".$this->createUrl("user/setDefault", array('id'=>$address->id))."'>设为默认</a>"; ?></td>
        <td><a id="edit" key="<?php echo $address->id ?>" href="#">修改</a> | <a id="del" href="<?php echo $this->createUrl("user/delReceive", array('id'=>$address->id)) ?>">删除</a></td>
    </tr>
    <?php endforeach;else: ?>
    <tr>
        <td colspan="7">无</td>
    </tr>
    <?php endif; ?>
</table>
<div>最多保存10个有效地址</div>