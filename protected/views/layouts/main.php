<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=7" />
        <link rel="stylesheet" type="text/css" href="css/style.css"/>
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/client.js"></script>
        <!--[if IE 6]>
        <script type="text/javascript" src="js/DD_belatedPNG.js"></script>
        <script type="text/javascript" src="https://getfirebug.com/firebug-lite.js"></script>
        <script type="text/javascript">
        $(function () {
            $("a.button").each(function () {
                var $this = $(this);
                $this.width($this.find("span").width() + 30);
            });
            $("#content .content").each(function () {
                var $this = $(this);
                $this.height($this.find(".right").height() + $("#footer").height());
            });
        });
        </script>
        <![endif]-->
        <script type="text/javascript">$(init);</script>
        <title><?php echo Yii::app()->name; ?></title>
    </head>
    <body>
        <div class="nav-bg"></div>
        <div id="head">
            <div class="content">
                <div class="logo">
                    <a href="#"><img src="images/logo.png" /></a>
                </div>
                <?php $this->widget('widget.UserInfo'); ?>
                <div class="head_state">中国领先的电子商务视觉服务提供商</div>
                <div class="shadow-b"></div>
                <div class="shadow-l"></div>
                <div class="shadow-r"></div>
                <div class="shadow-bl"></div>
                <div class="shadow-br"></div>
            </div>
            <?php $this->widget('widget.NavBar'); ?>
        </div>

        <div id="content">
            <div class="content">
                <div class="left">
                    <?php $this->widget('widget.SideBar'); ?>
                </div>

                <div class="right">
                    <?php $this->widget('widget.SiteMap'); ?>
                    <?php $this->widget("widget.ActionMessage", array('keys'=>array('success-big', 'error-big'))); ?>
                    <div class="clear"></div>
                    <?php echo $content; ?>
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
                    <span class="power">Powered by <a href="http://www.yiiframework.com" target="_blank">Yii Framework</a></span>
                    Copyright &copy; 2011 greensea.com.cn版权所有
                </p>
            </div>
        </div>
    </body>
</html>