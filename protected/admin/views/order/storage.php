<div class="pageContent" width="100%" layoutH="0">
	<?php if (empty($storage)):?>
	<div class="panel" [defH="200"|minH="100"] style="width:200px;">
		<h1>仓储信息</h1>
	    <div>
	    <div class="panelBar">
	        <ul class="toolBar">
	            <li><a class="add" href="<?php echo $this->createUrl("order/StorageGoods",array()) ?>" target="dialog" width="400" height="200" title="创建仓储"><span>创建仓储</span></a></li>
	        </ul>
	    </div>
	    </div>
	</div>
	<?php else:?>
	<div class="panel" [defH="200"|minH="100"] style="width:200px;">
		<h1>仓储信息</h1>
	    <div>
		    <div class="unit">
		    	<label>id：</label>
		    	<label><?php echo $storage->id?></label>
		    </div>
		    <div class="unit">
		    	<label>订单id</label>
		    	<label><?php echo $storage->order_id?></label>
		    </div>
		    <div class="unit">
		    	<label>入库人id</label>
		    	<label><?php echo $storage->admin_id?></label>
		    </div>
		    <div class="unit">
		    	<label>入库时间</label>
		    	<label><?php echo date('Y-m-d H:i:s',$storage->in_time)?></label>
		    </div>
		    <div class="unit">
		    	<label>出库时间</label>
		    	<label><?php echo date('Y-m-d H:i:s',$storage->out_time)?></label>
		    </div>
	    </div>
	</div>
    <div class="panelBar">
        <ul class="toolBar">
            <li><a class="add" href="<?php echo $this->createUrl("order/StorageGoods",array('id'=>'','storage_id'=>$storage->id,'order_sn'=>$storage->Order->sn)) ?>" target="dialog" width="400" height="200" title="添加物品"><span>添加物品</span></a></li>
        </ul>
    </div>
    <table class="list" width="100%">
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
	            <td><?php echo $storageGoods->ShootType->name?></td>
	            <td><?php echo $storageGoods->is_shoot == 0 ? "否" : "是"?></td>
	            <td>
	            	<a href="<?php echo $this->createUrl("order/StorageGoods",array('id'=>$storageGoods->id,'storage_id'=>$storage->id)) ?>" target="dialog" width="400" height="200" title="修改物品">修改</a>
	                &nbsp;
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
	<?php endif;?>
</div>