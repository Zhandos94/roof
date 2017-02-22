/**
 * Created by BADI on 28.10.2016.
 */

function _t(message) {

	var result = null;
	if (typeof window[_CURLANG] === 'undefined'
		|| typeof window[_CURLANG][message] === 'undefined'
		|| window[_CURLANG][message] === null) {
		if (typeof window[_CURLANG][message] === 'undefined') {
			writeToFile(message);
		}
		if (_CURLANG === 'ru') {
			result = 'Не задано';
		}
		if (_CURLANG === 'en') {
			result = 'Not set';
		}
		if (_CURLANG === 'kz') {
			result = 'Мәні жоқ';
		}
	} else {
		result = window[_CURLANG][message];
	}
	return result;
}

function writeToFile(message) {
	$.post({
		url: '/site/message-js',
		data: {param_id: 1611, message: message, lang: _CURLANG}
	});
}
