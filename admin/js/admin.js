function buildChangeInput(dom)
{
    var $dom = $(dom);
    var $input = $('<input />');
    $input.attr('name', $dom.attr('name'))
        .attr('id', $dom.attr('id'))
        .css('width', '85%')
        .val($dom.text());
    $dom.after($input);
}

function change(dom, url)
{
    window.url = url;
    $(dom).hide();
    if ($(dom).next().length == 0) buildChangeInput(dom);
    $(dom).next().show().focus().select()
        .unbind('keypress')
        .keypress(function (e) {
            if (e.which == 13) 
                $(this).blur();
            else if (e.which == 27) {
                $(this).unbind('blur');
                $(this).hide();
                $(this).prev().show();
            }
        })
        .blur(function () {
            $(this).unbind('blur');
            changeEvent(this, url);
        });
}

function changeEvent(dom, url)
{
    $dom = $(dom);
    var val = $dom.val()
    var data = new Object();
    data.id = $dom.attr('id');
    eval("data."+$dom.attr('name')+"="+val);
    $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: 'json',
        success: function (response) {
            DWZ.ajaxDone(response);
            $dom.hide();
            $dom.prev().text(val).show();
        },
        error: DWZ.ajaxError
    });
}