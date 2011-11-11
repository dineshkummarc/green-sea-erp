<div class="pageContent">
    <form action="<?php echo $this->createUrl(''); ?>" class="pageForm required-validate"
        enctype="multipart/form-data" onsubmit="return iframeCallback(this, dialogAjaxDone);" method="post">
        <div class="pageFormContent" layoutH="60">
            <input type="hidden" name="Form[id]" value="<?php echo !empty($orders->id) ? $orders->id : ''; ?>" />

            <div class="unit">
                <label>棚拍背景</label>
                <select name="Form[studio_shoot]" class="combox required" default="<?php echo !empty($orders->studio_shoot) ? $orders->studio_shoot : 0; ?>">
                    <option value="白色">白色</option>
                    <option value="灰色">灰色</option>
                    <option value="黑色">黑色</option>
                    <option value="黄色">黄色</option>
                    <option value="蓝色">蓝色</option>
                    <option value="绿色">绿色</option>
                </select>
            </div>
            <div class="unit">
            	<label>搭配注意事项</label>
            	<div class="box-separation">
                	<?php foreach ($shootNotice as $key=>$val): ?>
                    <div <?php if ($key % 2 == 0): ?>class="separation"<?php endif; ?>>
                        <?php echo $key ?>、<?php echo $val['text']; ?>
                        <?php foreach ($val['options'] as $option): ?>
                        <?php if($option['type'] == 'radio'): ?>
                        <input name="<?php echo $option['name']; ?>" type="radio"
                            value="<?php echo $option['value']; ?>"
                            <?php
                            	if ($shoot[$key] == $option['value'])echo "checked";
                            ?>
                            /><?php echo $option['text']; ?>
                        <?php endif; ?>
                        <?php endforeach; ?>
                        <?php echo isset($val['other']) ? "<br/>".$val['other'] : ''; ?>
                    </div>
                	<?php endforeach; ?>
                </div>
            </div>

            <div class="unit">
                <label>是否跟拍</label>
	            <input type="radio" name="Form[following]" value="0" <?php if ($orders->following == 0 || empty($orders->following)) echo "checked"; ?> />否&nbsp;&nbsp;&nbsp;
	            <input type="radio" name="Form[following]" value="1" <?php if ($orders->following == 1 ) echo "checked"; ?> />是
            </div>

            <div class="unit">
                <label>图片宽度(高度自适应)：</label>
                <div class="pic_width">
                <?php foreach ($shootTypeList as $key=>$type):?>
                <div><?php echo $shootType[$key] ?></div>
                <div>
                    简图宽度：<input style="float: none;width: 50px;" value="<?php echo $type['width']?>" type="text" name="Form[width][<?php echo $key ?>][width]" tip="750" class="input required"/>px(像素)&nbsp;&nbsp;
                    细节图宽度：<input style="float: none;width: 50px;" value="<?php echo $type['detail_width']?>" type="text" name="Form[width][<?php echo $key ?>][detail_width]" tip="750" class="input required"/>px(像素)
                </div>
                <?php endforeach;?>
                </div>
			</div>
            <div class="unit">
                <label>修图标准：</label>
                <input type="radio" name="Form[retouch]" value="1" <?php if ($orders->retouch == 1 || empty($orders->retouch)) echo "checked"; ?> /> 简修图&nbsp;<a href="#">简修图说明</a>
                <input type="radio" name="Form[retouch]" value="2" <?php if ($orders->retouch == 2 ) echo "checked"; ?> /> 精修图
			</div>
            <div class="unit">
                <label>修图其他要求：</label>
                <textarea class="require textInput valid" style="width: 400px; height: 100px;" maxlength="200" name="Form[other_comment]"><?php echo !empty($orders->other_comment) ? $orders->other_comment : ''; ?></textarea>
			</div>
            <div class="unit">
                <label>是否需要原图：</label>
                <label><input type="radio" name="Form[artwork]" value="0" <?php if ($orders->artwork == 0 || empty($orders->artwork)) echo "checked"; ?> /> 否</label>&nbsp;&nbsp;&nbsp;
                <label><input type="radio" name="Form[artwork]" value="1" <?php if ($orders->artwork == 1 ) echo "checked"; ?>/> 是</label>
			</div>

            <div class="unit">
                <label>方形主图：</label>
                <label><input type="radio" name="Form[square]" value="0" <?php if ($orders->square == 0 || empty($orders->square)) echo "checked"; ?>/> 不需要</label>&nbsp;&nbsp;&nbsp;
                <label><input type="radio" name="Form[square]" value="1" <?php if ($orders->square == 1 ) echo "checked"; ?> onclick="alert('选择此项请联系客服')" /> 需要</label>
			</div>
            <div class="unit">
                <label>排版： </label>
                <label><input type="radio" name="Form[typesetting]" value="0" <?php if ($orders->typesetting == 0 || empty($orders->typesetting)) echo "checked"; ?> /> 不需要</label>&nbsp;&nbsp;&nbsp;
                <label><input type="radio" name="Form[typesetting]" value="1" <?php if ($orders->typesetting == 1 ) echo "checked"; ?> onclick="alert('选择此项请联系客服')" /> 需要</label>
			</div>
            <div class="unit">
                <label>同款不同色：</label>
                <label><input type="radio" name="Form[diff_color]" value="0" <?php if ($orders->diff_color == 0 || empty($orders->diff_color)) echo "checked"; ?> /> 不需要</label>&nbsp;
                <label><input type="radio" name="Form[diff_color]" value="1" <?php if ($orders->diff_color == 1 ) echo "checked"; ?> onclick="alert('选择此项请联系客服')" /> 需要</label>
			</div>

            <div class="unit">
                <label>备注：</label>
                <textarea class="require textInput valid" style="width: 400px; height: 100px;" maxlength="200" name="Form[memo]"><?php echo !empty($orders->memo) ? $orders->memo : ''; ?></textarea>
			</div>
            <div class="unit">
                <label>价格：</label>
                <input type="text" maxlength="6" name="Form[total_price]" value="<?php echo !empty($orders->total_price) ? $orders->total_price : '0'; ?>" class="required" />
			</div>
            <div class="unit">
                <label>运单号：</label>
                <input type="text" name="Form[logistics_sn]" value="<?php echo !empty($orders->logistics_sn) ? $orders->logistics_sn : ''; ?>"/>
			</div>
            <div class="unit">
                <label>下载链接：</label>
                <input type="text" name="Form[down_url]" value="<?php echo !empty($orders->down_url) ? $orders->down_url : ''; ?>"/>
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