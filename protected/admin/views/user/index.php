<?php $this->widget('widget.Search', array(
    'searchCondition'=>array(
        '�û�����'=>array('type'=>'text', 'name'=>'params[name]', 'defaultValue'=>empty($params['name']) ? '' : $params['name'], 'alt'=>'֧��ģ������'),
        '�ֻ��ţ�'=>array('type'=>'text', 'name'=>'params[phone]', 'defaultValue'=>empty($params['phone']) ? '' : $params['phone']),
        '���䣺'=>array('type'=>'text', 'name'=>'params[mail]', 'defaultValue'=>empty($params['mail']) ? '' : $params['mail'], 'alt'=>'֧��ģ������'),
        ),
	)
); ?>
<div class="pageContent  width="100%"  layoutH="90">
    <div class="panelBar" >
        <ul class="toolBar">
           <li><a class="add" href="<?php echo $this->createUrl("user/edit") ?>" target="dialog" width="350" height="320" mask="true" title="����û�"><span>����û�</span></a></li>
           <li><a class="delete" href="<?php echo $this->createUrl("user/del"); ?>" target="selectedTodo" title="�Ż�ȯҲ��ɾ����ȷ��ɾ��ѡ��������" rel="id[]" ><span>ɾ��ѡ��</span></a></li>
        </ul>
    </div>
    <table class="list" width="100%" >
        <tr>
            <th width="20"><input type="checkbox" class="checkboxCtrl" group="id[]" /></th>
            <th>�û����</th>
            <th>�û���</th>
            <th>������</th>
            <th width="80">QQ��</th>
            <th width="115">�ֻ�����</th>
            <th width="200">�Ա�����ַ</th>
            <th width="65">ʣ�����</th>
            <th>����</th>
        </tr>
        <?php if (!empty($userList)) foreach ($userList as $user): ?>
        <tr>
            <td><input type="checkbox" name="id[]" value="<?php echo $user->id ?>" /></td>
            <td>P<?php echo substr(strval($user->id + 1000),1,3); ?></td>
            <td><?php echo $user->name; ?></td>
            <td><?php echo $user->wangwang; ?></td>
            <td><?php echo $user->qq; ?></td>
            <td><?php echo $user->mobile_phone?></td>
            <td><?php echo $user->page; ?></td>
           <td>
               <span id="<?php echo $user->id ?>" name="score" title="��������޸Ļ�Ա����" url="<?php echo $this->createUrl('user/changeScore'); ?>" class="changeBtn"><?php echo $user->score; ?></span>
            </td>
            <td>
                <a href="<?php echo $this->createUrl('user/edit', array('id'=>$user->id)) ?>" target="dialog" width="350" height="350" title="�޸�">�޸�</a>
                <a href="<?php echo $this->createUrl('user/del', array('id'=>$user->id)); ?>" target="ajaxTodo" title="���Ӧ������Ҳ��ɾ����ȷ��ɾ����">ɾ��</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
    <?php $this->widget('widget.Pager', array(
        'pages'=>$pages,
    )); ?>
