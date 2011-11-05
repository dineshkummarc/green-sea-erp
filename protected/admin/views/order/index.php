<?php $this->widget('widget.Search', array(
    'panleStyle'=>'width: 100%;',
    'searchCondition'=>array(
        '订单号：'=>array('type'=>'text', 'name'=>'params[sn]', 'defaultValue'=>empty($params['sn']) ? '' : $params['sn'], 'alt'=>'支持模糊搜索'),
        '客户名：'=>array('type'=>'text', 'name'=>'params[user_name]', 'defaultValue'=>empty($params['user_name']) ? '' : $params['user_name']),
        '状态：'=>array('type'=>'select', 'name'=>'params[status]', 'defaultValue'=>empty($params['status']) ? '' : $params['status'],
            'options'=>array(
				'未付款'=>'1',
				'已付款、未收货'=>'2',
				'已付款、已收货、待排程'=>'3',
				'已付款、已收货、已排程'=>'4',
				'拍摄中'=>'5',
				'拍摄完成、修图中'=>'6',
				'修图完成、可下载'=>'7',
				'货物待寄出'=>'8',
				'货物已寄出'=>'9',
				'确认收货'=>'10',
            )
        ),
    ),
)); ?>
<div class="pageContent" width="100%" layoutH="65">
    <table class="list" width="100%">
    	<thead>
	        <tr>
	            <th width="30"><input type="checkbox" class="checkboxCtrl" group="id[]" /></th>
	            <th width="100" >客户名</th>
	            <th width="100">旺旺号</th>
	            <th width="50">订单号</th>
	            <th width="50">合同金额</th>
	            <th width="80"><a style="line-height:20px" target="navTab" rel="order-index" href="<?php echo $this->createUrl('order/index', array('sort'=>'time'));?>">下单时间</a></th>
	            <th width="100"><a style="line-height:20px" target="navTab" rel="order-index" href="<?php echo $this->createUrl('order/index', array('sort'=>'status'));?>">当前状态</a></th>
	            <th width="50">是否跟拍</th>
	            <th width="200">操作</th>
	        </tr>
        </thead>
        <tbody>
	        <?php if (empty($orders)): ?>
	        <tr>
	            <td colspan="10">无数据</td>
	        </tr>
	        <?php else: foreach ($orders as $order): ?>
	        <tr>
	            <td><input type="checkbox" name="id[]" value="<?php echo $order->id ?>" /></td>
	            <td><?php echo $order->user_name; ?></td>
	            <td><?php echo $order->User->wangwang; ?></td>
	            <td><?php echo $order->sn; ?></td>
	            <td><?php echo $order->total_price; ?></td>
	            <td><?php echo date('Y-m-d H:i:s', $order->create_time); ?></td>
	            <td><?php echo $order->getStatusText(); ?></td>
	            <td><?php echo $order->following == 1 ? "是" : '否'; ?></td>
	            <td>
	            	<a href="<?php echo $this->createUrl('order/goods', array('id'=>$order->id)); ?>" target="navTab" rel="order-goods">订单物品</a>
	                &nbsp;
	            	<a href="<?php echo $this->createUrl('order/ShootScene', array('id'=>$order->id)); ?>" target="dialog" mask="true" width="700" height="600" title="拍摄需求">需求</a>
	                &nbsp;
	            	<a href="<?php echo $this->createUrl('order/OrderDel', array('id'=>$order->id)); ?>" target="ajaxTodo" title="关联数据也将删除，确定删除？">删除</a>
	                &nbsp;
	            </td>
	        </tr>
	        <?php endforeach; endif; ?>
        </tbody>
    </table>
    <?php $this->widget('widget.Pager', array(
        'pages'=>$pages,
    )); ?>
</div>