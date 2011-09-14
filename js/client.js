function init()
{
    $("#submit").click( function () {
        $(this).parents('form').submit();
    });
    
    try
    {
        DD_belatedPNG.fix('div, img, li, .button, .button span, input, .text');
    }
    catch (e)
    {
    }
}

function inputTip(dom)
{
    if (dom)
    {
        $(dom).focus(function () {
            var $this = $(this);
            var tip = $this.attr('tip')
            if (tip == $this.val())
                $this.val("").css('color', '');
        })
        .blur(function () {
            var $this = $(this);
            var tip = $this.attr('tip')
            if ($this.val() == '')
                $this.val(tip).css('color', '#ccc');
        })
        .blur();
    }
    else
    {
        $("input[tip]").focus(function () {
            var $this = $(this);
            var tip = $this.attr('tip')
            if (tip == $this.val())
                $this.val("").css('color', '');
        })
        .blur(function () {
            var $this = $(this);
            var tip = $this.attr('tip')
            if ($this.val() == '')
                $this.val(tip).css('color', '#ccc');
        })
        .blur();
    }
}

function changePageSize(size)
{
    var url = document.URL;
    var regexp = /\&size=\d*/;
    if (regexp.test(url))
    {
        url = url.replace(regexp, "&size="+size);
    }
    else
    {
        url += "&size="+size;
    }
    document.location.href = url;
}