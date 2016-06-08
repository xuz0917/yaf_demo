/// <reference path="jquery-vsdoc.js" />

var def;

function SetErrorMessate(obj, message, istrue, errboj, isfocus) {
    var txtbox = $(obj);
    if (errboj == def || errboj == "") {
        var err = $('#error_' + txtbox.attr("id"));
    } else {
        var err = $(errboj);
    }
    err.html(message);
    if (istrue) {
        txtbox.removeClass("txtbox-error");
        err.removeClass("error");
        err.addClass("crect");
    } else {
        err.removeClass("crect");
        err.addClass("error");
        txtbox.addClass("txtbox-error");
        if (isfocus == def || isfocus) {
            txtbox.focus();
        }
    }
}

var MaxLength = function (o, Length) {
    $(o).val($(o).val().substring(0, Length));
}

function CheckISFloat(TextName) {
    var tem = $(TextName);
    var strtem = tem.val().replace(/[^0-9,\.,-]/g, '');
    var fh = strtem.lastIndexOf("-");
    if (!(fh == 0 || fh == -1)) {
        strtem = strtem.substring(0, fh);
    }
    var numtem = strtem.indexOf(".", strtem.indexOf(".") + 1);
    numtem = numtem == -1 ? strtem.length : numtem;
    var str = strtem.substring(0, numtem);
    tem.val(str);
}
function CheckISNaN(TextName) {
    $(TextName).val($(TextName).val().replace(/\D/g, ''));
}

function CheckNoChinese(TextName) {
    $(TextName).val($(TextName).val().replace(/[\u4e00-\u9fa5]/g, ""));
}

function isNullText(Text) {
    var str = $(Text).val();
    if (str != def && str.length > 0) {
        return false;
    }
    else {
        return true;
    }
}

function isNotValText(ValE, Val) {
    var str = $(ValE).val();
    if (str != Val) {
        return true;
    } else {
        return false;
    }
}

function isNull(Text, message, err, isfocus) {
    if (isNullText(Text)) {
        if (message == def) message = "该项信息不能为空。";
        SetErrorMessate(Text, message, false, err, isfocus);
        return true;
    }
    else {
        SetErrorMessate(Text, "", true, err, isfocus);
        return false;
    }
}

function isNotVal(Text, Val, message, err, isfocus) {
    if (isNotValText(Text, Val)) {
        SetErrorMessate(Text, "", true, err, isfocus);
        return true;
    }
    else {
        if (message == def) message = "该项信息不能为空。";
        SetErrorMessate(Text, message, false, err, isfocus);
        return false;
    }
}

function isVal(Text, Val, message, err, isfocus) {
    if (!isNotValText(Text, Val)) {
        SetErrorMessate(Text, "", true, err, isfocus);
        return true;
    }
    else {
        if (message == def) message = "该项信息不能为空。";
        SetErrorMessate(Text, message, false, err, isfocus);
        return false;
    }
}

function isEmail(TextEmail, message, err, isfocus) {
    var strEmail = $(TextEmail).val();
    if (strEmail.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1) {

        SetErrorMessate(TextEmail, "", true, err, isfocus);
        return true;
    }
    else {
        if (message == def) message = "您输入的电子邮箱有错误。";
        SetErrorMessate(TextEmail, message, false, err, isfocus);
        return false;
    }
}

function isTel(TextTel, message, err, isfocus) {
    var phone = $(TextTel).val();
    var p1 = /^(([0\+]\d{2,3}-)?(0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/;
    var me = false;
    if (p1.test(phone)) me = true;
    if (!me) {
        if (message == def) message = "您输入的电话号码有错误。区号和电话号码之间请用-分割。";
        SetErrorMessate(TextTel, message, false, err, isfocus);
        return false;
    } else {
        SetErrorMessate(TextTel, "", true, err, isfocus);
        return true;
    }
}

function isMobil(TextMobile, message, err, isfocus) {
    var mobile = $(TextMobile).val();
    var reg0 = /^13\d{5,9}$/;
    var reg1 = /^153\d{4,8}$/;
    var reg2 = /^159\d{4,8}$/;
    var reg3 = /^0\d{10,11}$/;
    var reg4 = /^152\d{4,8}$/;
    var reg5 = /^151\d{4,8}$/;
    var reg6 = /^187\d{4,8}$/;
    var reg7 = /^186\d{4,8}$/;
    var reg8 = /^150\d{4,8}$/;
    var reg9 = /^154\d{4,8}$/;
    var reg10 = /^155\d{4,8}$/;
    var reg11 = /^156\d{4,8}$/;
    var reg12 = /^157\d{4,8}$/;
    var reg13 = /^158\d{4,8}$/;
    var reg14 = /^182\d{4,8}$/;
    var reg15 = /^185\d{4,8}$/;
    var reg16 = /^186\d{4,8}$/;

    var my = false;

    if (reg0.test(mobile)) my = true;
    if (reg1.test(mobile)) my = true;
    if (reg2.test(mobile)) my = true;
    if (reg3.test(mobile)) my = true;
    if (reg4.test(mobile)) my = true;
    if (reg5.test(mobile)) my = true;
    if (reg6.test(mobile)) my = true;
    if (reg7.test(mobile)) my = true;
    if (reg8.test(mobile)) my = true;
    if (reg9.test(mobile)) my = true;
    if (reg10.test(mobile)) my = true;
    if (reg11.test(mobile)) my = true;
    if (reg12.test(mobile)) my = true;
    if (reg13.test(mobile)) my = true;
    if (reg14.test(mobile)) my = true;
    if (reg15.test(mobile)) my = true;
    if (reg16.test(mobile)) my = true;

    if (!my) {
        if (message == def) message = "您输入的手机或小灵通号码有错误。";
        SetErrorMessate(TextMobile, message, false, err, isfocus);
        return false;
    } else {
        SetErrorMessate(TextMobile, "", true, err, isfocus);
        return true;
    }
}

function isCode(TextCode, message, err, isfocus) {

    var powers = new Array("7", "9", "10", "5", "8", "4", "2", "1", "6", "3", "7", "9", "10", "5", "8", "4", "2");
    var parityBit = new Array("1", "0", "X", "9", "8", "7", "6", "5", "4", "3", "2");
    var sex = "male";
    //校验身份证号码的主调用      

    function validId(obj) {
        var _id = obj;
        if (_id == "") return;
        var _valid = false;
        if (_id.length == 15) {
            _valid = validId15(_id);
        } else if (_id.length == 18) {
            _valid = validId18(_id);
        }

        if (!_valid) {
            if (message == def) message = "您输入的身份证号码有错误。";
            SetErrorMessate(TextMobile, message, false, err, isfocus);
            return false;
        } else {
            SetErrorMessate(TextMobile, "", true, err, isfocus);
            return true;
        }


        return _valid;
        //        if (!_valid) {

        //            alert("身份证号码有误,请检查!");
        //            obj.focus();
        //            return false;
        //        }
        //        //设置性别      

        //        var sexSel = document.getElementById("sex");
        //        var options = sexSel.options;
        //        for (var i = 0; i < options.length; i++) {
        //            if (options[i].value == sex) {
        //                options[i].selected = true;
        //                break;
        //            }
        //        }
    }
    //校验18位的身份证号码      

    function validId18(_id) {
        _id = _id + "";
        var _num = _id.substr(0, 17);
        var _parityBit = _id.substr(17);
        var _power = 0;
        for (var i = 0; i < 17; i++) {
            //校验每一位的合法性      

            if (_num.charAt(i) < '0' || _num.charAt(i) > '9') {
                return false;
                break;
            } else {
                //加权      

                _power += parseInt(_num.charAt(i)) * parseInt(powers[i]);
                //设置性别      

                if (i == 16 && parseInt(_num.charAt(i)) % 2 == 0) {
                    sex = "female";
                } else {
                    sex = "male";
                }
            }
        }
        //取模      

        var mod = parseInt(_power) % 11;
        if (parityBit[mod] == _parityBit) {
            return true;
        }
        return false;
    }
    //校验15位的身份证号码      

    function validId15(_id) {
        _id = _id + "";
        for (var i = 0; i < _id.length; i++) {
            //校验每一位的合法性      

            if (_id.charAt(i) < '0' || _id.charAt(i) > '9') {
                return false;
                break;
            }
        }
        var year = _id.substr(6, 2);
        var month = _id.substr(8, 2);
        var day = _id.substr(10, 2);
        var sexBit = _id.substr(14);
        //校验年份位      

        if (year < '01' || year > '90') return false;
        //校验月份      

        if (month < '01' || month > '12') return false;
        //校验日      

        if (day < '01' || day > '31') return false;
        //设置性别      

        if (sexBit % 2 == 0) {
            sex = "female";
        } else {
            sex = "male";
        }
        return true;
    }

    return validId($(TextCode).val());
}