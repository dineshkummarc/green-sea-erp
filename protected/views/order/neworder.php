<link type="text/css" rel="stylesheet" href="css/fancybox/fancybox.css" />
<script type="text/javascript" src="js/jquery.fancybox.js"></script>
<script type="text/javascript">
var goods_type = <?php echo json_encode($goodsType); ?>;
var shoot_type = <?php echo json_encode($shootType) ?>;
var styles = <?php echo json_encode($styles); ?>;
var models = <?php echo json_encode($models); ?>;
$(orderInit);
</script>
<form id="orderform" method="post" action="" enctype="multipart/form-data" onsubmit="return orderValidator()">
    <div class="step">填写拍摄需求</div>
    <div class="step">选择付款方式</div>
    <div class="step">生成订单</div>
    <div class="clear"></div>

    <div class="title">选择拍摄基地</div>
    <div class="box-separation">
        <div class="separation">拍摄基地：  嘉兴</div>
        <div>
            快递地址：<br />
            <span style="padding-left: 2em;">嘉兴市 南湖区城南路 科创中心 8栋115室 绿浪（收）</span><br />
            <span style="padding-left: 2em;">电话：400-000-9411</span><br />
            <span style="padding-left: 2em;">邮编：314000</span>
        </div>
    </div>

    <div class="title">拍摄内容</div>
    <table id="goodsTable" class="table" cellpadding="0" cellspacing="0">
        <tr class="head">
            <td width="85">类别</td>
            <td width="60">季节</td>
            <td width="60">性别</td>
            <td width="100">拍摄类型</td>
            <td width="60">拍摄风格</td>
            <td width="160">拍摄数量（不含搭配用服饰）</td>
            <td width="30">金额</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <select id="goods_type" name="Form[goods][0][type_id]">
                    <?php foreach ($goodsType as $type): ?>
                    <option value="<?php echo $type['id'] ?>"><?php echo $type['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <br/>
            </td>
            <td>
                <select id="goods_season" name="Form[goods][0][season]">
                    <option value="0">不限</option>
                    <option value="0">春秋</option>
                    <option value="0">夏</option>
                    <option value="0">冬</option>
                </select>
            </td>
            <td>
                <select id="goods_sex" name="Form[goods][0][sex]">
                    <option value="0">不限</option>
                    <option value="0">男</option>
                    <option value="0">女</option>
                </select>
            </td>
            <td>
                <select id="shoot_type" name="Form[goods][0][shoot_type_id]">
                    <?php foreach ($shootType as $type): ?>
                    <option value="<?php echo $type['id']; ?>"><?php echo $type['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <select id="goods_style" name="Form[goods][0][style_id]" filter="styles">
                    <option value="0">不限</option>
                    <?php foreach ($styles as $style): ?>
                    <option value="<?php echo $style['id']; ?>"><?php echo $style['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <input id="goods_count" type="text" name="Form[goods][0][count]" class="input" style="width: 100px;" value="0" />
            </td>
            <td class="price">--</td>
        </tr>
    </table>

    <div class="prompt">
        <a id="addOrderGoods" class="button" href="javascript: void(0);" style="margin-top: 6px;"><span>添加一项</span></a>
        如有不同的商品类别，请点击添加。物品类别的区分，对我们选择模特，提高拍摄质量有很大帮助。
    </div>
    <div id="total-price" class="total-price"><span class="error"></span>总金额：<span class="price">--</span><input type="hidden" name="Form[total_price]" value="0" /></div>

    <div class="title">拍摄风格及模特</div>
    <div id="styles" class="styles">
        您选择的风格：
        <?php foreach ($styles as $style): ?><span><?php echo $style['name']; ?></span><?php endforeach; ?>
    </div>
    <div style="text-indent: 2em; padding: 10px 0;">
    选择单一模特的，到货后7天内交图（模特会有生病，事假等各种不可控因素，所以时间要长），选择两个模特的，到货后4天内交图，选择三个及以上的，到货后3天内交图（<span style="color: red;">我们会在您选择的模特中选一个为您拍摄</span>)，从到货后第二天开始计算时间，每推迟一天，绿浪向您赔付20元。
    </div>
    <div class="model-container">
        <div class="models">
            <ul>
                <?php foreach ($models as $model): ?>
                <li>
                    <a id="<?php echo $model['id']; ?>" target="showModel" href="javascript: void(0);">查看</a>
                    <label>
                        <input type="checkbox" name="Form[models][]" value="<?php echo $model['id']; ?>"
                        style="margin-right: 5px;" /><?php echo $model['nick_name']; ?>
                    </label>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div id="model-info-container" class="model-info-container">
            <?php $model = $models[0]; ?>
            <div class="model-info">
                <div id="nick_name"><label>昵称：</label><span><?php echo $model['nick_name']; ?></span></div>
                <div id="china_name"><label>中文名：</label><span><?php echo $model['china_name']; ?></span></div>
                <div id="english_name"><label>英文名：</label><span><?php echo $model['english_name']; ?></span></div>
                <div id="height"><label>身高(CM)：</label><span><?php echo $model['height']; ?></span></div>
                <div id="weight"><label>体重(KG)：</label><span><?php echo $model['weight']; ?></span></div>
                <div id="chest"><label>胸围(CM)：</label><span><?php echo $model['chest']; ?></span></div>
                <div id="waist"><label>腰围(CM)：</label><span><?php echo $model['waist']; ?></span></div>
                <div id="hip"><label>臀围(CM)：</label><span><?php echo $model['hip']; ?></span></div>
                <div id="shoes"><label>鞋码(欧码)：</label><span><?php echo $model['shoes']; ?></span></div>
                <div id="sign_up"><label>是否签约：</label><span><?php echo $model['sign_up'] ? '是' : '否'; ?></span></div>
            </div>
            <div id="head_img" class="model-image">
                <a href="<?php echo $model['head_img']; ?>" class="imgWindow" title="点击查看大图"><img src="<?php echo $model['head_img_thumb']; ?>" /></a>
            </div>
        </div>
    </div>

    <div class="prompt">
        <a id="addExample" class="button" href="#addExampleHtml" style="margin-top: 6px;"><span>添加案例</span></a>
        <div style="display: none;">

        </div>
        上传您喜欢的案例，有助于绿浪摄影师理解您拍摄要求，您可以在此上传图片或者链接。
    </div>

    <div class="title">拍摄，搭配等其他要求和注意事项</div>
    <table class="neworder">
        <tr>
            <td width="100">棚拍背景：</td>
            <td>
                <select name="Form[shoot_scene][pengpai]">
                    <option value="白色">白色</option>
                    <option value="灰色">灰色</option>
                    <option value="黑色">黑色</option>
                    <option value="黄色">黄色</option>
                    <option value="蓝色">蓝色</option>
                    <option value="绿色">绿色</option>
                </select>
            </td>
        </tr>
        <tr>
            <td width="100">外拍场景：</td>
            <td><input type="text" name="Form[shoot_scene][waipai]" class="input" tip="默认" /></td>
        </tr>
        <tr>
            <td width="100" valign="top">其他要求：</td>
            <td>
                <textarea name="Form[other_comment]" class="text" style="width: 522px; height: 80px;"></textarea>
            </td>
        </tr>
        <tr>
            <td width="100" valign="top">搭配注意事项：</td>
            <td>
                <div class="box-separation">
                    <?php foreach ($shootNotice as $key=>$val): ?>
                    <div <?php if ($key % 2 == 0): ?>class="separation"<?php endif; ?>>
                        <?php echo $key ?>、<?php echo $val['text']; ?>
                        <?php foreach ($val['options'] as $option): ?>
                        <?php if($option['type'] == 'radio'): ?>
                        <label><input name="<?php echo $option['name']; ?>" type="radio"
                            value="<?php echo $option['value']; ?>" <?php echo isset($option['checked']) ? 'checked' : ''; ?> /><?php echo $option['text']; ?></label>
                        <?php endif; ?>
                        <?php endforeach; ?>
                        <?php echo isset($val['other']) ? "<br/>".$val['other'] : ''; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </td>
        </tr>
    </table>

    <div class="title">修图要求</div>
    <table class="neworder">
        <tr>
            <td width="100">图片尺寸： </td>
            <td>宽：<input type="text" name="Form[retouch_demand][width]" class="input" style="width: 50px;" tip="750" />px&nbsp;&nbsp;&nbsp;&nbsp;[高：自适应]</td>
        </tr>
        <tr>
            <td width="100" valign="top">需要方形主图：</td>
            <td>
                <input type="checkbox" name="Form[retouch_demand][square]" value="1" />
                <div class="info" style="padding-left: 0; padding-top: 5px;">正方形主图主要用于淘宝商品主图，绿浪视觉为您提供有偿方形主图编辑服务，选择此项每张加收2元。</div>
            </td>
        </tr>
        <tr>
            <td width="100" valign="top">是否需要原图： </td>
            <td>
                <input type="checkbox" name="Form[retouch_demand][artwork]" value="1" />
                <div class="info" style="padding-left: 0; padding-top: 5px;">选择此项绿浪视觉将为您提供宽2800px的大图，格式为jpg（将刻成光盘随您的宝贝一共快递给您）此项服务需加收20元。</div>
            </td>
        </tr>
        <tr>
            <td width="100">其他要求：</td>
            <td><input type="text" name="Form[retouch_demand][other]" class="input" tip="无" /></td>
        </tr>
    </table>

    <div class="title">是否跟拍</div>
    <div class="info">提示：如果选择跟拍，我们将会在拍摄前一天通知您</div>
    <table class="neworder">
        <tr>
            <td width="100">我要跟拍：</td>
            <td><input type="checkbox" name="Form[following]" value="1" /></td>
        </tr>
    </table>

    <div class="prompt">
        <a id="submit" class="button" href="javascript: void(0);" style="margin-top: 6px;"><span>保存并进入下一步</span></a>
    </div>
</form>