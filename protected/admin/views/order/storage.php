<script type="text/javascript">
function status(val)
{
	function _getIds(selectedIds, targetType){
		var ids = "";
		var $box = targetType == "dialog" ? $.pdialog.getCurrent() : navTab.getCurrentPanel();
		$box.find("input:checked").filter("[name='"+selectedIds+"']").each(function(i){
			var val = $(this).val();
			ids += i==0 ? val : ","+val;
		});
		return ids;
	}
	var val = val;
	var rel = $this.attr("rel") || "ids";
	$.ajax({
		type: 'POST',
		url: '<?php echo $this->createUrl('order/StorageGoodsStatus');?>',
		data: {id: rel, status: val},
		dataType:"json",
		cache: false,
		success: DWZ.ajaxDone,
		error: DWZ.ajaxError
	});
}
</script>
<div class="pageContent" width="100%" layoutH="0">
	<div class="panel" [defH="200"|minH="100"]>
		<h1>仓储信息<?php if ($storage->out_time != 0):?>
		            <a class="edit" href="<?php echo $this->createUrl("order/Storageedit",array('id'=>$storage->id)) ?>" target="dialog" width="400" height="190" title="修改仓储"><span>修改仓储</span></a>
					<a class="delete" end="true" tabId="order-storage" href="<?php echo $this->createUrl("order/StorageDel",array('id'=>$storage->id)); ?>" target="ajaxTodo" title="关联物品也将删除，确定删除仓储？"><span>删除仓储</span></a>
    		<?php endif;?>
    	</h1>
	    <div class="pageFormContent">

		    <div class="unit">
		    	<label>订单：</label>
		    	<label style="width:200px"><?php echo $storage->Order->user_name.' 【 '.$storage->Order->sn.' 】 '?></label>
		    </div>
		    <div class="unit">
		    	<label>入库人：</label>
		    	<label style="width:200px"><?php echo $storage->Admin->name . ' - '.'【'.$storage->Admin->number.'】'?></label>
		    </div>
		    <div class="unit">
		    	<label>入库时间：</label>
		    	<label style="width:auto" ><?php echo date('m-d H:i',$storage->in_time)?>&nbsp;&nbsp;&nbsp;&nbsp;
		    	<a href="<?php echo $this->createUrl('order/intime',array('id'=>$storage->id));?>" title="入库时间" height="150" width="400" target="dialog">修改</a>
		    	</label>
		    </div>
	    	<?php if ($storage->out_time == 0):?>
	    	<script type="text/javascript">
			$('a#submit').click(function(){
				$out_sn = $("#out_sn").val();
				$sn_name = $("#sn_name").val();
				$(this).attr('href',"<?php echo $this->createUrl('order/StorageOut', array('id'=>$storage->id));?>"+"&out_sn="+$out_sn+"&sn_name="+$sn_name)
			});
	    	</script>
		    <div class="unit">
		    	<label>物流公司：</label><input type="text" id="sn_name" value="" />
		    </div>
		    <div class="unit">
		    	<label>出库运单号：</label>
		    	<input type="text" id="out_sn" value="" />&nbsp;&nbsp;&nbsp;
		    	<a style="line-height:20px" id="submit" href="" target="ajaxTodo">提交</a>
		    </div>
		    <?php else:?>
		    <div class="unit">
		    	<label>出库时间：</label>
		    	<label style="width:200px"><?php echo date('m-d H:i',$storage->out_time)?></label>
		    </div>
	    	<?php endif;?>
		    <?php if ($storage->out_time != 0):?>
		    <div class="unit">
		    	<label>出库运单号：</label>
		    	<label style="width:200px"><?php echo $storage->out_sn?></label>
		    </div>
		    <?php endif;?>
	    </div>
	</div>
    <div class="panelBar">
        <ul class="toolBar">
            <li><a class="add" href="<?php echo $this->createUrl("order/StorageGoods",array('storage_id'=>$storage->id,'order_sn'=>$storage->Order->sn,'orderId'=>$orderId)) ?>" target="dialog" width="400" height="250" title="添加物品"><span>添加物品</span></a></li>
            <li><a class="delete" href="<?php echo $this->createUrl("order/StorageGoodsDel"); ?>" target="selectedTodo" title="确定删除选定数据吗？" rel="id[]" ><span>删除选定</span></a></li>
            <li class="line">line</li>
			<li><a class="icon" href="<?php echo $this->createUrl("order/StorageGoodsExcel",array('order_id'=>$storage->order_id)); ?>" target="dwzExport" targetType="navTab" title="确实要导出这些记录吗?" rel="id[]"><span>导出选定EXCEL</span></a></li>
			<li><a class="icon" href="<?php echo $this->createUrl("order/StorageGoodsExcel",array('order_id'=>$storage->order_id, 'id'=>'all', 'storage_id'=>$storage->id)); ?>" target="dwzExport" rel="all" targetType="navTab"><span>导出全部EXCEL</span></a></li>
			<li class="line">line</li>
			<li><a class="icon"><span><?php echo "入库 ".$pages->itemCount." 件";  echo !empty($shootCount) ? '&nbsp;&nbsp;已拍摄  '.$shootCount.' 件' : ''; ?></span></a></li>
			<li><a title="确定这些物品已拍吗?" target="selectedTodo" rel="id[]" href="<?php echo $this->createUrl('order/StorageGoodsStatus');?>" class="edit"><span>批量确认已拍摄状态</span></a></li>
        </ul>
    </div>
    <table class="list" width="100%" layoutH="165">
    	<thead>
	        <tr>
	            <th width="30"><input type="checkbox" class="checkboxCtrl" group="id[]" /></th>
	            <th width="80" >物品流水号</th>
	            <th width="150" >物品名称</th>
	            <th width="80" >拍摄类型</th>
	            <th width="80" >是否已拍</th>
	            <th>操作</th>
	        </tr>
        </thead>
        <tbody>
	        <?php if (empty($storageGoodsList)): ?>
	        <tr>
	            <td colspan="10">无数据</td>
	        </tr>
	        <?php else: foreach ($storageGoodsList as $storageGoods): ?>
	        <tr>
	            <td><input type="checkbox" name="id[]" value="<?php echo $storageGoods->id ?>" /></td>
	            <td><?php echo $storageGoods->sn?></td>
	            <td><?php echo $storageGoods->name?></td>
	            <td><?php echo !empty($storageGoods->shoot_type) ? ShootType::getShootName($storageGoods->shoot_type) : ''; ?></td>
	            <td><?php echo $storageGoods->is_shoot == 0 ? "否" : "是"?></td>
	            <td>
	            	<a href="<?php echo $this->createUrl("order/StorageGoods",array('id'=>$storageGoods->id,'storage_id'=>$storage->id, 'orderId'=>$orderId)) ?>" target="dialog" width="400" height="200" title="修改物品">修改</a>
	            </td>
	        </tr>
	        <?php endforeach; endif; ?>
        </tbody>
    </table>
    <form id="pagerForm" action="<?php echo $this->createUrl(""); ?>" method="post" >
    	<input type="hidden" name="id" value="<?php echo $id;?>" />
    	<input type="hidden" name="pageNum" value="1" />
    	<input type="hidden" name="numPerPage" value="<?php echo $pages->pageSize; ?>" />
	</form>
	<div class="panelBar">
	    <div class="pages">
	        <span>显示</span>
	            <select class="combox" name="numPerPage" change="navTabPageBreak" param="numPerPage" default="<?php echo $pages->pageSize; ?>">
	                <?php foreach ($pageSizes as $pageSize): ?>
	                <option value="<?php echo $pageSize; ?>"><?php echo $pageSize; ?></option>
	                <?php endforeach; ?>
	            </select>
	        <span>共<?php echo $pages->itemCount; ?>条记录</span>
	    </div>
	    <div class="pagination" targetType="navTab"
	        totalCount="<?php echo $pages->itemCount; ?>"
	        numPerPage="<?php echo $pages->pageSize; ?>"
	        pageNumShown="3"
	        currentPage="<?php echo $pages->currentPage + 1; ?>">
	    </div>
	</div>
</div>