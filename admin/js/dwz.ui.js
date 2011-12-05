function initEnv() {
    $("body").append(DWZ.frag["dwzFrag"]);
    
    if ( $.browser.msie && /6.0/.test(navigator.userAgent) ) {
        try {
            document.execCommand("BackgroundImageCache", false, true);  
        }catch(e){}
    }
	//清理浏览器内存,只对IE起效
	if ($.browser.msie) {
		window.setInterval("CollectGarbage();", 10000);
	}
	
	$(window).resize(function(){
        initLayout();
        $(this).trigger("resizeGrid");
    });
	
	var ajaxbg = $("#background,#progressBar");
	ajaxbg.hide();
	$(document).ajaxStart(function(){
        ajaxbg.show();
    }).ajaxStop(function(){
        ajaxbg.hide();
    });
	
	$("#leftside").jBar({minW:150, maxW:700});
	
	if ($.taskBar) $.taskBar.init();
	navTab.init();
	if ($.fn.switchEnv) $("#switchEnvBox").switchEnv();
	if ($.fn.navMenu) $("#navMenu").navMenu();
	
	setTimeout(function(){
        initLayout();
        initUI();
        
        // navTab styles
        var jTabsPH = $("div.tabsPageHeader");
        jTabsPH.find(".tabsLeft").hoverClass("tabsLeftHover");
        jTabsPH.find(".tabsRight").hoverClass("tabsRightHover");
        jTabsPH.find(".tabsMore").hoverClass("tabsMoreHover");
    
    }, 10);
//	initUI();
//
//	$("#taskbar li").hoverClass("hover");
//	$("#taskbar li.selected").hoverClass("selectedHover");
//	$("#taskbar .close").hoverClass("closeHover");
//	$("#taskbar .restore").hoverClass("restoreHover");
//	$("#taskbar .minimize").hoverClass("minimizeHover");
//	$("#taskbar .taskbarLeft").hoverClass("taskbarLeftHover");
//	$("#taskbar .taskbarRight").hoverClass("taskbarRightHover");
//	
//	// tab styles
//	var jTabsPH = $("div.tabsPageHeader");
//	jTabsPH.find(".tabsLeft").hoverClass("tabsLeftHover");
//	jTabsPH.find(".tabsRight").hoverClass("tabsRightHover");
//	jTabsPH.find(".tabsMore").hoverClass("tabsMoreHover");
//
//
//	var ajaxbg = $("#background,#progressBar");
//	ajaxbg.hide();
//	$(document).ajaxStart(function(){
//		ajaxbg.show();
//	}).ajaxStop(function(){
//		ajaxbg.hide();
//	});

}
function initLayout(){
	var iContentW = $(window).width() - (DWZ.ui.sbar ? $("#sidebar").width() + 10 : 34) - 5;
	var iContentH = $(window).height() - $("#header").height() - 34;

	$("#layout").width($(window).width());
	$("#container").width(iContentW);
	$("#container .tabsPageContent").height(iContentH - 34).find("[layoutH]").layoutH();
	$("#sidebar, #sidebar_s .collapse, #splitBar, #splitBarProxy").height(iContentH - 5);
	$("#taskbar").css({top: iContentH + $("#header").height() + 5, width:$(window).width()});
}

function initUI(_box){
	var $p = $(_box || document);

	//tables
	$("table.table", $p).jTable();
	
	// css tables
	$('table.list', $p).cssTable();

	//auto bind tabs
	$("div.tabs", $p).each(function(){
		var $this = $(this);
		var options = {};
		options.currentIndex = $this.attr("currentIndex") || 0;
		options.eventType = $this.attr("eventType") || "click";
		$this.tabs(options);
	});

	$("ul.tree", $p).jTree();
	$('div.accordion', $p).each(function(){
		var $this = $(this);
		$this.accordion({fillSpace:$this.attr("fillSpace"),alwaysOpen:true,active:0});
	});

	$(":button.checkboxCtrl, :checkbox.checkboxCtrl", $p).checkboxCtrl($p);
	
	if ($.fn.combox) $("select.combox",$p).combox();
    
	if (typeof KindEditor == "undefined") {
        $.getScript("./admin/keditor/kindeditor-min.js", function () {
            $("textarea.editor", $p).each(function(){
                var $this = $(this);
                KindEditor.create('textarea.editor', {
                    cssPath : 'css/global.css',
                    uploadJson : 'admin.php?r=site/editorupload',
                    fileManagerJson : 'admin.php?r=site/editorfilemanager',
                    allowFileManager : true,
                    afterCreate : function() {
                        var self = this;
                        $this.parents("form").bind("beforeSubmit", function () {
                            self.sync();
                            return false;
                        });
                    }
                });
            });
        });
    }
    else {
        $("textarea.editor", $p).each(function(){
            var $this = $(this);
            KindEditor.create('textarea.editor', {
                cssPath : 'css/global.css',
                uploadJson : 'admin.php?r=site/editorupload',
                fileManagerJson : 'admin.php?r=site/editorfilemanager',
                allowFileManager : true,
                afterCreate : function() {
                    var self = this;
                    $this.parents("form").bind("beforeSubmit", function () {
                        self.sync();
                        return false;
                    });
                }
            });
        });
    }
	
	if ($.fn.uploadify) {
		$(":file[uploader]", $p).each(function(){
			var $this = $(this);
			var options = {
				uploader: $this.attr("uploader"),
				script: $this.attr("script"),
				cancelImg: $this.attr("cancelImg"),
				queueID: $this.attr("fileQueue") || "fileQueue",
				fileDesc: $this.attr("fileDesc") || "*.jpg;*.jpeg;*.gif;*.png;*.pdf",
				fileExt : $this.attr("fileExt") || "*.jpg;*.jpeg;*.gif;*.png;*.pdf",
				folder	: $this.attr("folder"),
				auto: true,
				multi: true,
				onError:uploadifyError,
				onComplete: uploadifyComplete,
				onAllComplete: uploadifyAllComplete
			};
			if (!$this.attr("autoUpload")) {
			    options.auto = false;
			    var uploadBtn = $("<a />")
			        .attr("href", "#")
			        .text("上传").click(function () {
			        $this.uploadifyUpload();
			    });
			    uploadBtn.before("\\&nbsp;\\&nbsp;\\&nbsp;\\&nbsp;");
			    $this.after(uploadBtn);
			}
			if ($this.attr("onComplete")) {
				options.onComplete = DWZ.jsonEval($this.attr("onComplete"));
			}
			if ($this.attr("onAllComplete")) {
				options.onAllComplete = DWZ.jsonEval($this.attr("onAllComplete"));
			}
			if ($this.attr("scriptData")) {
				options.scriptData = DWZ.jsonEval($this.attr("scriptData"));
			}
			$this.uploadify(options);
		});
	}
	
	// init styles
	$("input[type=text], input[type=password], textarea", $p).addClass("textInput").focusClass("focus");

	$("input[readonly], textarea[readonly]", $p).addClass("readonly");
	$("input[disabled=true], textarea[disabled=true]", $p).addClass("disabled");

	$("input[type=text]", $p).not("div.tabs input[type=text]", $p).filter("[alt]").inputAlert();

	//Grid ToolBar
	$("div.panelBar li, div.panelBar", $p).hoverClass("hover");

	//Button
	$("div.button", $p).hoverClass("buttonHover");
	$("div.buttonActive", $p).hoverClass("buttonActiveHover");
	
	//tabsPageHeader
	$("div.tabsHeader li, div.tabsPageHeader li, div.accordionHeader, div.accordion", $p).hoverClass("hover");
	
	$("div.panel", $p).jPanel();

	//validate form
	$("form.required-validate", $p).each(function(){
		$(this).validate({
			focusInvalid: false,
			focusCleanup: true,
			errorElement: "span",
			ignore:".ignore",
			invalidHandler: function(form, validator) {
				var errors = validator.numberOfInvalids();
				if (errors) {
					var message = DWZ.msg("validateFormError",[errors]);
					alertMsg.error(message);
				} 
			}
		});
	});

	if ($.fn.datepicker){
		$('input.date', $p).each(function(){
			var $this = $(this);
			var opts = {};
			if ($this.attr("format")) opts.pattern = $this.attr("format");
			if ($this.attr("yearstart")) opts.yearstart = $this.attr("yearstart");
			if ($this.attr("yearend")) opts.yearend = $this.attr("yearend");
			$this.datepicker(opts);
		});
	}

	// navTab
	$("a[target=navTab]", $p).each(function(){
		$(this).click(function(event){
			var $this = $(this);
			var a = $this.attr("a");
			if (a != null) {
				alertMsg.confirm(a, {
					okCall: function(){_doAlert();}
				});
			} else {
				_doAlert();
			}
			return false;
			function _doAlert()
			{
				var title = $this.attr("title") || $this.text();
				var tabid = $this.attr("rel") || "_blank";
				var fresh = eval($this.attr("fresh") || "true");
				var external = eval($this.attr("external") || "false");
				var url = unescape($this.attr("href")).replaceTmById($p);
				DWZ.debug(url);
				if (!url.isFinishedTm()) {
					alertMsg.error($this.attr("warn") || DWZ.msg("alertSelectMsg"));
					return false;
				}
				navTab.openTab(tabid, url,{title:title, fresh:fresh, external:external});
				event.preventDefault();
			}
		});
	});
	
	//dialogs
	$("a[target=dialog]", $p).each(function(){
		$(this).click(function(event){
			var $this = $(this);
			var title = $this.attr("title") || $this.text();
			var rel = $this.attr("rel") || "_blank";
			var options = {};
			var w = $this.attr("width");
			var h = $this.attr("height");
			if (w) options.width = w;
			if (h) options.height = h;
			options.max = eval($this.attr("max") || "false");
			options.mask = eval($this.attr("mask") || "false");
			options.maxable = eval($this.attr("maxable") || "true");
			options.minable = eval($this.attr("minable") || "true");
			options.fresh = eval($this.attr("fresh") || "true");
			options.resizable = eval($this.attr("resizable") || "true");
			options.drawable = eval($this.attr("drawable") || "true");
			options.close = eval($this.attr("close") || "");
			options.param = $this.attr("param") || "";
			
			var url = unescape($this.attr("href")).replaceTmById($p);
			DWZ.debug(url);
			if (!url.isFinishedTm()) {
				alertMsg.error($this.attr("warn") || DWZ.msg("alertSelectMsg"));
				return false;
			}
			$.pdialog.open(url, rel, title, options);
			
			return false;
		});
	});
	$("a[target=ajax]", $p).each(function(){
		$(this).click(function(event){
			var $this = $(this);
			var rel = $this.attr("rel");
			var callback = $this.attr('callback');
			if (rel) $("#"+rel).loadUrl($this.attr("href"), {}, function() {eval(callback)});
			event.preventDefault();
		});
	});
	
	$("div.pagination", $p).each(function(){
		var $this = $(this);
		$this.pagination({
			targetType:$this.attr("targetType"),
			totalCount:$this.attr("totalCount"),
			numPerPage:$this.attr("numPerPage"),
			pageNumShown:$this.attr("pageNumShown"),
			currentPage:$this.attr("currentPage")
		});
	});

	if ($.fn.lookup) $("a[lookupName]", $p).lookup();
	if ($.fn.suggest) $("input[suggestFields]", $p).suggest();
	if ($.fn.itemDetail) $("table[itemDetail]", $p).itemDetail();
	if ($.fn.selectedTodo) $("a[target=selectedTodo]", $p).selectedTodo();
	if ($.fn.pagerForm) $("form[rel=pagerForm]", $p).pagerForm({parentBox:$p});
	if ($.fn.dwzExport) $("a[target=dwzExport]", $p).dwzExport();
	if ($.fn.ajaxTodo) $("a[target=ajaxTodo]", $p).ajaxTodo();
	
	$("span.changeBtn", $p).each(function () {
	    $(this).click(function () {
	        change(this, $(this).attr('url'));
	    });
	});
	
	$("a.reUpload", $p).each(function () {
	    $(this).click(function (event) {
	        reUpload(this, event);
        });
	});
	
	$("a.showmap", $p).each(function () {
	    $(this).click(function (event) {
	        showMap(this);
	    });
	});
}

function closedialog(param) {
	alert(param.msg);
	return true;
}

