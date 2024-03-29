<div class="panelBar">
<ul class="toolBar">
	<li><a class="add" href="<?php echo $this->createUrl("order/goodsEdit", array('id'=>'','orderId'=>$orderId)); ?>" target="dialog" width="500" height="400" mask="true" title="添加订单物品" ><span>添加订单物品</span></a></li>
	<li class="line">line</li>
	<li><a class="delete" href="<?php echo $this->createUrl("order/goodsDel"); ?>" target="selectedTodo" title="确定删除选定物品？" rel="id[]" ><span>删除选定</span></a></li>
	</ul>
</div>
<div class="pageContent" width="100%" layoutH="27">
    <table class="list" width="100%">
    	<thead>
	        <tr>
	            <th width="30"><input type="checkbox" class="checkboxCtrl" group="id[]" /></th>
	            <th width="80">订单号</th>
	            <th width="30">季节</th>
	            <th width="30">性别</th>
	            <th width="150">类型</th>
	            <th width="80">拍摄类型</th>
	            <th width="80">拍摄风格</th>
	            <th width="50">数量</th>
	            <th>备注</th>
	            <th width="100">操作</th>
	        </tr>
        </thead>
        <tbody>
	        <?php if (empty($orderGoodsList)): ?>
	        <tr>
	            <td colspan="10">无数据</td>
	        </tr>
	        <?php else: foreach ($orderGoodsList as $orderGoods): ?>
	        <tr>
	            <td><input type="checkbox" name="id[]" value="<?php echo $orderGoods->id ?>" /></td>
	            <td><?php echo $orderGoods->sn; ?></td>
	            <td><?php
	            	$season = $orderGoods->season;
	            	if ($season == 0)echo "不限";
	            	if ($season == 1)echo "春秋";
	            	if ($season == 2)echo "夏";
	            	if ($season == 3)echo "冬";
	            ?></td>
	            <td><?php
	            	$sex = $orderGoods->sex;
	            	if ($sex == 0)echo "不限";
	            	if ($sex == 1)echo "男";
	            	if ($sex == 2)echo "女";
	            	if ($sex == 3)echo "情侣";
	            ?></td>
	            <td><?php echo $orderGoods->type_name;?></td>
	            <td><?php echo $orderGoods->ShootType->name; ?></td>
	            <td><?php if ($orderGoods->style == 0)echo "不限";else echo $orderGoods->Style->name; ?></td>
	            <td><?php echo $orderGoods->count; ?></td>
	            <td><?php echo $orderGoods->memo; ?></td>
	            <td>
	            	<a href="<?php echo $this->createUrl('order/goodsEdit', array('id'=>$orderGoods->id,'orderId'=>$orderId)); ?>" target="dialog" mask="true" width="500" height="400" title="修改物品">修改</a> |
	            	<a href="<?php echo $this->createUrl("order/goodsDel", array('id'=>$orderGoods->id)); ?>" target="ajaxTodo" title="确定删除该物品？">删除</a>
	            </td>
	        </tr>
	        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
<?php $this->widget('widget.Pager', array(
    'pages'=>$pages,
)); ?>
