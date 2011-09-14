<?php if ($order->status >= 6): ?>
<div class="title">作品下载</div>
<div class="box"><a href="#">作品下载</a></div>
<?php endif; ?>

<div class="title">排程</div>
<table class="table">
    <tr class="head">
        <td>拍摄时间</td>
        <td>拍摄内容</td>
        <td>状态</td>
        <td>模特</td>
        <td>摄影组</td>
    </tr>
    <tr>
        <td colspan="5">暂无排程信息</td>
    </tr>
</table>

<div class="title">订单金额</div>
<div class="box order-price">
<div>订单金额：<?php echo $order->total_price; ?></div>
<div>交易流水：<?php echo $order->id; ?></div>
<div>支付状态：<?php echo $order->statusText; ?></div>
<div>详情：</div>
</div>
<div class="title">拍摄内容</div>
<table class="table">
    <tr class="head">
        <td>类别</td>
        <td>季节</td>
        <td>性别</td>
        <td>风格</td>
        <td>拍摄类型</td>
        <td>数量</td>
    </tr>
    <?php foreach ($order->Goods as $goods): ?>
    <tr>
        <td><?php echo $goods->Type->name; ?></td>
        <td><?php echo $goods->seasonText; ?></td>
        <td><?php echo $goods->sexText; ?></td>
        <td><?php echo $goods->Style->name; ?></td>
        <td><?php echo $goods->ShootType->name; ?></td>
        <td><?php echo $goods->count ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<div class="title">拍摄要求</div>
<div class="box-separation">
    <div>是否跟拍：否</div>
    <div class="separation">模特：<?php echo $order->modelsName; ?></div>
    <div>棚拍背景：<?php echo $order->shootSceneData['pengpai']; ?></div>
    <div class="separation">外拍背景：<?php echo $order->shootSceneData['waipai']; ?></div>
    <div>拍摄、搭配和其他服务：<?php echo !empty($order->other_comment) ? $order->other_comment : '无' ?></div>
    <div class="separation">案例：无</div>
</div>

<div class="title">搭配注意事项</div>
<div class="box-separation">
    <div>
        1、拍上衣整体图时，是否需要盘起头发以便于展示领口特点？
        <label><?php echo $order->shootNoticeData[1] ?></label>
    </div>
    <div class="separation">
        2、如果刚好合适的话，我们是否可以用您自己的服饰来搭配您的衣服？
        <label><?php echo $order->shootNoticeData[2] ?></label>
    </div>
    <div>
        3、拍裤子时，是否可以用靴子搭配？
        <label><?php echo $order->shootNoticeData[3] ?></label><br />
        <span>注意：这会影响裤脚的局部展示。</span>
    </div>
    <div class="separation">
        4、拍外套时，是否必须要有敞开拉链的图片？
        <label><?php echo $order->shootNoticeData[4] ?></label>
    </div>
    <div>
        5、秋冬的短袖上衣搭配：
        <label><?php echo $order->shootNoticeData[5] ?></label>
    </div>
    <div class="separation">
        6、拍长上衣时，您是要搭配：
        <label><?php echo $order->shootNoticeData[6] ?></label>
    </div>
</div>

<div class="title">修图要求</div>
<div class="box-separation">
    <div>需要正方形主图：<?php echo isset($order->retouchDemandData['square']) ? '是' : '否'; ?></div>
    <div class="separation">修图要求：<?php echo isset($order->retouchDemandData['other']) ? $order->retouchDemandData['other'] : '绿浪修图'; ?></div>
    <div>图片高度：自适应</div>
    <div class="separation">图片宽度：<?php echo isset($order->retouchDemandData['width']) ? $order->retouchDemandData['width'] : '750px'; ?></div>
    <div>需要原图：<?php echo isset($order->retouchDemandData['artwork']) ? '是' : '否'; ?></div>
</div>

<div class="title">是否跟拍：<?php echo $order->following ? '是' : '否' ?></div>

<div class="title">物流信息</div>
<table class="table">
    <tr class="head">
        <td>送递方式</td>
        <td>快递单号</td>
        <td>送货时间</td>
        <td>送寄物品</td>
        <td>状态</td>
    </tr>
    <tr>
        <td colspan="5">还没有添加任何运单信息</td>
    </tr>
</table>