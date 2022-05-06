"use strict";

/**
 * When recaptcha is loaded,
 * render recaptcha to all forms with a gdo6-recaptcha element. 
 */
function googleCallbackRecaptcha() {
	let items = document.querySelectorAll('.gdo6-recaptcha2');
	let len = items.length;
	for (let i = 0; i < len; i++)
	{
		let item = items[i];
		let parameters = {
				'theme': 'light', // 'dark'
				'size': 'compact', // 'normal'
				'sitekey': gdo6_recaptcha_key,
				'callback': function(response) {
					$(item).parent().find('input').val(response);
				}
		};
		window.grecaptcha.render(item, parameters);
	}
}
