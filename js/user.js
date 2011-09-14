function userInit()
{
    $("select#sheng").change(function () {
        if (this.value == 0) return false;
        var id = this.value;
        var shi = $("select#shi");
        var qu = $("select#qu");
        shi.empty();
        qu.empty();
        var option = "<option value=\"0\"></option>";
        shi.append(option);
        qu.append(option);
        for (var id in areaShi)
        {
            var area = areaShi[id];
            if (area.parent_id != this.value) continue;
            option = "<option value=\"" +  area.id+ "\">" + area.name + "</option>";
            shi.append(option);
        }
    });
    $("select#shi").change(function () {
        if (this.value == 0) return false;
        var id = this.value;
        var qu = $("select#qu");
        qu.empty();
        var option = "<option value=\"0\"></option>";
        qu.append(option);
        for (var id in areaQu)
        {
            var area = areaQu[id];
            if (area.parent_id != this.value) continue;
            option = $("<option/>")
                .text(area.name)
                .val(area.id);
            qu.append(option);
        }
    });
    $(".receive-list #edit").click(function () {
        var $this = $(this);
        var key = $this.attr('key');
        var data = receiveData[key];
        var defaultValue = $("#default").val();
        $("form input[name='Form[id]']").val(key);
        $("form input[name='Form[receive_name]']").val(data.name);
        // 自动选择地区
        if (!data.area) $("form select#sheng").text("海外");
        else
        {
            var qu = areaQu[data.area];
            var shi = areaShi[qu.parent_id];
            $("form select#sheng").val(shi.parent_id).change();
            $("form select#shi").val(shi.id).change();
            $("form select#qu").val(qu.id);
        }
        $("form textarea[name='Form[street]']").val(data.street);
        $("form input[name='Form[postalcode]']").val(data.postalcode);
        // 自动分割电话号码
        var phoneList = data.phone.split('-');
        $("form input[name='Form[phone-1]']").val(phoneList[0]);
        $("form input[name='Form[phone-2]']").val(phoneList[1]);
        if (phoneList[2])
            $("form input[name='Form[phone-3]']").val(phoneList[2]);
        $("form input[name='Form[mobile_phone]']").val(data.mobile_phone);
        if (defaultValue == key) $("form input[name='Form[default]']").attr("checked", true);
        else $("form input[name='Form[default]']").attr("checked", false);
    });
}

function validateReceive()
{
    var receive_name = $.trim($("input[name='Form[receive_name]']").val());
    if (receive_name == "")
    {
        alert("收货人姓名不能为空");
        return false;
    }
    var area_first = $.trim($("select#sheng").val());
    var area_id = $.trim($("select[name='Form[area_id]']").val());
    if (area_first < 35 && area_id == 0)
    {
        alert("请选择所在地区");
        return false;
    }
    var street = $.trim($("textarea[name='Form[street]']").val());
    if (street == "")
    {
        alert("请填写详细地址");
        return false;
    }
    var postalcode = $.trim($("input[name='Form[postalcode]']").val());
    if (area_first < 35)
    {
        if (postalcode == "")
        {
            alert("邮政编码不能为空");
            return false;
        }
        else if (!/^[0-9]{6}$/.test(postalcode))
        {
            alert("邮政编码格式错误");
            return false;
        }
    }
    var phone1 = $.trim($("input[name='Form[phone-1]']").val());
    var phone2 = $.trim($("input[name='Form[phone-2]']").val());
    var phone3 = $.trim($("input[name='Form[phone-3]']").val());
    var phone = phone1 + "-" + phone2;
    var mobile_phone = $.trim($("input[name='Form[mobile_phone]']").val())
    if (mobile_phone == "" && 
            (phone == "-"))
    {
        alert("请填写电话号码或者手机号码任意一个");
        return false;
    }
    else if (mobile_phone != "" && !/^0{0,1}(13[0-9]|15[0-9])[0-9]{8}$/.test(mobile_phone))
    {
        alert("手机号码格式错误");
        return false;
    }
    else if (phone != "-" && phone1 == "")
    {
        alert("请填写电话区号");
        return false;
    }
    else if(phone != "-" && !/^(\(\d{3,4}\)|\d{3,4}-)?\d{7,8}$/.test(phone))
    {
        alert("电话号码格式不正确");
        return false;
    }
    return true;
}

function validatePassword()
{
    var oldPwd = $.trim($("form input[name='Form[oldpassword]']").val());
    var newPwd = $.trim($("form input[name='Form[newpassword]']").val());
    var rePwd = $.trim($("form input[name='Form[repassword]']").val());

    if (oldPwd == "")
    {
        alert("旧密码不能为空");
        return false;
    }
    
    if (newPwd.length <= 5)
    {
        alert("密码长度不应小于5位");
        return false;
    }
    
    if (newPwd != rePwd)
    {
        alert("两次密码验证不通过");
        return false;
    }
    
    return true;
}

function score_log(select)
{
    var url = document.URL;
    var regexp = /\&time=\d*/;
    if (regexp.test(url))
    {
        url = url.replace(regexp, "&time="+select.value);
    }
    else
    {
        url += "&time="+select.value;
    }
    document.location.href = url;
}

function validateReg()
{
    var name = $.trim($("input[name='Form[name]']").val());
    var mobile_phone = $.trim($("input[name='Form[mobile_phone]']").val());
    var pwd = $.trim($("input[name='Form[password]']").val());
    var repwd = $.trim($("input#repassword").val());
    var email = $.trim($("input[name='Form[email]']").val());
    var area = $("input[name='Form[area_id]']").val();
    var qq = $.trim($("input[name='Form[qq]']").val());
    var page = $.trim($("input[name='Form[page]']").val());
    
    if (name == "")
    {
        alert("客户名不能为空");
        return false;
    }
    
    if (mobile_phone == "")
    {
        alert("手机号码不能为空");
        return false;
    }
    
    var regexp = /^0{0,1}(13[0-9]|15[0-9])[0-9]{8}$/;

    if (!regexp.test(mobile_phone))
    {
        alert("手机号码格式错误");
        return false;
    }
    
    if (pwd == "")
    {
        alert("密码不能为空");
        return false;
    }
    
    if (repwd != pwd)
    {
        alert("2次密码输入不一致");
        return false;
    }
    
    if (qq == "")
    {
        alert("QQ号码不能为空");
        return false;
    }
    
    regexp = /^[1-9]{1}[0-9]{4,8}$/;
    if (!qq.test(regexp))
    {
        alert("QQ号码格式错误");
        return false;
    }
    
    return true;
}