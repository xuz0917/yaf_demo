function sendName() {
	var xmlhttp = false;
    //开始初始化XMLHttpRequest对象
    if(window.XMLHttpRequest) {
        //Mozilla 浏览器
        xmlhttp = new XMLHttpRequest();
        if (xmlhttp.overrideMimeType) {//设置MiME类别
            xmlhttp.overrideMimeType('text/xml');
        }
    }
    else if (window.ActiveXObject) {
        // IE浏览器
        try {
            xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {}
        }
    }
   //初始化、指定处理函数、发送请求的函数
	if (!xmlhttp) { // 异常，创建对象实例失败
	   window.alert("不能创建XMLHttpRequest对象实例.");
	   return false;
	}
	var title = window.document.form.name.value;
	if(title.length <= 0){
		alert("公司名称不能为空。");
		window.document.form.name.focus();
		return false;
	}
	var url = "/index.php/Company/compnayajax/name/"+encodeURI(title);
	xmlhttp.open("get",url,false);
	xmlhttp.setRequestHeader("If-Modified-Since","0");//清除缓存
	xmlhttp.send(null);
	if(xmlhttp.readyState == 4) { // 判断对象状态
		if (xmlhttp.status == 200) { // 信息已经成功返回，开始处理信息
			var n=xmlhttp.responseText;
			var str1="";
			
			window.document.getElementById("show").innerHTML=n;
		}else{ //页面不正常
	 		alert("您所请求的页面有异常。");
		}
	}
}

function sendDomain(){
	var xmlhttp = false;
    //开始初始化XMLHttpRequest对象
    if(window.XMLHttpRequest) {
        //Mozilla 浏览器
        xmlhttp = new XMLHttpRequest();
        if (xmlhttp.overrideMimeType) {//设置MiME类别
            xmlhttp.overrideMimeType('text/xml');
        }
    }
    else if (window.ActiveXObject) {
        // IE浏览器
        try {
            xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {}
        }
    }
   //初始化、指定处理函数、发送请求的函数
	if (!xmlhttp) { // 异常，创建对象实例失败
	   window.alert("不能创建XMLHttpRequest对象实例.");
	   return false;
	}
	var ckyj = window.document.form.domain.value+'56cheng.com';
	
	
	
	var url = "/index.php/Company/domainajax/domain/"+ckyj;
	xmlhttp.open("get",url,false);
	xmlhttp.setRequestHeader("If-Modified-Since","0");//清除缓存
	xmlhttp.send(null);
	if(xmlhttp.readyState == 4) { // 判断对象状态
		if (xmlhttp.status == 200) { // 信息已经成功返回，开始处理信息
			var n=xmlhttp.responseText;
			var str1="";
			
			window.document.getElementById("showckyj").innerHTML=n;
		}else{ //页面不正常
	 		alert("您所请求的页面有异常。");
		}
	}	
}
