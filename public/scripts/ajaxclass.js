function Ajax(url,recvT,stringS,resultF) {

	this.url = url;
	this.stringS = stringS;
	this.xmlHttp = this.createXMLHttpRequest();
	if (this.xmlHttp == null) {
		alert("erro");
		return;
	}
	var objxml = this.xmlHttp;
	objxml.onreadystatechange = function (){Ajax.handleStateChange(objxml,recvT,resultF)};

}
Ajax.prototype.createXMLHttpRequest = function() {
	try { return new ActiveXObject("Msxml2.XMLHTTP"); } catch(e) {}
	try { return new ActiveXObject("Microsoft.XMLHTTP"); } catch(e) {}
	try { return new XMLHttpRequest(); } catch(e) {}
	return null;
}
Ajax.prototype.createQueryString = function () {
	var queryString = this.stringS;
	return queryString;
}
Ajax.prototype.get = function () {
	url = this.url;
	var queryString = url + this.createQueryString();
	this.xmlHttp.open("GET",queryString,true);
	this.xmlHttp.send(null);
}
Ajax.prototype.post = function() {
	url = this.url;
	var url = url;
	var queryString = this.createQueryString();
	this.xmlHttp.open("POST",url,true);
	this.xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	this.xmlHttp.send(queryString);
}
Ajax.handleStateChange = function (xmlHttp,recvT,resultF) {
	if (xmlHttp.readyState == 4) {
		if (xmlHttp.status == 200) {
			resultF(recvT?xmlHttp.responseXML:xmlHttp.responseText);
		} else {
			alert("您所请求的页面有异常。");
		}
	}
}