/**
 * Customizer Live Preview
 * Atualiza o preview em tempo real sem reload da página
 *
 * @package Theme_WordPress
 */

(function($) {
	'use strict';

	wp.customize('theme_primary_color', function(value) {
		value.bind(function(newval) {
			const rgb = hexToRgb(newval);
			document.documentElement.style.setProperty('--theme-primary', newval);
			document.documentElement.style.setProperty('--theme-primary-rgb', `${rgb.r},${rgb.g},${rgb.b}`);
		});
	});

	wp.customize('theme_secondary_color', function(value) {
		value.bind(function(newval) {
			const rgb = hexToRgb(newval);
			document.documentElement.style.setProperty('--theme-secondary', newval);
			document.documentElement.style.setProperty('--theme-secondary-rgb', `${rgb.r},${rgb.g},${rgb.b}`);
		});
	});

	wp.customize('theme_accent_color', function(value) {
		value.bind(function(newval) {
			const rgb = hexToRgb(newval);
			document.documentElement.style.setProperty('--theme-accent', newval);
			document.documentElement.style.setProperty('--theme-accent-rgb', `${rgb.r},${rgb.g},${rgb.b}`);
		});
	});

	wp.customize('theme_header_bg_color', function(value) {
		value.bind(function(newval) {
			document.documentElement.style.setProperty('--theme-header-bg', newval);
		});
	});

	wp.customize('theme_header_text_color', function(value) {
		value.bind(function(newval) {
			document.documentElement.style.setProperty('--theme-header-text', newval);
		});
	});

	wp.customize('theme_footer_bg_color', function(value) {
		value.bind(function(newval) {
			document.documentElement.style.setProperty('--theme-footer-bg', newval);
		});
	});

	wp.customize('theme_footer_text_color', function(value) {
		value.bind(function(newval) {
			document.documentElement.style.setProperty('--theme-footer-text', newval);
		});
	});

	wp.customize('theme_logo_width', function(value) {
		value.bind(function(newval) {
			document.documentElement.style.setProperty('--theme-logo-width', newval + 'px');
		});
	});

	wp.customize('theme_logo_height', function(value) {
		value.bind(function(newval) {
			document.documentElement.style.setProperty('--theme-logo-height', newval + 'px');
		});
	});

	wp.customize('theme_container_layout', function(value) {
		value.bind(function(newval) {
			$('body').toggleClass('boxed-layout', newval === 'boxed');
		});
	});

	wp.customize('theme_container_max_width', function(value) {
		value.bind(function(newval) {
			document.documentElement.style.setProperty('--theme-container-max-width', newval + 'px');
		});
	});

	function hexToRgb(hex) {
		const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
		return result ? {
			r: parseInt(result[1], 16),
			g: parseInt(result[2], 16),
			b: parseInt(result[3], 16)
		} : {r: 0, g: 0, b: 0};
	}

})(jQuery);
