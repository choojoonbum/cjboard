if (typeof(CAPTCHA_JS) === 'undefined') {

	if (typeof cb_url === 'undefined') {
		alert('올바르지 않은 접근입니다.');
	}

	var CAPTCHA_JS = true;
	var captcha_word = '';

	$(function() {
		$.ajax({
			url : cb_url + '/captcha/get',
			type : 'get',
			dataType : 'json',
			success : function(data) {
				captcha_word= data.word;
			}
		});
		$.validator.addMethod('captchaKey', function(value, element) {
			console.log(captcha_word)
			return this.optional(element) || value.toLowerCase() === captcha_word.toLowerCase();
		});
	});
}
