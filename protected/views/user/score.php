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