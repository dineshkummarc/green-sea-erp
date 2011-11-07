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

function reUpload(dom, event)
{
    var $dom = $(dom);
    var $file = $("<input />");
    $file.attr('type', 'file')
        .attr('name', $dom.attr('name'));
    var $cancel = $("<a />");
    $cancel.attr('href', '#')
        .text('取消')
        .click(function (event) {
            $this = $(this);
            $this.parent().parent().find(">span:first").show();
            $this.parent().remove();
            event.preventDefault();
        });
    var $container = $("<span />");
    $container.append($file).append($cancel);
    $dom.parent().parent().append($container);
    $dom.parent().hide();
    event.preventDefault();
}

function changeType(value)
{
    var $this = $(this).parent().parent().parent();
    if (value == 0) return true;
    var $container = $this.parent().find('div.container');
    if ($container.length != 0)
        $container.remove();
    $this.after($('<div class="container" />'));
    $container = $this.parent().find('div.container');
    var url = $('label', $this).attr('url');
    $.ajax({
        type: "POST",
        url: url,
        data: {type: value},
        dataType: 'html',
        success: function (response) {
            if (!response)
                alertMsg.error('错误，请联系管理员');
            else
                $container.append(response).initUI();
        }
    });
}

function artExit()
{
	var list = art.dialog.list;
	for (var i in list) {
	    list[i].close();
	};
}
function showMap(dom)
{
    var $dom = $(dom);
    var $map = $dom.attr('map');
    if ($map.length == 0)
    {
    	$map='30.751278, 120.760803';
    }
	mapArt($map)
}
function mapArt($map)
{
	art.dialog({
		padding: 0,
	    fixed: true,
	    lock: true,
	    background: '#fff',
	    title: "请拖动 '气球' 选择坐标点",
	    content: 
    	"<div>"+
    	"<input style='' type='button' value='搜索' onclick='codeAddress()'>"+
    	"<input style='border:0px; width:400px;' id='address' type='textbox' value='"+$("input[name='Form[address]']").val()+"'>"+
    	"<input style='float:right;' type='button' value='确定' onclick='artExit()'>"+
    	"</div>"+
    	"<div id='map_canvas' style='width: 600px; height: 500px;'></div>"
	});
	initialize($map);
}

var geocoder;
var map;
var marker;
function initialize($map) 
{
	var $mapStr=$map.split(',');
	geocoder = new google.maps.Geocoder();
	var myLatlng = new google.maps.LatLng($mapStr['0'], $mapStr['1']);
	var myOptions = {
		zoom: 15,
		center: myLatlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	marker = new google.maps.Marker({
	    position: myLatlng,
	    draggable: true,
	    map: map,
	});
  	document.getElementById("mapAddress").value=$mapStr['0']+','+$mapStr['1'];
    google.maps.event.addListener(marker, "dragend", function(event) {
        var point = event.latLng;
      	document.getElementById("mapAddress").value=point.lat().toString()+','+point.lng().toString();
    });
}
function codeAddress() {
	marker.setMap(null);//隐藏之前叠加层
	var address = document.getElementById("address").value;
    if (geocoder) {
    	geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
              	map.setCenter(results[0].geometry.location);
              	marker = new google.maps.Marker({
            	    draggable: true,
                  	map: map,
                  	position: results[0].geometry.location
              	});
              	var latLng=results[0].geometry.location;
              	document.getElementById("mapAddress").value=latLng.lat().toString()+','+latLng.lng().toString();
                google.maps.event.addListener(marker, "dragend", function(event) {
                    var point = event.latLng;
                  	document.getElementById("mapAddress").value=point.lat().toString()+','+point.lng().toString();
                });
            } else {
              	alert("Geocode was not successful for the following reason: " + status);
            }
      	});
    }
}




function pictureUploadifyComplete(event, queueId, fileObj, response, data)
{
    //response =  DWZ.jsonEval(response);
    alert(respons);
//    if (respons.statusCode == DWZ.statusCode.ok)
//    {
//        alertMsg.success(respons);
//    }
//    else
//    {
//        alertMsg.error(respons);
//    }
    
}