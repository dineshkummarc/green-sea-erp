<div class="pageContent" width="100%" layoutH="27">
    <table class="list" id="list" width="1300px">
    	<thead>
	        <tr>
	            <th width="30"></th>
	            <th colspan="2">客户信息</th>
	            <th rowspan="2">确认<br />拍摄</th>
	            <th colspan="2">入库状态</th>
	            <th>拍摄时间</th>
	            <th>模特</th>
	            <th colspan="2">拍摄状态</th>
	            <th colspan="2">修图状态</th>
	            <th rowspan="2">是否<br />付款</th>
	            <th colspan="2">是否交图</th>
	            <th colspan="3">满意度</th>
	            <th colspan="2">反馈处理</th>
	            <th>出库</th>
	            <th>运单号</th>
	        </tr>
	        <tr>
	            <th width="30"><input type="checkbox" class="checkboxCtrl" group="id[]" /></th>
	            <th>客户编号</th>
	            <th>客户名称</th>
	            <th>时间</th>
	            <th>数量</th>
	            <th></th>
	            <th></th>
	            <th>拍摄中</th>
	            <th>已拍摄</th>
	            <th>修中</th>
	            <th>修完</th>
	            <th>已修图</th>
	            <th>已交图</th>
	            <th>A</th>
	            <th>B</th>
	            <th>C</th>
	            <th>处理</th>
	            <th>完成</th>
	            <th></th>
	            <th></th>
	        </tr>
        </thead>
        <tbody>
	        <?php if (empty($orderTrackList)): ?>
	        <tr>
	            <td colspan="10">无数据</td>
	        </tr>
	        <?php else: foreach ($orderTrackList as $orderTrack):
	        $photographer_id = $orderTrack->photographer_id;
	        $photographer_id_2 = $orderTrack->photographer_id_2;
	        $retouch_id = $orderTrack->retouch_id;
	        $retouch_id_2 = $orderTrack->retouch_id_2;
	        $deliver_id = $orderTrack->deliver_id;

	        $shoot_begin_time = $orderTrack->Order->shoot_begin_time;
	        $shoot_end_time = $orderTrack->Order->shoot_end_time;
	        $retouch_begin_time = $orderTrack->Order->retouch_begin_time;
	        $retouch_end_time = $orderTrack->Order->retouch_end_time;
	        $receive_time = $orderTrack->Order->receive_time;

	        $shoot_begin_time = $orderTrack->Order->shoot_begin_time;
	        $logistics_sn = $orderTrack->Order->logistics_sn
	        ?>
	        <tr>
	            <td><input type="checkbox" name="id[]" value="" /></td>
	            <td>
	            	<?php echo substr(strval($orderTrack->Order->user_id + 1000),1,3)?>
	            	<br /><?php echo "<span style='font-weight:bold'>".Admin::getAdminName($orderTrack->admin_id).'</span>';?>
	            </td><!-- 客户编号 -->
	            <td><?php echo $orderTrack->Order->user_name?></td><!-- 客户名称 -->
	            <td><?php echo $orderTrack->photographer_id !=0 ? "<span style='color:red'>✔</span>" : "";?></td><!-- 确认拍摄 -->
	            <td>
	            	<?php $storage = $orderTrack->getStorage(); echo empty($storage)?'无仓储':date('Y-m-d',$storage['in_time']);?>
	            	<br /><span style='font-weight:bold'><?php echo Admin::getAdminName($storage['admin_id']);?></span>
	            </td><!-- 时间 -->
	            <td>
	            	<?php if (empty($storage)) echo '无仓储'; else{ $count = $orderTrack->getStorageGoodsCount($storage['id']); echo empty($count)?'0':$count;}?>
	            </td><!-- 数量 -->
	            <td>
	            	<?php $schedules = $this->getSchedule($orderTrack->Order->id);
	            		if($schedules === false) echo "";
	            		else{ foreach($schedules as $schedule ){
		            		echo !empty($schedule['0']) ? date("Y-m-d H:i", $schedule['0']) : '';
		            	}
	            	}?>
	            </td><!-- 拍摄时间 -->
	            <td>
	            	<?php $schedules = $this->getSchedule($orderTrack->Order->id);
	            		if($schedules === false) echo "ss";
	            		else{ foreach($schedules as $schedule )
	            		{
		            		if(!empty($schedule['1'])){
		            			$model = $this->getModel($schedule['1']);
		            				echo !empty($model) ? $model : '';
		            		}
	            	}}?>
	            </td><!-- 模特 -->
	            <td>
	            	<?php echo $photographer_id != 0 ? "<span style='color:red'>✔</span>" : "";?>
	            	<br/><?php echo "<span style='font-weight:bold'>".Admin::getAdminName($photographer_id).'</span>';?>
	            	<br/><?php echo $shoot_begin_time == 0 ? '' : date('Y-m-d H:s',$shoot_begin_time)?>
	            </td><!-- 拍摄中 -->
	            <td>
	            	<?php echo $photographer_id_2 != 0 ? "<span style='color:red'>✔</span>" : "";?>
	            	<br /><?php echo "<span style='font-weight:bold'>".Admin::getAdminName($photographer_id_2).'</span>';?>
	            	<br /><?php echo $shoot_end_time == 0 ? '' : date('Y-m-d H:s',$shoot_end_time)?>
	            </td><!-- 已拍摄 -->
	            <td>
	            	<?php echo $retouch_id != 0 ? "<span style='color:red'>✔</span>" : "";?>
	            	<br /><?php echo "<span style='font-weight:bold'>".Admin::getAdminName($retouch_id).'</span>';?>
	            	<br /><?php echo $retouch_begin_time == 0 ? '' : date('Y-m-d H:s',$retouch_begin_time)?>
	            </td><!-- 修中 -->
	            <td>
	            	<?php echo $retouch_id_2 != 0 ? "<span style='color:red'>✔</span>" : "";?>
	            	<br /><?php echo "<span style='font-weight:bold'>".Admin::getAdminName($retouch_id_2).'</span>';?>
	            	<br /><?php echo $retouch_end_time == 0 ? '' : date('Y-m-d H:s',$retouch_end_time)?>
	            </td><!-- 修完 -->
	            <td><?php echo $orderTrack->Order->status >= 2 ? "<span style='color:red'>✔</span>" : "";?></td><!-- 是否付款 -->
	            <td><?php echo $orderTrack->retouch_id_2 != 0 ? "<span style='color:red'>✔</span>" : "";?></td><!-- 已修图 -->
	            <td><?php echo $orderTrack->Order->status >= 9 ? "<span style='color:red'>✔</span>" : "";?></td><!-- 已交图 -->
	            <td></td><!-- A -->
	            <td></td><!-- B -->
	            <td></td><!-- C -->
	            <td></td><!-- 处理 -->
	            <td></td><!-- 完成 -->
	            <td><?php echo $storage['out_time'] > 0 ? "<span style='color:red'>✔</span>" : "";?></td><!-- 出库 -->
	            <td></td><!-- 运单号 -->
	        </tr>
	        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
<?php $this->widget('widget.Pager', array(
    'pages'=>$pages,
)); ?>