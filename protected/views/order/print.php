<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=7" />
        <style type="text/css">
        body
        {
            margin: 0;
            padding: 0;
            font-size: 14px;
            font-family: Simsun;
        }
        h2
        {
            margin-top: 0;
            margin-bottom: 10px;
        }
        h4
        {
            margin: 10px 0;;
        }
        table
        {
            width: 100%;
            border-top: 1px solid #000;
            border-left: 1px solid #000;
        }
        table td
        {
            border-right: 1px solid #000;
            border-bottom: 1px solid #000;
        }
        .spacing
        {
            clear: both;
            width: 100%;
            height: 20px;
        }
        .container
        {
            width: 16cm;
            margin: 0 auto;
        }
        .table td
        {
            padding: 5px 10px;
        }
        tr.head
        {
            font-weight: bold;
        }
        td.head
        {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            color: #FFF;
            background: #B3B3B3;
        }
        .page-break
        {
            page-break-after:always;
        }
        .page-margin
        {
            height: 1.4cm;
        }
        </style>
        <title><?php echo Yii::app()->name; ?></title>
    </head>
    <body onclick="window.print()" title="点击开始打印" style="cursor: pointer;">
        <div class="container">
            <div class="page-margin"></div>
            <h2 style="text-align: center;">拍摄需求清单</h2>
            <h4 style="text-align: right;">订单号：<span style="text-decoration: underline; font-weight: normal;">&nbsp;<?php echo $order->sn; ?>&nbsp;</span></h4>
            <table class="table" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td class="head" colspan="6">基本资料</td>
                </tr>
                <tr>
                    <td width="15%">客户名</td>
                    <td width="25%"><?php echo $order->User->name; ?></td>
                    <td width="6%">QQ</td>
                    <td width="20%"><?php echo $order->User->qq; ?></td>
                    <td width="14%">手机号</td>
                    <td><?php echo $order->User->mobile_phone; ?></td>
                </tr>
                <tr>
                    <td width="15%">旺旺号</td>
                    <td colspan="2"><?php echo $order->User->wangwang; ?></td>
                    <td width="15%">运单号</td>
                    <td colspan="2"><?php echo $order->logistics_sn; ?></td>
                </tr>
                <tr>
                    <td>店铺地址</td>
                    <td colspan="5"><?php echo $order->User->page; ?></td>
                </tr>
                <tr>
                    <td>地&nbsp;&nbsp;址</td>
                    <td colspan="5"><?php echo $order->receive_address; ?></td>
                </tr>
            </table>

            <div class="spacing"></div>

            <table class="table" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td class="head" colspan="6">拍摄内容</td>
                </tr>
                <tr class="head">
                    <td width="16%">类别</td>
                    <td width="16%">季节</td>
                    <td width="16%">性别</td>
                    <td width="16%">拍摄类型</td>
                    <td width="16%">拍摄风格</td>
                    <td width="16%">拍摄数量</td>
                </tr>
                <?php foreach($goodsList as $goods): ?>
                <tr class="content">
                    <td><?php echo $goods->type_name; ?></td>
                    <td><?php echo $season[$goods->season]; ?></td>
                    <td><?php echo $sex[$goods->sex]; ?></td>
                    <td><strong><?php echo $shootType[$goods->shoot_type]; ?></strong></td>
                    <td><?php echo $style[$goods->style]; ?></td>
                    <td><strong><?php echo $goods->count; ?></strong></td>
                </tr>
                <tr class="content">
                    <td>订单备注：</td>
                    <td colspan="5"><?php echo $goods->memo; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>

            <div class="spacing"></div>

            <table class="table" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td class="head" colspan="6">拍摄模特</td>
                </tr>
                <tr class="head">
                    <td width="16%">昵称</td>
                    <td width="16%">身高(cm)</td>
                    <td width="16%">体重(kg)</td>
                    <td width="16%">三围(cm)</td>
                    <td width="16%">鞋码(欧码)</td>
                    <td width="16%">等级(S,A,B)</td>
                </tr>
                <?php foreach ($models as $model): ?>
                <tr>
                    <td><strong><?php echo $model->nick_name; ?></strong></td>
                    <td><?php echo $model->height; ?></td>
                    <td><?php echo $model->weight; ?></td>
                    <td><?php echo $model->chest . "," . $model->waist . "," . $model->hip; ?></td>
                    <td><?php echo $model->shoes; ?></td>
                    <td>B</td>
                </tr>
                <?php endforeach; ?>
            </table>

            <div class="page-break"></div>
            <div class="page-margin"></div>

            <table class="table" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td class="head" colspan="2">拍摄需求</td>
                </tr>
                <tr>
                    <td width="18%">棚拍背景</td>
                    <td><strong><?php echo $order->studio_shoot; ?></strong></td>
                </tr>
                <tr>
                    <td>外拍场景</td>
                    <td><strong><?php echo $order->outdoor_shoot; ?></strong></td>
                </tr>
                <tr>
                    <td valign="top">搭配注意事项</td>
                    <td>
                        <?php if (empty($order->shoot_notice)): ?>
                        无
                        <?php else: ?>
                        <div style="width: 450px;">
                            <?php foreach ($order->shoot_notice as $key=>$notice): ?>
                            <div style="padding: 5px 0 5px 10px; font-size: 12px; border: 1px solid #C6C5C5; <?php if (count($order->shoot_notice) != $key) echo "border-bottom: 0;"; ?> <?php if ($key % 2 == 0) echo "background: #EAEAEA" ?>">
                            <?php printf($shootNotice[$key]['dbtext'], $notice); ?>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        <?php ?>
                    </td>
                </tr>
            </table>

            <div class="spacing"></div>

            <table class="table" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td class="head" colspan="2">修图要求</td>
                </tr>
                <tr>
                    <td valign="top">图片宽度</td>
                    <td>
                        <?php foreach($order->width as $key=>$width): ?>
                        <div style="font-weight: bold; margin-bottom: 5px;"><?php echo $shootType[$key]; ?></div>
                        <div style="margin-bottom: 5px;">
                            简图宽度：<strong><?php echo $width['width'] ?>px(像素)</strong>&nbsp;&nbsp;细节图宽度：<strong><?php echo $width['detail_width'] ?>px(像素)</strong><br/>
                        </div>
                        <?php endforeach; ?>
                    </td>
                </tr>
                <tr>
                    <td>修图其他需求</td>
                    <td><?php echo empty($order->other_comment) ? "无" : $order->other_comment; ?></td>
                </tr>
                <tr>
                    <td width="18%">增值服务</td>
                    <td>
                        需要方形主图：<strong><?php echo $order->square == 1 ? "是" : "否" ?></strong>&nbsp;&nbsp;
                        修图标准：<strong><?php echo $order->retouch == 1 ? "简修" : "精修" ?></strong>&nbsp;&nbsp;
                        是否跟拍：<strong><?php echo $order->following == 1 ? "是" : "否" ?></strong>&nbsp;&nbsp;
                        排版：<strong><?php echo $order->typesetting == 1 ? "需要" : "不需要" ?></strong>&nbsp;&nbsp;
                        同款不同色：<strong><?php echo $order->diff_color == 1 ? "需要" : "不需要" ?></strong>
                    </td>
                </tr>
                <tr>
                    <td width="18%">订单备注</td>
                    <td><strong><?php echo empty($order->memo) ? "无" : $order->memo; ?></strong></td>
                </tr>
            </table>

            <div class="spacing"></div>

            <div style="font-weight: bold; line-height: 30px;">备&nbsp;&nbsp;&nbsp;&nbsp;注：</div>
            <div style="line-height: 30px;">1、所有材料必须连同本说明书交由拍摄部门，签字后生效，不得修改。</div>
            <div style="line-height: 30px;">2、有其他要求，可与业务员或直接与拍摄部门取得联系，我们将根据您的要求，竭力为您服务，价格另议。</div>
            <div style="line-height: 30px;">3、本说明书将作为合同附件，且具有相同法律效益。</div>
            <div style="line-height: 30px;">
                客户签认：<span style="margin-left: 20em;">日期：</span>
            </div>
            <div style="line-height: 30px;">
                绿浪签认：<span style="margin-left: 20em;">日期：</span>
            </div>
        </div>
    </body>
</html>