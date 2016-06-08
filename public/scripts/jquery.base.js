
getUrlParam = function (paramName, url) {
    url = url || window.location.href;
    var _urlsearch = url.substring(url.indexOf("?") + 1, url.length) || "";
    if (_urlsearch == "") {
        return "";
    }
    var _paramString = _urlsearch.split("&");
    var _paramObj = {}
    for (i = 0; j = _paramString[i]; i++) {
        _paramObj[j.substring(0, j.indexOf("=")).toLowerCase()] = j.substring(j.indexOf("=") + 1, j.length);
    }
    var _reslut = _paramObj[paramName.toLowerCase()] || "";
    return _reslut;
}

jQuery.fn.LimitWordCount = function (maxcount) {
    var _self = this;
    _self.bind("change", function () {
        if (_self.val().length > maxcount) { _self.val(_self.val().slice(0, maxcount)); }
    });
}


function AutoImg(img, h, w) {
    var image = new Image();
    image.src = img.src;
    var f = h / w;
    var i = image.height / image.width;
    if (f > i) {

        $(img).css({ height: "auto", width: w + "px", visibility: "" });
    } else {
        $(img).css({ height: h + "px", width: "auto", visibility: "" });
    }
}


function DrawImage(ImgD, iwidth, iheight) {
    var image = new Image();
    image.src = ImgD.src;
    if (image.width > 0 && image.height > 0) {
        flag = true;
        if (image.width / image.height >= iwidth / iheight) {
            if (image.width > iwidth) {
                ImgD.width = iwidth;
                ImgD.height = (image.height * iwidth) / image.width;
            } else {
                ImgD.width = image.width;
                ImgD.height = image.height;
            }
        }
        else {
            if (image.height > iheight) {
                ImgD.height = iheight;
                ImgD.width = (image.width * iheight) / image.height;
            } else {
                ImgD.width = image.width;
                ImgD.height = image.height;
            }
        }
    }
}

jQuery.fn.CbVerify = function (contain) {

    $(this).bind("click", function () {
        if (contain.find("input:checkbox.cbselect:checked").size() == 0) {
            return false;
        } else {
            if (confirm("确定继续操作吗？")) {
                return true;
            }
            return false;
        }
    });
}

jQuery.fn.StringFilter = function () {
    var _form = this;
    _form.bind("submit", function () {
        _form.find("input").each(function () {
            var fString = $(this).val();
            fString = fString.replace(/</ig, "＜");
            fString = fString.replace(/>/ig, "＞");
            fString = fString.replace(/\'/ig, "‘");
            fString = fString.replace(/select/ig, "ｓelect");
            fString = fString.replace(/update/ig, "ｕpdate");
            fString = fString.replace(/delete/ig, "ｄelete");
            fString = fString.replace(/insert/ig, "ｉnsert");
            fString = fString.replace(/create/ig, "ｃreate");
            fString = fString.replace(/drop/ig, "ｄrop");
            fString = fString.replace(/and/ig, "ａnd");
            fString = fString.replace(/exec/ig, "ｅxec");
            fString = fString.replace(/execute/ig, "ｅxeｃute");
            fString = fString.replace(/delcare/ig, "ｄeclare");
            fString = fString.replace(/script/ig, "ｓcript");
            $(this).val(fString);
        });
    });
}

var ChangePageSize = function (self, pageindexname, pagesizename, url) {
    pagesizename = pagesizename || "pagesize";
    url = url || window.location.href;
    if (url.indexOf("?") > 0) {
        var _pagesize = getUrlParam(pagesizename, url);
        if (_pagesize != "") {
            var _pageindex = getUrlParam(pageindexname, url);
            window.location.href = url.replace(pageindexname + "=" + _pageindex, pageindexname + "=1").replace(pagesizename + "=" + _pagesize, pagesizename + "=" + $(self).val());
        } else {
            window.location.href = url + "&" + pagesizename + "=" + $(self).val();
        }
    } else {
        window.location.href = url + "?" + pagesizename + "=" + $(self).val();
    }
}


jQuery.fn.CbCheck = function (contain) {
    var _self = this;
    _self.bind("click", function () {
        var ischeck = _self.attr("checked") || false;
        if (ischeck) {
            _self.attr("checked", "checked");
            contain.find("input:checkbox.cbselect").attr("checked", "checked");
        } else {
            _self.removeAttr("checked");
            contain.find("input:checkbox.cbselect").removeAttr("checked");
        }
    });
    contain.find("input:checkbox.cbselect").each(function () {
        $(this).bind("click", function () {
            var ischeck = $(this).attr("checked") || false;

            if (ischeck) {
                $(this).attr("checked", "checked");
                if (contain.find("input:checkbox.cbselect:not(:checked)").size() == 0) {
                    _self.attr("checked", "checked");
                }
            } else {
                $(this).removeAttr("checked");
                if (contain.find("input:checkbox.cbselect:not(:checked)").size() > 0) {
                    _self.removeAttr("checked");
                }
            }
        });
    });
}

jQuery.fn.ToggleSelect = function () {
    var _self = this;
    _self.find("tr").each(function () {
        var _row = $(this);
        _row.find("td").bind("mouseover", function () {
            _row.find("td").addClass("gridrowselect");
        });
        _row.find("td").bind("mouseout", function () {
            _row.find("td").removeClass("gridrowselect");
        });
    });
}