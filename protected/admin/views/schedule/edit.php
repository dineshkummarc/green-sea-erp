<script type="text/javascript">
$(function(){
	$("#diy").change(function () {
		var $this = $(this);
		if ($this.val() == 0)
		{
			var input = $("<input type='text' />")
			.attr("name", "Form[model_name]")
			.attr("class", "required textInput valid")
			.width(100)
			.css("margin-left", 5);
			$this.after(input);
			inputTip(input);
		}
		else if ($this.next().length > 0)
		{
			$this.next().remove();
		}
	});
});
</script>
<div class="pageContent">
    <form action="<?php echo $this->createUrl(''); ?>" class="pageForm required-validate"
        enctype="multipart/form-data" onsubmit="return iframeCallback(this, dialogAjaxDone);" method="post">
        <div class="pageFormContent" layoutH="60">
            <div class="unit">
				<label>订 单 号：</label>
				<select name="Form[order_id]" class="combox required" default="<?php echo !empty($model->order_id) ? $model->order_id : 1; ?>">
                	<?php foreach ($orders as $order):?>
                    <option value="<?php echo $order['id']?>"><?php echo $order['sn']?></option>
                    <?php endforeach;?>
                </select>
			</div>
			<div class="unit">
				<label>拍摄场地：</label>
				<input name="Form[shoot_site]" class="required" type="text" size="30" value="<?php echo !empty($model->shoot_site) ? $model->shoot_site: ''; ?>" alt="请输入拍摄场地"/>
			</div>
			<div class="unit">
                <label>拍摄时间：</label>
                <input type="text" name="Form[shoot_time]" value="<?php echo !empty($model->shoot_time) ? date('Y-m-d H:i', $model->shoot_time) : ''; ?>" class="date required" yearstart="0" yearend="5" format="yyyy-MM-dd HH:mm" /><a class="inputDateButton" href="javascript:;">选择</a>
            </div>
            <div class="unit">
                <label>拍摄类型：</label>
                <select name="Form[shoot_type]" class="combox required" <?php echo !empty($model->shoot_type) ? 'default="'.$model->shoot_type.'"' : ''; ?> >
                	<?php if(!empty($model->order_id)): foreach ($typeList as $list):?>
                    <option value="<?php echo $list['shoot_type']?>"><?php echo ShootType::getShootName($list['shoot_type']);?></option>
                    <?php endforeach; else: foreach (ShootType::getType() as $val):?>
                    <option value="<?php echo $val['id']?>"><?php echo $val['name'];?></option>
                    <?php endforeach; endif;?>
                </select>
            </div>
			<div class="unit">
				<label>摄影师：</label>
				<select name="Form[shoot_id]" class="combox required" <?php echo !empty($model->shoot_id) ? 'default="'.$model->shoot_id.'"' : ''; ?> >
					<option value="0">未选择</option>
                	<?php foreach ($shootList as $list):?>
                    <option value="<?php echo $list['id']?>"><?php echo $list['name']?></option>
                    <?php endforeach;?>
                </select>
			</div>
			<div class="unit">
				<label>造型师：</label>
				<select name="Form[stylist_id]" class="combox" <?php echo !empty($model->stylist_id) ? 'default="'.$model->stylist_id.'"' : ''; ?> >
					<option value="0">未选择</option>
                	<?php foreach ($styleList as $list):?>
                    <option value="<?php echo $list['id']?>"><?php echo $list['name']?></option>
                    <?php endforeach;?>
                </select>
			</div>
			<div class="unit">
				<label>模特：</label>
				<select name="Form[model_id]" class="combox" id="diy" <?php echo !empty($model->model_id) ? 'default="'.$model->model_id.'"' : ''; ?> >
					<option value="0">未选择</option>
                	<?php foreach ($modelList as $list):?>
                    <option value="<?php echo $list['id']?>"><?php echo $list['nick_name']?></option>
                    <?php endforeach;?>
                </select>
			</div>
			<div class="unit">
				<label>描述：</label>
				<textarea name="Form[memo]"  maxlength="250" class="textInput" style="width: 320px; height: 60px;"><?php echo !empty($model->memo) ? $model->memo: ''; ?></textarea>
			</div>
			<input name="Form[id]" type="hidden" value="<?php echo !empty($model->id) ? $model->id : ''; ?>" />
        </div>
        <div class="formBar">
            <ul>
                <li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
                <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
            </ul>
        </div>
    </form>
</div>
