<div class="pageContent">
    <form action="<?php echo $this->createUrl(''); ?>"  class="pageFormrequiredd-validate" onsubmit="return validateCallback(this, dialogAjaxDone);" method="post">
        <div class="pageFormContent" layoutH="60">
            <input type="hidden" name="Form[id]" value="<?php echo $user->id; ?>" />
            <?php if (!empty($user->id)): ?>
            <div class="unit">
                <label>�ͻ����</label>
                <span class="unit">P<?php echo substr(strval($user->id + 1000),1,3); ?></span>
            </div>
            <?php endif; ?>
            <div class="unit">
                <label>�û���</label>
                <span class="unit"><input type="text" name="Form[name]" class="required" value="<?php echo $user->name; ?>" alt="�ͻ�������Ϊ��" /></span>
            </div>
            <div class="unit">
                <?php if (!empty($user->password)): ?>
                <label>��������</label>
                <input id="pwdequal" type="text" name="Form[password]" />
                <?php else: ?>
                <label>����</label>
                <input id="pwdequal" type="text" name="Form[password]" class="required" alt="���벻��Ϊ��" />
                <?php endif; ?>
            </div>
            <div class="unit">
                <label>ȷ������</label>
                <input type="text" id="rePwd" equalto="#rePwd" />
            </div>
            <div class="unit">
                <label>������</label>
                <input type="text" name="Form[wangwang]" value="<?php echo $user->wangwang; ?>" />
            </div>
           <div class="unit">
                <label>QQ��</label>
                <input type="text" name="Form[qq]" value="<?php echo $user->qq; ?>" class="required" alt="QQ���벻��Ϊ��" />
            </div>
            <div class="unit">
                <label>�ֻ�����</label>
                <input type="text" name="Form[mobile_phone]" value="<?php echo $user->mobile_phone; ?>" class="required" alt="�ֻ�����Ϊ��" />
            </div>
            <div class="unit">
                <label>�Ա�����ַ</label>
                <input type="text" name="Form[page]" value="<?php echo $user->page; ?>" />
            </div>
            <div class="unit">
                <label>��Ա����</label>
                <input type="text" name="Form[score]" value="<?php echo $user->score; ?>" class="required number" alt="���ֲ���Ϊ�գ�Ϊ����" />
            </div>
        </div>
        <div class="formBar">
            <ul>
                <li><div class="buttonActive"><div class="buttonContent"><button type="submit">����</button></div></div></li>
                <li><div class="button"><div class="buttonContent"><button type="button" class="close">ȡ��</button></div></div></li>
            </ul>
        </div>
    </form>
</div>
