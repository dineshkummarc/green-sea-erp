<form action="<?php echo $this->createUrl("user/addUser") ?>" method="post" onsubmit="return validateReg()">
    <div class="info">
        <div>
            <label>客户名：</label><input type="text" name="Form[name]" class="input" />
        </div>
        <div>
            <label>手机号：</label><input type="text" name="Form[mobile_phone]" class="input" />
        </div>
        <div>
            <label>密码：</label><input type="password" name="Form[password]" class="input" value="" />
        </div>
        <div>
            <label>密码验证：</label><input id="repassword" type="password" class="input" value="" />
        </div>
        <div>
            <label>邮箱地址：</label><input type="text" name="Form[email]" class="input" />
        </div>
        <div>
            <label>所在地区：</label>
            <select name="Form[area_id]">
                <option value="0">请选择</option>
                <?php foreach ($areaList as $area): ?>
                <option value="<?php echo $area->id ?>"><?php echo $area->name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>旺旺号：</label><input type="text" name="Form[wangwang]" class="input" />
        </div>
        <div>
            <label>QQ号：</label><input type="text" name="Form[qq]" class="input" />
        </div>
        <div>
            <label>淘宝网店地址：</label><input type="text" name="Form[page]" class="input" />
        </div>
        <div style="margin-top: 10px; padding-left: 120px;">
            <input type="submit" value="提交" />
        </div>
    </div>
</form>