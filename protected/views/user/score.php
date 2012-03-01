<title>我的积分</title>
<div class="score">
    <div>您当前的积分： <?php echo $score; ?></div>
    <div>
        积分记录：
        <select onchange="score_log(this)">
            <option value="30" <?php if ($time == 30) echo "selected"; ?>>最近30天的记录</option>
            <option value="90" <?php if ($time == 90) echo "selected"; ?>>最近90天的记录</option>
            <option value="365" <?php if ($time == 365) echo "selected"; ?>>最近1年的记录</option>
            <option value="730" <?php if ($time == 730) echo "selected"; ?>>最近2年的记录</option>
        </select>
    </div>
    <table class="score-log" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <th width="140" align="center">操作日期</th>
            <th width="80" align="center">积分数量</th>
            <th align="center">积分原因</th>
        </tr>
        <?php if ($logs !== array()): foreach ($logs as $log): ?>
        <tr>
            <td><?php echo date("Y-m-d H:i:s", $log->create_time); ?></td>
            <td><?php echo $log->score; ?></td>
            <td><?php echo $log->reason; ?></td>
        </tr>
        <?php endforeach; else: ?>
        <tr>
            <td colspan="3">无</td>
        </tr>
        <?php endif; ?>
    </table>
    <?php $this->widget("widget.Pager", array('pages'=>$pages)); ?>
    <div class="notice">提示：系统仅显示您两年之内的积分明细，更早的积分明细不再显示。</div>
</div>

    <title>积分说明</title>
<div class="score-explain">

    <div class="green">一、积分兑换</div>
    <div class="indent">绿浪每20积分可抵扣订单金额1元</div>

    <div class="green">二、获取原则</div>
    <div class="indent">
        <span class="green">1、</span>新客户送积分
        <div class="indent">如果您是第一次在绿浪下订单，即可获得额外<span class="green">赠送1500积分</span></div>
    </div>
    <div class="indent">
        <span class="green">2、</span>下单送积分
        <div class="indent">您每次下单都可以获得与订单金额相等的积分，如100元=100积分</div>
    </div>
    <div class="indent">
        <span class="green">3、</span>累积消费积分
        <div class="indent">您每累积消费5000元，额外<span class="green">赠送3000积分</span></div>
    </div>
    <div class="indent">
        <span class="green">4、</span>评价获得积分
        <div class="indent">您在绿浪“用户中心”对我们的拍摄质量评价一次获得100积分</div>
    </div>

    <div class="green">二、获取原则</div>
    <div class="indent"><span class="green">1、</span>从第二次订单开始，可以使用积分直接抵扣订单金额（RMB）</div>
    <div class="indent"><span class="green">2、</span>积分不可以直接兑换现金（RMB）</div>
</div>