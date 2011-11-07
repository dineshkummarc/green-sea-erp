<div class="step">添加拍摄物品</div>
<div class="step active">填写拍摄要求</div>
<div class="step">选择付款方式</div>
<div class="step">生成订单</div>
<div class="clear"></div>
<form action="<?php $this->createUrl("order/shootScene") ?>" method="post">
    <table class="table" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="2" style="background: #B3B3B3; text-align: center; color: #FFF; font-size: 14px;">拍摄要求</td>
        </tr>
        <?php if ( isset($selectedShootType[1]) || isset($selectedShootType[2]) ): // 存在模特拍摄的情况下 ?>
        <?php if (isset($selectedShootType[1])): ?>
        <tr>
            <td class="label">棚拍背景：</td>
            <td>
                <select name="Form[studio_shoot]">
                    <option value="白色">白色</option>
                    <option value="灰色">灰色</option>
                    <option value="黑色">黑色</option>
                    <option value="黄色">黄色</option>
                    <option value="蓝色">蓝色</option>
                    <option value="绿色">绿色</option>
                </select>
            </td>
        </tr>
        <?php elseif(isset($selectedShootType[2])): ?>
        <tr>
            <td class="label">外拍背景：</td>
            <td>
                <input type="text" name="Form[outdoor_shoot]" class="input" tip="默认" />
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td class="label">搭配注意事项：</td>
            <td>
                <div class="box-separation">
                    <?php foreach ($shootNotice as $key=>$val): ?>
                    <div <?php if ($key % 2 == 0): ?>class="separation"<?php endif; ?>>
                        <?php echo $key ?>、<?php echo $val['text']; ?>
                        <?php foreach ($val['options'] as $option): ?>
                        <?php if($option['type'] == 'radio'): ?>
                        <label><input name="<?php echo $option['name']; ?>" type="radio"
                            value="<?php echo $option['value']; ?>"
                            <?php echo (isset($option['checked']) && !isset($goods['shoot_notice'][$key])) || ( isset($goods['shoot_notice'][$key]) && $goods['shoot_notice'][$key] == $option['value']) ? 'checked' : ''; ?>
                            /><?php echo $option['text']; ?></label>
                        <?php endif; ?>
                        <?php endforeach; ?>
                        <?php echo isset($val['other']) ? "<br/>".$val['other'] : ''; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td class="label">是否跟拍：</td>
            <td>
                <label><input type="radio" name="Form[following]" value="0" checked />否</label>&nbsp;&nbsp;&nbsp;
                <label><input type="radio" name="Form[following]" value="1" onclick="alert('选择此项请联系客服')" />是</label>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="background: #B3B3B3; text-align: center; color: #FFF; font-size: 14px;">修图要求</td>
        </tr>
        <tr>
            <td class="label">图片宽度(高度自适应)：</td>
            <td>
                <?php foreach ($selectedShootType as $typeId=>$type): ?>
                <div><?php echo $type ?></div>
                <div>
                    简图宽度：<input type="text" name="Form[width][<?php echo $typeId ?>][width]" tip="750" class="input" style="width: 50px;" />px(像素)
                    细节图宽度：<input type="text" name="Form[width][<?php echo $typeId ?>][detail_width]" tip="750" class="input" style="width: 50px;" />px(像素)
                </div>
                <?php endforeach; ?>
            </td>
        </tr>
        <tr>
            <td class="label">修图标准：</td>
            <td>
                <label><input type="radio" name="Form[retouch]" value="1" checked /> 简修图</label>&nbsp;&nbsp;&nbsp;
                <a href="#">简修图说明</a><br/>
                <label><input type="radio" name="Form[retouch]" value="2" onclick="alert('选择此项请联系客服')" /> 精修图</label>&nbsp;&nbsp;&nbsp;
                <span class="error">此项为收费项目，请联系客服说明具体的需求</span>
            </td>
        </tr>
        <tr>
            <td class="label">修图其他要求：</td>
            <td>
                <textarea name="Form[other_comment]" class="text" style="width: 532px; height: 80px;"></textarea>
            </td>
        </tr>
        <tr>
            <td class="label">是否需要原图：</td>
            <td>
                <label><input type="radio" name="Form[artwork]" value="0" checked /> 否</label>&nbsp;&nbsp;&nbsp;
                <label><input type="radio" name="Form[artwork]" value="1" /> 是</label>&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="2" style="background: #B3B3B3; text-align: center; color: #FFF; font-size: 14px;">增值服务</td>
        </tr>
        <tr>
            <td class="label">方形主图：</td>
            <td>
                <label><input type="radio" name="Form[square]" value="0" checked /> 不需要</label>&nbsp;&nbsp;&nbsp;
                <br/>
                <label><input type="radio" name="Form[square]" value="1" onclick="alert('选择此项请联系客服')" /> 需要</label>&nbsp;&nbsp;<span class="error">选择此项请与客服联系</span>
            </td>
        </tr>
        <tr>
            <td class="label">排版：</td>
            <td>
                <label><input type="radio" name="Form[typesetting]" value="0" checked /> 不需要</label>&nbsp;&nbsp;&nbsp;
                <br/>
                <label><input type="radio" name="Form[typesetting]" value="1" onclick="alert('选择此项请联系客服')" /> 需要</label>&nbsp;&nbsp;<span class="error">选择此项请与客服联系</span>
            </td>
        </tr>
        <tr>
            <td class="label">同款不同色：</td>
            <td>
                <label><input type="radio" name="Form[diff_color]" value="0" checked /> 不需要</label>&nbsp;&nbsp;&nbsp;
                <br/>
                <label><input type="radio" name="Form[diff_color]" value="1" onclick="alert('选择此项请联系客服')" /> 需要</label>&nbsp;&nbsp;<span class="error">选择此项请与客服联系</span>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="background: #B3B3B3; text-align: center; color: #FFF; font-size: 14px;">订单备注</td>
        </tr>
        <tr>
            <td colspan="2"><textarea name="Form[memo]" class="text" style="width: 663px; height: 80px;"></textarea></td>
        </tr>
        <tr>
            <td class="label">运单号：</td>
            <td>
                <input type="text" name="Form[logistics_sn]" class="input" />
            </td>
        </tr>
    </table>
    <input type="hidden" name="id" value="<?php //echo $id; ?>" />
    <input type="button" value="返回上一步" onclick="window.location.href='<?php echo Yii::app()->request->urlReferrer; ?>'" />
    <input type="submit" value="完成，保存订单" />
</form>
