<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=7" />
    <title><?php echo Yii::app()->name; ?></title>
    <link href="<?php echo Yii::app()->baseUrl; ?>/admin/themes/css/login.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div id="login">
        <div id="login_header"><?php echo Yii::app()->name; ?></div>
        <?php $form = $this->beginWidget('CActiveForm', array('focus'=>array($model,'name'),)); ?>
            <?php echo $form->error($model, 'title'); ?>
            <?php echo $form->error($model, 'name'); ?>
            <?php echo $form->error($model, 'password'); ?>
            <?php echo $form->error($model, 'verifyCode'); ?>
            <div class="form">
                <?php echo $form->label($model,'name'); ?>
                <?php echo $form->textField($model, 'name', array('class'=>'text')); ?>
            </div>
            <div class="form">
                <?php echo $form->label($model,'password'); ?>
                <?php echo $form->passwordField($model, 'password', array('class'=>'text')); ?>
            </div>
            <?php if(extension_loaded('gd')): ?>
            <div class="form">
                <?php echo $form->label($model,'verifyCode'); ?>
                <?php echo $form->textField($model, 'verifyCode', array('class'=>'text', 'style'=>'width: 50px;')); ?>
                <?php $this->widget('CCaptcha', array('buttonLabel'=>'刷新验证码')); ?>
            </div>
            <?php endif; ?>
            <?php echo CHtml::submitButton('登录', array('class' => 'submit')); ?>
        <?php $this->endWidget();?>
    </div>
</body>
</html>
