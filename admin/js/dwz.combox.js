/**
 * @author Roger Wu
 */

(function($){
	var allSelectBox = [];
	$.extend($.fn, {
		comboxSelect: function($box, options){
			var op = $.extend({ selector: ">a" }, options);
			var killAllBox = function(bid){
				$.each(allSelectBox, function(i){
					if (allSelectBox[i] != bid) {
						if (!$("#" + allSelectBox[i])[0]) {
							$("#op" + allSelectBox[i]).remove();
						} else 
							$("#op" + allSelectBox[i]).css({
								height: "",
								width: ""
							}).hide();
					}
				});
			}
			return this.each(function(){
				var box = $(this);
				var selector = $(op.selector, box);

				box.append('<input type="hidden" class="'+selector.attr("class")+'" name="' + selector.attr("name") + '" value="'+selector.attr("value")+'"/>').data("title", selector.text());
				allSelectBox.push(box.attr("id"));
				$(op.selector, box).click(function(){
					var options = $("#op"+box.attr("id"));
					if (options.is(":hidden")) {
						if(options.height() > 300) {
							options.css({height:"300px",overflow:"scroll"});
							options.css("width",options.width() + 20);
						}
						var top = box.offset().top+box[0].offsetHeight-50;
						if(top + options.height() > $(window).height() - 20) {
							top =  $(window).height() - 20 - options.height();
						}
						options.css({top:top,left:box.offset().left}).show();
						killAllBox(box.attr("id"));
						$(document).click(killAllBox);
					} else {
						$(document).unbind("click", killAllBox);
						killAllBox();
					}
					return false;
				});
				$("#op"+box.attr("id")).find(">li").comboxOption(selector, box);
			});
		},
		comboxOption: function(selector, box){
		    selector.text(box.data("title"));
			
			return this.each(function(){
				$(">a", this).click(function(event){
					var $this = $(this);
					var property = $(":hidden", box);
					var change = eval(selector.attr("change"));
					if ($.isFunction(change)) {
						var param = box.attr("param");
						var rel = box.attr("rel");
						var args = (!rel && param)?DWZ.jsonEval("{"+param + ":" + $this.attr("value")+"}"):$this.attr("value");
						var options = change.call(selector[0], args);
						if (options == false)
						{
						    event.preventDefault();
						    return false;
						}
						if(rel) {
							var html = "";
							for (var i = 0; i < options.length; i++) {
								html += "<li><a href=\"#\" value=\"" + options[i][0] + "\">" + options[i][1] + "</a></li>";
							}
							var relObj = $(".combox").find(">div[name='" + rel + "']");
							options = $("#op"+relObj.attr("id")).html(html);
							$(">li", options).comboxOption($(">a", relObj), relObj);
							$(">li>a:first", options).trigger("click");
//								if (relObj.attr("rel")) setTimeout(function(){$(">a:first", relObj).trigger("click")}, 100);
						}
					}
					$this.parent().parent().find(".selected").removeClass("selected");
                    $this.addClass("selected");
                    selector.text($this.text());
					property.attr("value", $this.attr("value"));
					event.preventDefault();
				});
			});
			box.removeData("title");
		},
		combox:function(){
			return this.each(function(){
				var $this = $(this).removeClass("combox");
				var name = $this.attr("name");
				var value= 0;
				if ($this.attr("default"))
				    value = $this.attr("default");
				else if ($("option:selected",$this).length > 0)
				    value = $($("option:selected",$this).get(0)).val();

				var label = "";
				if ($("option[value='" + value + "']",$this).length > 0)
				    label = $("option[value='" + value + "']",$this).text();
				else
				    label = $($("option",$this).get(0)).text();
				var ref = $this.attr("ref");
				var param = $this.attr("param");
				var cid = Math.round(Math.random()*10000000);
				var select = '<div class="combox"><div id="'+ cid +'" class="select"' + (ref?' rel="' + ref + '"' : '') + ' name="' + name + '"' + (param ? ' param="' + param+'"' : '') + '>';
				select += '<a href="javascript:" class="'+$this.attr("class")+'" name="' + name +'" value="' + value + '" change="' + ($this.attr("change")?$this.attr("change"):"")+ '">' + label +'</a></div></div>'
				if ($this.hasClass('required')) select += '<span style="color: red">*</span>';
				var options = '<ul class="comboxop" id="op'+ cid +'">';
				$("option", $this).each(function(){
					var option = $(this);
					options +="<li><a class=\""+ (value==option.val()?"selected":"") +"\" href=\"#\" value=\"" + option.val() + "\">" + option.text() + "</a></li>";
				});
				options +="</ul>";
				$("body").append(options);
				$this.after(select);
				$("div.select", $this.next()).comboxSelect();
				$this.remove();
			});
		}
	});
})(jQuery);
