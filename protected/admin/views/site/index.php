<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link href="admin/themes/default/style.css" rel="stylesheet" type="text/css" />
	<link href="admin/themes/css/core.css" rel="stylesheet" type="text/css" />
	<link href="admin/keditor/themes/default/default.css" rel="stylesheet" type="text/css" />
	<link href="admin/keditor/plugins/code/prettify.css" rel="stylesheet" type="text/css" />

	<link rel="icon" href=" ../favicon.ico" type="/image/x-icon" />
	<link rel="shortcut icon" href="../favicon.ico" type="/image/x-icon" />
	<link href="admin/themes/css/multiple.css" rel="stylesheet" type="text/css" />
	<!--[if IE]>
	<link href="admin/themes/css/ieHack.css" rel="stylesheet" type="text/css" />
	<![endif]-->
	<script src="admin/js/jquery.js" type="text/javascript"></script>
    <script src="admin/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="admin/js/jquery.validate.js" type="text/javascript"></script>
    <script src="admin/js/jquery.bgiframe.js" type="text/javascript"></script>

    <script src="admin/js/dwz.core.js" type="text/javascript"></script>
    <script src="admin/js/dwz.accordion.js" type="text/javascript"></script>
    <script src="admin/js/dwz.ajax.js" type="text/javascript"></script>
    <script src="admin/js/dwz.alertMsg.js" type="text/javascript"></script>
    <script src="admin/js/dwz.barDrag.js" type="text/javascript"></script>
    <script src="admin/js/dwz.checkbox.js" type="text/javascript"></script>
    <script src="admin/js/dwz.combox.js" type="text/javascript"></script>
    <script src="admin/js/dwz.contextmenu.js" type="text/javascript"></script>
    <script src="admin/js/dwz.cssTable.js" type="text/javascript"></script>
    <script src="admin/js/dwz.database.js" type="text/javascript"></script>
    <script src="admin/js/dwz.datepicker.js" type="text/javascript"></script>
    <script src="admin/js/dwz.dialog.js" type="text/javascript"></script>
    <script src="admin/js/dwz.dialogDrag.js" type="text/javascript"></script>
    <script src="admin/js/dwz.drag.js" type="text/javascript"></script>
    <script src="admin/js/dwz.effects.js" type="text/javascript"></script>
    <script src="admin/js/dwz.history.js" type="text/javascript"></script>
    <script src="admin/js/dwz.navTab.js" type="text/javascript"></script>
    <script src="admin/js/dwz.pagination.js" type="text/javascript"></script>
    <script src="admin/js/dwz.panel.js" type="text/javascript"></script>
    <script src="admin/js/dwz.resize.js" type="text/javascript"></script>
    <script src="admin/js/dwz.stable.js" type="text/javascript"></script>
    <script src="admin/js/dwz.switchEnv.js" type="text/javascript"></script>
    <script src="admin/js/dwz.tab.js" type="text/javascript"></script>
    <script src="admin/js/dwz.taskBar.js" type="text/javascript"></script>
    <script src="admin/js/dwz.tree.js" type="text/javascript"></script>
    <script src="admin/js/dwz.ui.js" type="text/javascript"></script>
    <script src="admin/js/dwz.util.date.js" type="text/javascript"></script>
    <script src="admin/js/dwz.validate.method.js" type="text/javascript"></script>

    <script src="admin/keditor/kindeditor.js" type="text/javascript"></script>
    <script src="admin/keditor/lang/zh_CN.js" type="text/javascript"></script>
	<script src="admin/uploadify/scripts/swfobject.js" type="text/javascript"></script>
	<script src="admin/uploadify/scripts/jquery.uploadify.v2.1.0.js" type="text/javascript"></script>

    <!-- 引用自定义js -->
    <script src="admin/js/admin.js" type="text/javascript"></script>

    <script type="text/javascript">
    $(function(){
    	DWZ.init("admin/dwz.frag.xml", {
    		loginUrl:"<?php echo $this->createUrl('site/login'); ?>",
    		debug:false,	// 调试模式 【true|false】
    		callback:function(){
    			initEnv();
    		}
    	});
    });
    </script>
    <title><?php echo Yii::app()->name?></title>
</head>

<body scroll="no">
	<div id="layout">
		<div id="header">
			<div class="headerNav">
				<ul class="nav">
					<li id="switchEnvBox"><a href="javascript:">您好，（<span><?php echo Yii::app()->user->name; ?></span>） </a>
						<ul>
							<li><a href="<?php echo $this->createUrl('auth/changePwd'); ?>" target="dialog" width="400" mask="true">修改密码</a></li>
						</ul>
					</li>
					<li><a href="#" target="_blank">您最后登录时间：<?php echo date('Y-m-d H:i:s',$admin->login_time);?></a></li>
					<li><a href="<?php echo Yii::app()->baseUrl; ?>" target="_blank">网站首页</a></li>
                    <li><a href="<?php echo Yii::app()->homeUrl; ?>">后台首页</a></li>
					<li><a href="<?php echo $this->createUrl('site/logout'); ?>">退出</a></li>
				</ul>
			</div>
			<!-- navMenu -->
		</div>
		<div id="leftside">
			<div id="sidebar_s">
				<div class="collapse">
					<div class="toggleCollapse"><div></div></div>
				</div>
			</div>
			<div id="sidebar">
				<div class="toggleCollapse"><h2>主菜单</h2><div>收缩</div></div>

				<div class="accordion" fillSpace="sidebar">
					<div class="accordionHeader">
						<h2><span>Folder</span>网站内容管理</h2>
					</div>
					<div class="accordionContent">
						<ul class="tree treeFolder">
							<li><a>订单管理</a>
								<ul>
									<li><a href="<?php echo $this->createUrl('order/index');?>" target="navTab" rel="order-index">订单列表</a></li>
									<li><a href="<?php echo $this->createUrl('order/ordertrack');?>" target="navTab" rel="order-track">订单追踪</a></li>
									<li><a href="<?php echo $this->createUrl('schedule/index');?>" target="navTab" rel="schedule-index">排程列表</a></li>
								</ul>
							</li>
							<li><a>模特管理</a>
								<ul>
									<li><a href="<?php echo $this->createUrl('model/index');?>" target="navTab" rel="model-index">模特列表</a></li>
								</ul>
							</li>
							<li><a>客户管理</a>
								<ul>
									<li><a href="<?php echo $this->createUrl('user/index');?>"   target="navTab" rel="user-index">用户管理</a></li>
								</ul>
							</li>
						</ul>
					</div>
					<div class="accordionHeader">
						<h2><span>Folder</span>系统管理</h2>
					</div>
					<div class="accordionContent">
						<ul class="tree treeFolder">
							<li><a>用户组管理</a>
								<ul>
									<?php if($admin->is_supper == 1): ?>
									<li><a href="<?php echo $this->createUrl('auth/admin'); ?>" target="navTab" rel="list">管理员列表</a></li>
									<li><a href="<?php echo $this->createUrl('auth/role'); ?>" target="navTab" rel="auth-role">部门管理</a></li>
									<?php endif; ?>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div id="container">
			<div id="navTab" class="tabsPage">
				<div class="tabsPageHeader">
					<div class="tabsPageHeaderContent"><!-- 显示左右控制时添加 class="tabsPageHeaderMargin" -->
						<ul class="navTab-tab">
							<li tabid="main" class="main"><a href="javascript:;"><span><span class="home_icon">我的主页</span></span></a></li>
						</ul>
					</div>
					<div class="tabsLeft">left</div><!-- 禁用只需要添加一个样式 class="tabsLeft tabsLeftDisabled" -->
					<div class="tabsRight">right</div><!-- 禁用只需要添加一个样式 class="tabsRight tabsRightDisabled" -->
					<div class="tabsMore">more</div>
				</div>
				<ul class="tabsMoreList">
					<li><a href="javascript:;">我的主页</a></li>
				</ul>
				<div class="navTab-panel tabsPageContent layoutBox">
					<div class="page unitBox">
						<div class="accountInfo">
							<div class="alertInfo">
							</div>
							<div class="right">
							</div>
							<p><span><?php echo isset($admin->Role->name) ? $admin->Role->name : ($admin->is_supper == 1 ? '超级管理员' : ''); ?>：<span style="color:red"><?php echo Yii::app()->user->name; ?></span>,您好！欢迎登录 <?php echo Yii::app()->name; ?></span></p>
							<p>您已经登录 ：<?php echo $admin->login_count; ?> 次,最后一次登录时间 ：<?php echo date('Y-m-d H:i:s',$admin->login_time);?> IP为： <?php echo $admin->last_ip; ?></p>
						</div>
						<div class="pageFormContent" layoutH="80" style="margin-right:230px">

						</div>
						<div style="width:230px;position: absolute;top:60px;right:0" layoutH="80">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="footer">Copyright &copy; 2011 绿浪视觉</div>
</body>
</html>