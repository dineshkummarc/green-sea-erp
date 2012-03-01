function orderInit()
{
    $(".change-goods-type").change(function () {
        var $this = $(this);
        var num = $this.attr('num');
        if ($this.val() == 0)
        {
            var input = $("<input type='text' />")
                .attr("name", "Form[1][type_name]")
                .attr("tip", "请填写")
                .addClass("input")
                .width(100)
                .css("margin-top", 5);
            $this.after(input);
            inputTip(input);
        }
        else if ($this.next().length > 0)
        {
            $this.next().remove();
        }
    });
    
    $(".models .model input[type=checkbox]").change(function () {
        var $this = $(this);
        var selectedModels = $(".models .model input:checked");
        var container = $(".models .selected");
        $("span", container).remove();
        if (selectedModels.length == 0)
        {
            container.append("<span>无</span>");
        }
        else
        {
            selectedModels.each(function () {
                var name = $(".models #model-" + this.value + " .name").text();
                container.append("<span>" + name + "</span>");
            });
        }
    });
    inputTip();
}

function validateGoods()
{
	var checked = true;
    var type = $(".change-goods-type");
    if (type.val() == 0)
    {
        var type = type.next();
        if (type.val() == type.attr("tip"))
        {
            alert("请填写自定义的类型");
            type.focus();
            return false;
        }
    }
    
    var numRegexp = /^\d+$/;
    $(".check-count").each(function (k,v) {
    	v = $(v);
    	if (v.val() == '' || !numRegexp.test(v.val()) || v.val() < 1)
        {
            alert("请填写正确的拍摄数量");
            v.select();
            checked = false;
            return false;
        }
    });
    
    return checked;
}

function validateModel()
{
    var selectedCount = $(".models input:checked").length;
    if (selectedCount == 0)
    {
        alert("请选择模特");
        return false;
    }
    return true;
}

function addOrderGoods(table)
{
    var index = table.find('tr').length - 1;
    var tr = $('<tr />');

    // 类别
    var td = $('<td />');
    var select = $('<select id="goods_type"/>');
    select.attr("name", "Form[goods][" + index + "][goods_type_id]");
    for (var i = 0; i < goods_type.length; i++)
    {
        var type = goods_type[i];
        var option = $('<option/>');
        option.val(type.id);
        option.text(type.name);
        select.append(option);
    }
    td.append(select);
    tr.append(td);

    // 季节
    td = $('<td />');
    select = $('<select id="goods_season"/>');
    select.attr("name", "Form[goods][" + index + "][season]");
    option = $('<option/>');
    option.val(0);
    option.text('不限');
    select.append(option);

    option = $('<option/>');
    option.val(1);
    option.text('春秋');
    select.append(option);

    option = $('<option/>');
    option.val(2);
    option.text('夏');
    select.append(option);

    option = $('<option/>');
    option.val(3);
    option.text('冬');
    select.append(option);

    td.append(select);
    tr.append(td);

    // 性别
    td = $('<td />');
    select = $('<select id="goods_sex"/>');
    select.attr("name", "Form[goods][" + index + "][sex]");
    option = $('<option/>');
    option.val(0);
    option.text('不限');
    select.append(option);

    option = $('<option/>');
    option.val(1);
    option.text('男');
    select.append(option);

    option = $('<option/>');
    option.val(2);
    option.text('女');
    select.append(option);

    option = $('<option/>');
    option.val(3);
    option.text('情侣');
    select.append(option);

    td.append(select);
    tr.append(td);

    // 拍摄类型
    td = $('<td />');
    select = $('<select id="shoot_type"/>');
    select.attr("name", "Form[goods][" + index + "][shoot_type_id]");
    for (var i = 0; i < shoot_type.length; i++)
    {
        var type = shoot_type[i];
        option = $('<option/>');
        option.val(type.id);
        option.text(type.name);
        select.append(option);
    }
    td.append(select);
    tr.append(td);

    // 拍摄风格
    td = $('<td />');
    select = $('<select id="goods_style"/>');
    select.attr("name", "Form[goods][" + index + "][style_id]")
        .attr('filter', 'styles')
        .change(function () {
            changeStyle();
        });
    option = $('<option/>');
    option.val(0);
    option.text('不限');
    select.append(option);
    for (var i = 0; i < styles.length; i++)
    {
        var style = styles[i];
        option = $('<option/>');
        option.val(style.id);
        option.text(style.name);
        select.append(option);
    }
    td.append(select);
    tr.append(td);

    // 拍摄数量
    td = $('<td />');
    var input = $('<input id="goods_count" type="text" />');
    input.attr("name", "Form[goods][" + index + "][count]")
        .addClass("input")
        .css('width', 100)
        .keyup(function () {
            goodsCountValidator(this);
        })
        .blur(function () {
            hideError(input);
        });
    td.append(input);
    tr.append(td);

    td = $('<td class="price" />');
    td.text("--");
    tr.append(td);

    td = $('<td />');
    var del = $("<a/>");
    del.attr('href', 'javascript: void(0);');
    del.text('删除');
    del.click(function () {
        delOrderGoods(this);
    });
    td.append(del);
    tr.append(td);

    table.append(tr);
    changeStyle();
}

function delOrderGoods(dom)
{
    $(dom).parent().parent().remove();
    changeStyle();
}

function changeStyle()
{
    var table = $("table#goodsTable");
    var stylesContainer = $("#styles");
    var styles = new Array();
    stylesContainer.data('selected', new Array());
    table.find("select[filter=styles]").each(function () {
        var $this = $(this);
        styles = stylesContainer.data('selected');
        var option = $this.find("option");
        if (styles.length >= option.length - 1)
            return true;
        if ($this.val() == 0)
        {
            styles.length = 0;
            option.each(function () {
                $this= $(this);
                if ($this.val() == 0) return true;
                styles.push({ id: $this.val(), name: $this.text() });
            });
        }
        else
        {
            option = $this.find("option:selected");
            if (styles.length == 0) styles.push({ id: option.val(), name: option.text() });
            for (var i = 0; i < styles.length; i++)
            {
                if (styles[i].id != option.val())
                    styles.push({ id: option.val(), name: option.text() });
            }
        }
        stylesContainer.data('selected', styles);
    });
    $(">span", stylesContainer).remove();
    for (var i = 0; i < styles.length; i++)
    {
        stylesContainer.append("<span>" + styles[i].name + "</span>");
    }
    getModels();
}

function getModels()
{
    var showModels = new Array();
    var styles = $("#styles").data('selected');
    $.each(styles, function (i, style) {
        if (showModels.length == models.length)
            return false;
        $.each(models, function (k, model) {
            if (model.styles)
            {
                $.each(model.styles, function (modelIndex, modelStyle) {
                    if (style.id == modelStyle.id)
                    {
                        showModels.push(model);
                        return false;
                    }
                });
            }
        });
    });
    $("#model-info-container span").text("");
    $("#model-info-container #head_img").html("");
    var container = $(".model-container .models ul");
    container.find("li").remove();
    for (var i = 0; i < showModels.length; i++)
    {
        var model = showModels[i];
        var li = $("<li />");
        var label = $("<label />");
        var checkbox = $("<input type='checkbox' />")
            .attr("name", 'Form[models][]')
            .val(model.id)
            .css('margin-right', 5);
        label.append(checkbox);
        label.append(model.niki_name);
        li.append(label);
        var a = $("<a />")
            .attr("modelId", model.id)
            .attr("href", "javascript: void(0);")
            .text("查看")
            .click(function () {
                var id = $(this).attr("modelId");
                showModel(id);
            });
        li.append(a);
        container.append(li);
    }
}

function showModel(id)
{
    var model = new Object();
    $.each(models, function () {
        if (this.id == id)
        {
            model = this;
            return false;
        }
    });
    var modelInfo = $("#model-info-container");
    $.each(model, function (key, val) {
        if (key == "sign_up")
        {
            $("#sign_up span", modelInfo).text(val?'是':'否');
            return true;
        }
        else if (key == "head_img_thumb")
        {
            $("#head_img", modelInfo).html('<a href="' + model.head_img + '" class="imgWindow" title="点击查看大图"><img src="' + val +'" /></a>');
            $("a.imgWindow", modelInfo).fancybox({autoDimensions: false});
            return true;
        }
        else if (key == "head_img") return true;
        $("#" + key + " span", modelInfo).text(val);
    });
}

function selectModel(id)
{
    alert('selected '+id);
}

function orderValidator()
{
    var result = false;
//    goodsTypeValidator();
    $("#goods_count").each(function () {
        result = goodsCountValidator(this);
    });
    result = modelsValidator();
    return result;
}

function goodsTypeValidator(dom)
{
    if (dom.val() == 0)
    {
        var $this = $(this);
        var input = dom.parent().find("input[type=text]#goods_type_input");
        if (input.length > 0)
        {
            if (input.val() == "")
            {
                showError(input, '请物品类型');
            }
        }
    }
    return true;
}

function goodsCountValidator(dom)
{
    if (!(dom instanceof jQuery))
        dom = $(dom);
    if (!dom.val())
    {
        showError(dom, '请填写拍摄数量');
        return false;
    }
    var regexp = /^\d{1,}$/;
    if (!regexp.test(dom.val()))
    {
        showError(dom, '拍摄数量只能为数字');
        return false;
    }
    else
    {
        hideError(dom);
        return true;
    }
}

function modelsValidator()
{
    if ($("input[name='Form[models][]']:checked").length == 0)
    {
        alert("请选择模特");
        return false;
    }
    else
        return true;
}

function showError(dom, message)
{
    if (!(dom instanceof jQuery))
        dom = $(dom);
    var container = $(".error-container");
    if (container.length == 0)
        container = $("<div />").addClass('error-container');
    $("body").append(container);
    var validataControl = $("#" + dom.attr('id') + "-error", container);
    if (validataControl.length == 0)
    {
        validataController = $("<div />")
            .attr('id', dom.id + "-error")
            .addClass("error")
            .text(message);
        validataController.css('left', dom.offset().left)
            .css('top', dom.offset().top + dom.height() + 7);
        container.append(validataController);
    }
    container.show();
    validataControl.show();
}

function hideError(dom)
{
    if (!(dom instanceof jQuery))
        dom = $(dom);
    var container = $(".error-container");
    $("#" + dom.attr('id') + "-error", container).hide();
    container.hide();
}

function calcError()
{
    var $this = $("#total-price");
    $('.error', $this).text('有数据不明确，请联系客服确定价格');
    $('.price', $this).text('--');
}

function clearCalcError()
{
    var $this = $("#total-price");
    $('.error', $this).text("");
}

$(orderInit);