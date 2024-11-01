var surly = (function($) {
	return {
		initialStep: function(step) {

			var current = parseInt(step.substring(0, 1));
			var child = parseInt(step.substring(2, 3));

			var currentStep = current ? $('#initial-steps').children('li').eq(current - 1) : [];
			var childElement = child ? currentStep.find('ul li').eq(child - 1) : [];
			var form = $('#surly-initial-step-' + step);

			if (currentStep.length && form.length) {
				if (childElement.length) {
					childElement.addClass('active').prevAll().removeClass('active').addClass('complete');

					currentStep.addClass('complete actual view');
				} else {
					currentStep.addClass('active');
				}

				currentStep.prevAll().removeClass('active actual view').addClass('complete');

				form.show().siblings().hide();
			}
		}
	};
})(jQuery);

jQuery(document).ready(function($) {

	$('form').each(function() {
		$(this).find('input').keypress(function(e) {
			if(e.which == 10 || e.which == 13) {
				e.preventDefault();

				$(this).closest('form').submit();
			}
		});
	});

	$('#surly-set-up-plugin')
	.add('#surly-set-up-plugin-img').click(function() {
		$.post(ajaxurl, {next_step: '1-0', action: 'surly_skip_step'}).done(function() {
			window.location.reload(true);
		});

		return false;
	});

	$('#surly-initial-step-2-1').submit(function() {
		var form = $(this);

		$.post(ajaxurl, $(form).serialize(), function(response) {
			form.find('.ps-type-in').removeClass('field-error');
			form.find('.surly-field-error').empty();

			if (response.error) {
				form.find('.ps-type-in').addClass('field-error');
				form.find('.surly-field-error[data-field="surly-subdomain"]')
					.html('<div class="ps-title-cell red"><p>' + response.error + '</p></div>');
			} else {
				surly.initialStep('2-2');
			}
		});

		return false;

	}).find('#surly-save-subdomain').click(function() {
		$(this).closest('form').submit();

		return false;
	});

	$('#surly-initial-step-2-2').find('#surly-save-trusted-domains').click(function() {
		$.post(ajaxurl, {next_step: '3-0', action: 'surly_skip_step'}).done(function() {
			surly.initialStep('3-0');
		});

		return false;
	});

	$('#surly-initial-step-3-0').submit(function() {
		var form = $(this);

		$.post(ajaxurl, form.serialize()).done(function() {
			surly.initialStep('4-1');
		});

		return false;

	}).find('#surly-save-trusted-groups').click(function() {
		$(this).closest('form').submit();

		return false;
	});

	$('#surly-initial-step-4-1').submit(function() {
		var form = $(this);

		$.post(ajaxurl, form.closest('form').serialize()).done(function() {
			surly.initialStep('4-2');
		});

		return false;

	}).find('#surly-save-shorten-urls').click(function() {
		$(this).closest('form').submit();

		return false;
	});

	$('#surly-initial-step-4-2').submit(function() {
		var form = $(this);

		$.post(ajaxurl, form.closest('form').serialize()).done(function() {
			window.location.reload(true);
		});

		return false;

	}).find('#surly-save-replace-urls').click(function() {
		$(this).closest('form').submit();

		return false;
	});

	$('#surly-trusted-domains').submit(function() {
		var form = $(this);
		var trustedDomain = $.trim($('#surly-trusted-domain').val());

		if (trustedDomain) {
			$.post(ajaxurl, {surly_trusted_domain: trustedDomain, action: 'surly_save_trusted_domain'}, function(response) {
				form.find('#surly-trusted-domain').removeClass('field-error');
				form.find('.surly-field-error').empty();

				if (response.error) {
					form.find('#surly-trusted-domain').addClass('field-error');
					form.find('.surly-field-error[data-field="surly-trusted-domain"]')
						.html('<div class="ps-title-cell red"><p>' + response.error + '</p></div>');
				} else {
					$('#surly-trusted-domain').val('');

					var rand = Math.round(100000 * Math.random());

					var row = '<li class="inner">'
						+ '<span class="ps-type-check">'
							+ '<input id="surly_trusted_domains-' + rand + '" name="surly_trusted_domains[]" value="' + response.domain + '" type="checkbox"/>'
						+ '</span>'
						+ '<label for="surly_trusted_domains-' + rand + '">' + response.domain + '</label>'
					+ '</li>';

					$('.ps-table-line ul', form).find('li.empty').hide();
					$('.ps-table-line ul', form).append(row).find('#surly_trusted_domains-' + rand).iCheck();

					var length = $('.ps-table-line ul', form).find('li.inner').length;

					$('.ps-table-line ul', form).find('li.first .num-item').text(length == 1 ? '1 item' : (length + ' items'));
				}
			});
		}

		return false;

	}).find('#surly-save-trusted-domain').click(function() {
		$(this).closest('form').submit();

		return false;
	});

	$('#surly-delete-trusted-domains').click(function() {

		var form = $(this).closest('form');
		var trustedDomains = $('.ps-table-line ul', form).find('li.inner input:checked');

		if (trustedDomains.length) {
			$.post(ajaxurl, {surly_trusted_domains: trustedDomains.map(function() { return $(this).val(); }).toArray(), action: 'surly_delete_trusted_domains'}).done(function() {
				form.find('.ps-type-in').removeClass('field-error');
				form.find('.ps-title-cell.red p').empty();

				trustedDomains.closest('li').remove();

				var length = $('.ps-table-line ul', form).find('li.inner').length;

				if (length == 0) {
					$('.ps-table-line ul', form).find('li.empty').show();
				}

				$('.ps-table-line ul', form).find('li.first .num-item').text(length == 1 ? '1 item' : (length + ' items'));
			});
		}

		return false;
	});

	$('.ps-list-in input, .ps-table-line input').iCheck();
	$('.ps-select-in select').chosen({disable_search: true});

	$('#surly_trusted_domains').on('ifChecked', function(event) {
		$(this).closest('form').find('li.inner').iCheck('check');
	}).on('ifUnchecked', function(event) {
		$(this).closest('form').find('li.inner').iCheck('uncheck');
	});

	// replace urls
	var checked = $('#surly_replace_urls_posts:checked')
		.add('#surly_replace_urls_comments:checked')
		.add('#surly_replace_urls_everywhere:checked')
			.length || 0;

	$('#surly_replace_urls_nowhere').on('ifChecked', function(event) {

		$('#surly_replace_urls_posts')
		.add('#surly_replace_urls_comments')
		.add('#surly_replace_urls_everywhere')
			.iCheck('uncheck');
	}).on('ifUnchecked', function(event) {

		if (checked === 0) {
			setTimeout(function() {
				$("#surly_replace_urls_nowhere").iCheck('check');
			}, 1);
		}
	});

	$('#surly_replace_urls_posts')
	.add('#surly_replace_urls_comments').on('ifChecked', function(event) {

		++checked;

		$('#surly_replace_urls_nowhere')
		.add('#surly_replace_urls_everywhere')
			.iCheck('uncheck');
	}).on('ifUnchecked', function(event) {

		if (--checked === 0) {
			$('#surly_replace_urls_nowhere').iCheck('check');
		}
	});

	$('#surly_replace_urls_everywhere').on('ifChecked', function(event) {

		++checked;

		$('#surly_replace_urls_nowhere')
		.add('#surly_replace_urls_posts')
		.add('#surly_replace_urls_comments')
			.iCheck('uncheck');
	}).on('ifUnchecked', function(event) {

		if (--checked === 0) {
			$('#surly_replace_urls_nowhere').iCheck('check');
		}
	});
	// \replace urls

	$('#surly-save-settings-form').submit(function() {
		var form = $(this);

		$.post(ajaxurl, form.serialize(), function(response) {
			form.find('.ps-type-in').removeClass('field-error');
			form.find('.surly-field-error').hide();
			$('#surly-message').hide();

			if (response.error) {
				$('#surly-subdomain').find('.ps-type-in').addClass('field-error');
				$('#surly-subdomain').find('.surly-field-error[data-field="surly-subdomain"]')
					.html('<div class="ps-title-cell red"><p>' + response.error + '</p></div>')
					.show();
			} else {
				$('html, body').animate({scrollTop: 0}, 200).find('#surly-message').show();
			}

			if ($('#surly_replace_urls_nowhere').is(':checked')) {
				$('#surly-replace-urls').find('.ps-list-in').addClass('field-error');

				var error = $('#surly-replace-urls').find('.surly-field-error[data-field="surly-replace-urls"] div p').text();

				$('#surly-replace-urls').find('.surly-field-error[data-field="surly-replace-urls"]')
					.html('<div class="ps-title-cell red"><p>' + error + '</p></div>')
					.show();

			} else {
				$('#surly-replace-urls').find('.ps-list-in').removeClass('field-error');
			}
		});

		return false;
	});

	$('#surly-save-settings').click(function() {
		$('#surly-save-settings-form').submit();

		return false;
	});

	$(window).on('message', function(event) {
		if (event.origin != "https://surdotly.com") {
			return;
		}

		var data = JSON.parse(event.data);
		if (!data.surly_toolbar_settings) {
			return;
		}

		$.post(ajaxurl, {surly_toolbar_settings: data, action: 'surly_save_toolbar_settings'}).done(function() {
			$('#surly-initial-step-1-0').empty();

			$('#surly-initial-step-4-1 .ps-info-text p').text(function() {
				return $(this).text().replace(/\w{2}\d+/, data.id);
			});

			surly.initialStep('2-1');
		});
	});
});