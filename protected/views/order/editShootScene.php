<form action="<?php $this->createUrl("") ?>" method="post">
    <table class="table" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="2" style="background: #B3B3B3; text-align: center; color: #FFF; font-size: 14px;">订单备注</td>
        </tr>
        <tr>
            <td colspan="2"><textarea name="memo" class="text" style="width: 663px; height: 80px;"><?php echo $memo?></textarea></td>
        </tr>
    </table>
    <input type="hidden" name="id" value="<?php echo $id; ?>" />
    <input type="submit" value="完成，保存" />
</form>
