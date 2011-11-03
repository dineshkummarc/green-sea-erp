/**
 * 多行选择框插件
 * @author 袁晨思
 */
(function ($){

    $.multiselect = function () {
        multiple = $("select[multiple]");
        
        multiple.each(function () {
            // 定义常用变量
            $this = $(this);
            $this.hide();
            selected = new Array();
            unselected = new Array();
            myMultiple = $("<div />");
            leftDiv = $("<div />");
            rightDiv = $("<div />");
            width = $this.attr("width") ? $this.attr("width") : 200;
            height = $this.attr("height") ? $this.attr("height") : 300;
            
            // 获取select相关选项
            selectedOptions= $(">option:selected", this);
            unseselectedOptions = $(">option:not(:selected)", this);
            selectedOptions.each(function () {
                $this = $(this);
                selected.push({value: $this.val(), text: $this.text()});
            });
            unseselectedOptions.each(function () {
                $this = $(this);
                unselected.push({value: $this.val(), text: $this.text()});
            });
            
            // 构建自定义多行下拉框
            leftDiv.css("min-width", width+"px")
                .css("min-height", height+"px")
                .css("_width", width+"px")
                .css("_height", height+"px")
                .addClass("leftDiv");
            rightDiv.css("min-width", width+"px")
                .css("min-height", height+"px")
                .css("_width", width+"px")
                .css("_height", height+"px")
                .addClass("rightDiv");
            
            for (i = 0; i < selected.length; i++)
            {
                div = $("<div />");
                div.text(selected[i].text);
                div.attr('val', selected[i].value);
                
                btn = $("<span />");
                btn.attr('title', '取消授权');
                btn.click($.multiselect.revoke);
                btn.addClass("btn").addClass("del");
                
                div.append(btn);
                leftDiv.append(div);
            }
            
            for (i = 0; i < unselected.length; i++)
            {
                div = $("<div />");
                div.text(unselected[i].text);
                div.attr('val', unselected[i].value);
                
                btn = $("<span />");
                btn.attr('title', '授权');
                btn.click($.multiselect.assign);
                btn.addClass("btn").addClass("add");
                
                div.append(btn);
                rightDiv.append(div);
            }
            
            // 将两个自定义下拉框插入容器
            myMultiple.addClass("multiple");
            // 宽度计算：2多行下拉框宽+内边距+外边距+边框
            myMultiple.css("width", width * 2 + 20 + 10 + 4 + "px").css("min-height", height+"px");
            myMultiple.append(leftDiv).append(rightDiv);
            
            // 将内容添加到同级节点
            $this.parent().parent().append(myMultiple);
        });
        
        return this;
    }
    
    $.multiselect.assign = function () {
        $this = $(this).parent();
        $('span', $this).removeClass("add").addClass("del");
        $this.appendTo($this.parent().prev());
        $(">option[value='"+$this.attr('val')+"']", $this.parent().parent().prev()).attr('selected', "1");
        console.debug($(">option[value='"+$this.attr('val')+"']", $this.parent().parent().prev()).attr('selected'));
        $(this).unbind('click');
        $(this).bind('click', $.multiselect.revoke);
    }
    
    $.multiselect.revoke = function () {
        $this = $(this).parent();
        $('span', $this).removeClass("del").addClass("add");
        $this.appendTo($this.parent().next());
        $(">option[value='"+$this.attr('val')+"']", $this.parent().parent().prev()).attr('selected', "");
        console.debug($(">option[value='"+$this.attr('val')+"']", $this.parent().parent().prev()).attr('selected'));
        $(this).unbind('click');
        $(this).bind('click', $.multiselect.assign);
    }

})(jQuery);
