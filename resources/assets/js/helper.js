exports.getObjectValue = function(obj, index){
	var value = null;
	try{
		value = index.split('.').reduce((o,i)=>o[i], obj);
	}catch(err){}
	
	return value;
}


exports.getAjaxErrorText = function(jqXHR, exception){
	var msg = '';
	if (jqXHR.status === 0) {
		msg = 'Not connect. Verify Network.';
	} else if (jqXHR.status == 404) {
		msg = 'Requested page not found. [404]';
	} else if (jqXHR.status == 500) {
		msg = 'Internal Server Error [500].';
	} else if (exception === 'parsererror') {
		msg = 'Requested JSON parse failed.';
	} else if (exception === 'timeout') {
		msg = 'Time out error.';
	} else if (exception === 'abort') {
		msg = 'Ajax request aborted.';
	} else {
		msg = 'Uncaught Error.\n' + jqXHR.responseText;
	}

	return msg;
}
