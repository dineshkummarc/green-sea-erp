<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=7" />
        <title><?php echo Yii::app()->name; ?></title>
        <link rel="stylesheet" type="text/css" href="css/style.css"/>
        <script type="text/javascript" src="js/client.js"></script>
        <!--[if IE 6]>
        <script type="text/javascript" src="js/DD_belatedPNG.js"></script>
        <script type="text/javascript">
        DD_belatedPNG.fix('div, a, img');
        $(function () {
            $("a.button").each(function () {
                var $this = $(this);
                $this.width($this.find("span").width() + 30);
            });
            $("#content .content").each(function () {
                var $this = $(this);
                var height = $this.find(".right").height();
                if (height)
                    $this.height(height + $("#footer").height());
                else
                    $this.height($this.height());
            });
        });
        </script>
        <![endif]-->
    </head>

    <body>
        <div class="nav-bg"></div>
        <div id="head">
            <div class="content">
                <div class="logo">
                    <a href="#"><img src="images/logo.png" /></a>
                </div>
                <div class="head_state">中国领先的电子商务视觉服务提供商！</div>
                <div class="shadow-b"></div>
                <div class="shadow-l"></div>
                <div class="shadow-r"></div>
                <div class="shadow-bl"></div>
                <div class="shadow-br"></div>
            </div>
            <div class="nav">
                <div class="left"></div>
                <ul>
                    <li ><a href="javascript: viod(0)"class="active">欢迎访问绿浪视觉客户中心！ ［登录］</a></li>
                    <li class="link">&nbsp;</li>
                </ul>
                <div class="right"></div>
            </div>
        </div>
        <div id="content">
            <div class="content">
                <div class="form">
                    <?php $form=$this->beginWidget('CActiveForm', array('id'=>'login-form','enableClientValidation'=>true,)); ?>

                        <div class="row">
                            <?php echo $form->label($model,'username'); ?>
                            <?php echo $form->textField($model,'username') ?>
                            <?php echo $form->error($model,'username')?>
                        </div>

                        <div class="row">
                            <?php echo $form->label($model,'password'); ?>
                            <?php echo $form->passwordField($model,'password') ?>
                            <?php echo $form->error($model,'password')?>
                        </div>

                        <div class="row_remember">
                            <?php echo $form->checkBox($model,'saveTime'); ?>
                            <?php echo $form->label($model,'saveTime'); ?>
                        </div>

                        <div class="row_submit">
                            <?php echo CHtml::submitButton('登陆'); ?>
                        </div>

                    <?php $this->endWidget(); ?>
                </div>
                <div class="clear"></div>
                <div class="shadow-t"></div>
                <div class="shadow-b"></div>
                <div class="shadow-l"></div>
                <div class="shadow-r"></div>
                <div class="shadow-tl"></div>
                <div class="shadow-tr"></div>
                <div class="shadow-bl"></div>
                <div class="shadow-br"></div>
            </div>
            <div id="footer">
                <p>
                    <span class="power">Power by <a href="http://www.yiiframework.com" target="_blank">Yii Framework</a></span>
                    Copyright &copy; 2011 greensea.com.cn版权所有
                </p>
            </div>
        </div>
    </body>
</html>


