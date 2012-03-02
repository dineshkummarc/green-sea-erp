<div>
    <?php if ($orderCount == 0):?>
        <p>您尚未创建订单，是否现在<a href="order/Editorder">创建</a>一个？</p>
    <?php elseif($order === null):?>
        <p>您的所有订单都已完成，是否继续<a href="order/Editorder">创建</a>？</p>
    <?php else:?>
        <h3 style="text-indent: -5em;">您有订单尚未完成：</h3>
        <p>订单号：<?php echo $order->sn; ?></p>
        <p>
                        当前状态：
            <ul>
                <?php foreach ($orderType as $id=>$type): ?>
                    <li style="text-indent: 5em;">
                        <?php echo $type;
                        if ($order->status >= $id){
                            echo "&nbsp;&nbsp;&nbsp;√";
                        }elseif ($order->status == $id-1){
                            echo "&nbsp;&nbsp;&nbsp;进行中";
                        }
                        ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </p>
    <?php endif;?>
</div>