<div class="pageContent">
	<form action="<?php echo $this->createUrl(''); ?>" class="pageForm required-validate"
        enctype="multipart/form-data" onsubmit="return iframeCallback(this, dialogAjaxDone);" method="post">
		<div class="pageFormContent" layoutH="56">
			<p>
				<label>昵称：</label>
				<span><input type="text" name="Form[nick_name]" value="<?php echo $model->nick_name; ?>" class="required" /></span>
			</p>
			<p>
				<label>头像：</label>
				<?php if (!empty($model->head_img)): ?>
                <span style="line-height: 21px;">
                <a href="<?php echo $model->head_img; ?>" target="_blank">查看</a>
                <a name="head_img" class="reUpload" href="#">重新上传</a>
                </span>
                <?php else: ?>
                <input type="file" name="head_img" />
                <?php endif; ?>
			</p>
			<p>
				<label>图片：</label>
				<?php if (!empty($model->picture)): ?>
                <span style="line-height: 21px;">
                <a href="<?php echo $model->picture; ?>" target="_blank">查看</a>
                <a name="picture" class="reUpload" href="#">重新上传</a>
                </span>
                <?php else: ?>
                <input type="file" name="picture" />
                <?php endif; ?>
			</p>
			<p>
				<label>中文名：</label>
				<span><input type="text" name="Form[china_name]" value="<?php echo  $model->china_name; ?>"  class="required" /></span>
			</p>
			<p>
				<label>英文名：</label>
				<span><input type="text" name="Form[english_name]" value="<?php echo  $model->english_name; ?>"  class="required" /></span>
			</p>
			<p>
				<label>身高：</label>
				<span><input type="text" name="Form[height]" value="<?php echo  $model->height; ?>"  class="required" /></span>
			</p>
			<p>
				<label>体重：</label>
				<span><input type="text" name="Form[weight]" value="<?php echo  $model->weight; ?>"  class="required" /></span>
			</p>
			<p>
				<label>胸围：</label>
				<span><input type="text" name="Form[chest]" value="<?php echo  $model->chest; ?>"  class="required" /></span>
			</p>
			<p>
				<label>腰围：</label>
				<span><input type="text" name="Form[waist]" value="<?php echo  $model->waist; ?>"  class="required" /></span>
			</p>
			<p>
				<label>臀围：</label>
				<span><input type="text" name="Form[hip]" value="<?php echo  $model->hip; ?>"  class="required" /></span>
			</p>
			<p>
				<label>鞋码：</label>
				<span><input type="text" name="Form[shoes]" value="<?php echo  $model->shoes ?>"  class="required" /></span>
			</p>
			<p>
				<label>所在地区：</label>
				<span>
	                <select name="Form[area_id]" class="required" >
	                    <?php foreach ($areaList as $area): ?>
	                    <option value="<?php echo $area->id ?>"><?php echo $area->name; ?></option>
	                    <?php endforeach; ?>
	                </select>
	            </span>
			</p>
			<p>
				<label>签约等级：</label>
				<span><input type="text" name="Form[level]" value="<?php echo  $model->level; ?>"  class="required" /></span>
			</p>
			<input type="hidden" name="Form[id]"  value="<?php echo $model->id; ?>"/>
		</div>
		<div class="formBar">
			<ul>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>
