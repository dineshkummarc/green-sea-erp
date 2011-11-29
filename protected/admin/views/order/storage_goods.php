<div class="pageContent">
    <form action="<?php echo $this->createUrl(''); ?>" class="pageForm required-validate"
        enctype="multipart/form-data" onsubmit="return iframeCallback(this, dialogAjaxDone);" method="post">
        <div class="pageFormContent" layoutH="60">
            <input type="hidden" name="Form[id]" value="<?php echo !empty($storageGoods->id) ? $storageGoods->id : ''; ?>" />
            <input type="hidden" name="Form[storage_id]" value="<?php echo !empty($storage_id) ? $storage_id : ''; ?>" />
            <input type="hidden" name="Form[order_sn]" value="<?php echo !empty($order_sn) ? $order_sn : ''; ?>" />

            <div class="unit">
                <label>物品名称</label>
                <input type="text" name="Form[name]" value="<?php echo !empty($storageGoods->name) ? $storageGoods->name : ''; ?>" class="required" />
            </div>
            <div class="unit">
                <label>拍摄类型</label>
                <select name="Form[shoot_type]" class="combox required" <?php echo !empty($storageGoods->shoot_type) ? 'default="'.$storageGoods->shoot_type.'"' : ''; ?>>
                	<?php foreach ($shootTypes as $type):?>
                    <option value="<?php echo $type['shoot_type']?>"><?php echo ShootType::getShootName($type['shoot_type']);?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <?php if (empty($storageGoods->id)):?>
            <div class="unit">
                <label>物品数量</label>
                <input type="text" name="Form[count]" value="1" class="required" />
            </div>
            <?php endif;?>
        </div>
        <div class="formBar">
            <ul>
                <li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
                <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
            </ul>
        </div>
    </form>
</div>