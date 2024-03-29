<div class="panelBar">
<ul class="toolBar">
	<li><a class="icon" href="<?php echo $this->createUrl("schedule/index"); ?>" target="navTab" ><span>已排程订单列表</span></a></li>
	<li class="line">line</li>
	</ul>
</div>
<table class="list" width="100%" layoutH="54">
	<thead>
		<tr>
			<th width="30"><input type="checkbox" class="checkboxCtrl" group="id[]" /></th>
			<th width="150">订单</th>
			<th width="100">拍摄类型</th>
			<th width="120">入库时间</th>
			<th width="100">操作</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php if(!empty($orders)): foreach ($orders as $order):?>
		<tr>
			<td width="30"><input type="checkbox" name="id[]" value="<?php echo $order['id']; ?>" /></td>
	        <td ><?php echo "[ ".$order['sn']." ]".$order['user_name']; ?></td>
	        <td>
            <?php $typeList =$this->getShootType($order['id']); ?>
            <?php foreach ($typeList as $key=>$list):?>
            <?php if ($key > 0) echo "<br/>";?>
            <?php echo ShootType::getShootName($list['shoot_type'])?></option>
            <?php endforeach;?>
	        </td>
	        <td><?php $in_time = $this->getStorage($order['id']); echo !empty($in_time) ? date("Y-m-d H:i",$in_time) : '订单未入库'; ?></td>
			<td width="100">
				<a href="<?php echo $this->createUrl("schedule/edit", array('orderId'=>$order['id'])); ?>" target="dialog" width="400" height="500" mask="true" >添加</a>
			</td>
			<td></td>
		</tr>
		<?php endforeach;endif;?>
	</tbody>
</table>
<?php if(isset($pages)): $this->widget('widget.Pager', array(
        'pages'=>$pages,
    )); endif;?>


