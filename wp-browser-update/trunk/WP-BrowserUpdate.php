<?php
/*
Plugin Name: WP BrowserUpdate
Plugin URI: https://wpbu.steinbrecher.co/
Description: This plugin notifies website visitors to update their outdated browser in a non-intrusive way. Visit <a href="https://browserupdate.org/" title="browserupdate.org" target="_blank" rel="noopener noreferrer">browserupdate.org</a> for more information…
Version: 6.0.0
Author: Marco Steinbrecher
Author URI: https://profiles.wordpress.org/macsteini
Requires at least: 4.6
License: GPLv3 or later
License URI: http://gnu.org/licenses/gpl
*/

if (!defined('ABSPATH')) {
	exit;
}

define('MIN_PHP_VERSION', '7.4');
define('WPBU_BROWSER_UPDATE_SCRIPT_FILE', 'assets/browser-update/update.min.js');
define('WPBU_BROWSER_UPDATE_SHOW_SCRIPT_FILE', 'assets/browser-update/update.show.wpbu.min.js');
define('WPBU_BROWSER_UPDATE_TEST_SCRIPT_FILE', 'assets/browser-update/update.test.wpbu.js');
define('WPBU_BROWSER_UPDATE_STYLE_FILE', 'assets/browser-update/update.show.wpbu.css');
define('WPBU_BROWSER_UPDATE_API_VERSION', 2026.01);
define('WPBU_OPTIONS_NAME', 'wp_browserupdate_options');

if (version_compare(PHP_VERSION, MIN_PHP_VERSION, '<')) {
	add_action('admin_notices', function () {
		echo '<div class="notice notice-error"><p><strong>' . sprintf(esc_html__('Your PHP v%s is outdated: This plugin requires PHP v%s or higher. Please update your PHP version or %s for compatibility with older PHP versions…', 'wp-browser-update'), esc_html(PHP_VERSION), esc_html(MIN_PHP_VERSION), '<a href="https://downloads.wordpress.org/plugin/wp-browser-update.4.8.1.zip" rel="noopener noreferrer">' . esc_html__('download plugin version 4.8.1', 'wp-browser-update') . '</a>') . '</strong></p></div>';
	});
	deactivate_plugins(plugin_basename(__FILE__));
	return;
}

function wpbu_browser_configs() {
	return array(
		'c'       => array(
			'name'     => 'Google Chrome',
			'download' => 'https://chrome.google.com/',
			'url'      => 'https://en.wikipedia.org/wiki/Google_Chrome',
			'xpath'    => "//table[contains(@class,'infobox')]//tr[th//a[text()='Stable release(s)']]/following-sibling::tr[1]//table[contains(@class, 'infobox-subbox')]//tr[th[contains(text(),'Windows')]]/td",
		),
		'f'       => array(
			'name'     => 'Mozilla Firefox',
			'download' => 'https://firefox.com/',
			'url'      => 'https://en.wikipedia.org/wiki/Firefox',
			'xpath'    => "//table[contains(@class,'infobox')]//tr[th//a[text()='Stable release(s)']]/following-sibling::tr[1]//table[contains(@class, 'infobox-subbox')]//tr[th[text()='Standard']]/td",
			'regex'    => '/\d+(\.\d+)+/',
		),
		's'       => array(
			'name'     => 'Apple Safari',
			'download' => 'https://support.apple.com/102665',
			'url'      => 'https://support.apple.com/en-us/100100',
			'xpath'    => "(//a[starts-with(normalize-space(.), 'Safari ')])[1]",
			'regex'    => '/\d+(?:\.\d+)+/',
		),
		'e'       => array(
			'name'     => 'Microsoft Edge',
			'download' => 'https://microsoft.com/edge',
			'url'      => 'https://en.wikipedia.org/wiki/Microsoft_Edge',
			'xpath'    => "//table[contains(@class,'infobox')]//tr[th//a[text()='Stable release(s)']]/following-sibling::tr[1]//table[contains(@class, 'infobox-subbox')]//tr[th[contains(text(),'Windows')]]/td",
		),
		'i'       => array(
			'name'     => 'Microsoft Internet Explorer',
			'download' => 'https://blogs.windows.com/windowsexperience/2022/06/15/internet-explorer-11-has-retired-and-is-officially-out-of-support-what-you-need-to-know/',
		),
		'ios'     => array(
			'name'     => 'iOS Safari',
			'download' => 'https://support.apple.com/ios',
		),
		'samsung' => array(
			'name'     => 'Samsung Internet',
			'download' => 'https://www.samsung.com/apps/samsung-internet/',
		),
		'o'       => array(
			'name'     => 'Opera',
			'download' => 'https://opera.com/',
			'url'      => 'https://en.wikipedia.org/wiki/Opera_(web_browser)',
			'xpath'    => "//table[contains(@class,'infobox')]//tr[th//a[text()='Stable release']]/td",
		),
		'o_a'     => array(
			'name'     => 'Opera for Android',
			'download' => 'https://opera.com/browsers/opera-for-android',
		),
		'e_a'     => array(
			'name'     => 'Microsoft Edge for Android',
			'download' => 'https://microsoft.com/edge',
		),
		'y'       => array(
			'name'     => 'Yandex Browser',
			'download' => 'https://browser.yandex.com/',
		),
		'v'       => array(
			'name'     => 'Vivaldi',
			'download' => 'https://vivaldi.com/',
		),
		'uc'      => array(
			'name'     => 'UC Browser',
			'download' => 'https://www.ucweb.com/',
		),
		'a'       => array(
			'name'     => 'Android Browser',
			'download' => 'https://www.android.com/',
		),
	);
}

function wpbu_browser_keys() {
	return array_keys(wpbu_browser_configs());
}

function wpbu_text_fields() {
	return array('msg', 'insecure', 'msgmore', 'bupdate', 'bignore', 'remind', 'bnever');
}

function wpbu_default_options() {
	$required = array();
	foreach (wpbu_browser_keys() as $key) {
		$required[$key] = '0';
	}

	return array(
		'required'   => $required,
		'behaviour'  => array(
			'reminder'          => '12',
			'reminderClosed'    => '168',
			'test'              => 'false',
			'newwindow'         => 'true',
			'style'             => 'top',
			'shift_page_down'   => 'true',
			'notify_esr'        => 'false',
			'insecure'          => 'true',
			'unsupported'       => 'true',
			'mobile'            => 'true',
			'noclose'           => 'false',
			'nomessage'         => 'false',
			'no_permanent_hide' => 'false',
			'container'         => '',
		),
		'links'      => array(
			'url'                => '',
			'url_permanent_hide' => '',
			'burl'               => '',
		),
		'language'   => array(
			'l' => '',
		),
		'text'       => array(
			'default'        => array_fill_keys(wpbu_text_fields(), ''),
			'overrides_json' => '',
		),
		'callbacks'  => array(
			'onshow'  => '',
			'onclick' => '',
			'onclose' => '',
		),
		'custom_css' => '',
	);
}

function wpbu_legacy_browser_order() {
	return array('e', 'f', 'o', 's', 'c', 'i');
}

function wpbu_legacy_js_keys() {
	return array('reminder', 'test', 'newwindow', 'style', 'insecure', 'unsupported', 'mobile', 'shift_page_down');
}

function wpbu_allowed_remote_hosts() {
	return array(
		'en.wikipedia.org',
		'support.apple.com',
	);
}

function wpbu_unslash_scalar($value, $default = '') {
	if (is_array($value) || is_object($value)) {
		return $default;
	}

	return (string) wp_unslash($value);
}

function wpbu_fetchurl($url) {
	$host = wp_parse_url($url, PHP_URL_HOST);

	if (!is_string($host) || !in_array($host, wpbu_allowed_remote_hosts(), true)) {
		return '';
	}

	$response = wp_safe_remote_get(
		$url,
		array(
			'timeout'     => 10,
			'redirection' => 3,
			'user-agent'  => 'WP BrowserUpdate/' . WPBU_BROWSER_UPDATE_API_VERSION . '; ' . home_url('/'),
		)
	);

	if (is_wp_error($response)) {
		return '';
	}

	$status_code = wp_remote_retrieve_response_code($response);
	if ($status_code < 200 || $status_code >= 300) {
		return '';
	}

	$body = wp_remote_retrieve_body($response);

	return is_string($body) ? $body : '';
}

function wpbu_getversion($url, $xpathQuery, $regex = '/\d+(\.\d+)+/') {
	$html = wpbu_fetchurl($url);
	if (!$html) {
		return '';
	}

	libxml_use_internal_errors(true);
	$dom = new DOMDocument();
	$loaded = $dom->loadHTML($html, LIBXML_NONET);
	libxml_clear_errors();

	if (!$loaded) {
		return '';
	}

	$xpath = new DOMXPath($dom);
	$nodes = $xpath->query($xpathQuery);

	if ($nodes !== false && $nodes->length > 0) {
		$text = $nodes->item(0)->textContent;
		if (preg_match($regex, $text, $match)) {
			return trim($match[0]);
		}
		return 'Version number not found.';
	}

	return 'Stable release not found.';
}

function wpbu_getversion_cached($url, $xpath, $regex = '/\d+(\.\d+)+/', $hours = 6) {
	$hours = (int) apply_filters('wpbu_browser_version_cache_hours', $hours, $url, $xpath, $regex);
	$hours = max(1, min(168, $hours));
	$key = 'wpbu_' . md5($url . $xpath . $regex);
	$version = get_transient($key);

	if ($version !== false) {
		return $version;
	}

	$version = wpbu_getversion($url, $xpath, $regex);
	if (is_string($version) && strlen($version) < 255) {
		set_transient($key, $version, $hours * HOUR_IN_SECONDS);
	}

	return $version;
}

function wpbu_format_required_version_for_buorg($value) {
	$value = trim((string) $value);

	if ($value === '') {
		return 0;
	}

	if (preg_match('/^-?\d+$/', $value)) {
		return (int) $value;
	}

	if (preg_match('/^\d+(?:\.\d+)+$/', $value)) {
		return $value;
	}

	return 0;
}

function wpbu_sanitize_version_setting($value) {
	$value = trim(wpbu_unslash_scalar($value, '0'));
	$value = preg_replace('/(?!^-)[^0-9.]/', '', $value);

	if (!preg_match('/^-?\d+$|^\d+(?:\.\d+)+$/', $value)) {
		return '0';
	}

	return $value;
}

function wpbu_sanitize_bool_string($value, $default = 'false') {
	$value = sanitize_text_field(wpbu_unslash_scalar($value, $default));

	return in_array($value, array('true', 'false'), true) ? $value : $default;
}

function wpbu_sanitize_style_setting($value) {
	$value = sanitize_text_field(wpbu_unslash_scalar($value, 'top'));

	return in_array($value, array('top', 'bottom', 'corner'), true) ? $value : 'top';
}

function wpbu_sanitize_number_string($value, $default, $min, $max) {
	$value = (int) wpbu_unslash_scalar($value, $default);

	return (string) max($min, min($max, $value));
}

function wpbu_sanitize_language_setting($value) {
	$value = strtolower(sanitize_text_field(wpbu_unslash_scalar($value)));

	return preg_match('/^[a-z]{2}(?:-[a-z0-9]{2,3})?$/', $value) ? $value : '';
}

function wpbu_sanitize_url_setting($value) {
	$value = trim(wpbu_unslash_scalar($value));

	return $value === '' ? '' : esc_url_raw($value);
}

function wpbu_sanitize_callback_name($value) {
	$value = trim(wpbu_unslash_scalar($value));

	return preg_match('/^(?:window\.)?[A-Za-z_$][A-Za-z0-9_$]*(?:\.[A-Za-z_$][A-Za-z0-9_$]*)*$/', $value) ? $value : '';
}

function wpbu_sanitize_container_selector($value) {
	$value = trim(sanitize_text_field(wpbu_unslash_scalar($value)));

	return strlen($value) <= 255 ? $value : '';
}

function wpbu_sanitize_custom_css($css) {
	$css = sanitize_textarea_field(wpbu_unslash_scalar($css));

	return trim(wp_strip_all_tags($css));
}

function wpbu_sanitize_text_value($value) {
	if (is_array($value)) {
		$output = array();
		foreach (wpbu_text_fields() as $field) {
			$output[$field] = sanitize_textarea_field(wpbu_unslash_scalar($value[$field] ?? ''));
		}
		return $output;
	}

	return sanitize_textarea_field(wpbu_unslash_scalar($value));
}

function wpbu_sanitize_text_overrides_json($value) {
	$value = trim(wpbu_unslash_scalar($value));
	if ($value === '') {
		return '';
	}

	$decoded = json_decode($value, true);
	if (!is_array($decoded)) {
		return '';
	}

	$allowed = '/^text(?:_(?:in_)?[a-z]{2}(?:-[a-z0-9]{2,3})?|_for_[a-z0-9_]+(?:_in_[a-z]{2}(?:-[a-z0-9]{2,3})?)?)$/';
	$output = array();

	foreach ($decoded as $key => $text) {
		if (!is_string($key) || !preg_match($allowed, $key)) {
			continue;
		}

		$output[$key] = wpbu_sanitize_text_value($text);
	}

	return empty($output) ? '' : wp_json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}

function wpbu_migrate_legacy_options() {
	$options = wpbu_default_options();

	$legacy_browsers = explode(' ', get_option('wp_browserupdate_browsers', ''));
	$legacy_browsers = array_pad($legacy_browsers, count(wpbu_legacy_browser_order()), '0');
	foreach (wpbu_legacy_browser_order() as $index => $key) {
		$options['required'][$key] = wpbu_sanitize_version_setting($legacy_browsers[$index] ?? '0');
	}

	$legacy_js = explode(' ', get_option('wp_browserupdate_js', ''));
	$legacy_js = array_pad($legacy_js, count(wpbu_legacy_js_keys()), null);
	foreach (wpbu_legacy_js_keys() as $index => $key) {
		if ($legacy_js[$index] !== null && $legacy_js[$index] !== '') {
			$options['behaviour'][$key] = $legacy_js[$index];
		}
	}

	$options['custom_css'] = get_option('wp_browserupdate_css_buorg', '');

	return wpbu_sanitize_options($options);
}

function wpbu_sanitize_options($value) {
	$defaults = wpbu_default_options();
	$value = is_array($value) ? $value : array();
	$output = $defaults;

	foreach (wpbu_browser_keys() as $key) {
		$output['required'][$key] = wpbu_sanitize_version_setting($value['required'][$key] ?? $defaults['required'][$key]);
	}

	$output['behaviour']['reminder'] = wpbu_sanitize_number_string($value['behaviour']['reminder'] ?? $defaults['behaviour']['reminder'], $defaults['behaviour']['reminder'], 0, 9999);
	$output['behaviour']['reminderClosed'] = wpbu_sanitize_number_string($value['behaviour']['reminderClosed'] ?? $defaults['behaviour']['reminderClosed'], $defaults['behaviour']['reminderClosed'], 0, 9999);
	$output['behaviour']['style'] = wpbu_sanitize_style_setting($value['behaviour']['style'] ?? $defaults['behaviour']['style']);

	foreach (array('test', 'newwindow', 'shift_page_down', 'notify_esr', 'insecure', 'unsupported', 'mobile', 'noclose', 'nomessage', 'no_permanent_hide') as $key) {
		$output['behaviour'][$key] = wpbu_sanitize_bool_string($value['behaviour'][$key] ?? $defaults['behaviour'][$key], $defaults['behaviour'][$key]);
	}
	$output['behaviour']['container'] = wpbu_sanitize_container_selector($value['behaviour']['container'] ?? '');

	foreach (array('url', 'url_permanent_hide', 'burl') as $key) {
		$output['links'][$key] = wpbu_sanitize_url_setting($value['links'][$key] ?? '');
	}

	$output['language']['l'] = wpbu_sanitize_language_setting($value['language']['l'] ?? '');
	$output['text']['default'] = wpbu_sanitize_text_value($value['text']['default'] ?? array());
	$output['text']['overrides_json'] = wpbu_sanitize_text_overrides_json($value['text']['overrides_json'] ?? '');

	foreach (array('onshow', 'onclick', 'onclose') as $key) {
		$output['callbacks'][$key] = wpbu_sanitize_callback_name($value['callbacks'][$key] ?? '');
	}

	$output['custom_css'] = wpbu_sanitize_custom_css($value['custom_css'] ?? '');

	return $output;
}

function wpbu_get_options() {
	$options = get_option(WPBU_OPTIONS_NAME, null);

	if (!is_array($options)) {
		$options = wpbu_migrate_legacy_options();
		update_option(WPBU_OPTIONS_NAME, $options);
	}

	return wpbu_sanitize_options($options);
}

function wpbu_build_text_config($text_options) {
	$config = array();
	$default = array();

	foreach (wpbu_text_fields() as $field) {
		if (!empty($text_options['default'][$field])) {
			$default[$field] = $text_options['default'][$field];
		}
	}

	if (!empty($default)) {
		$config['text'] = $default;
	}

	if (!empty($text_options['overrides_json'])) {
		$decoded = json_decode($text_options['overrides_json'], true);
		if (is_array($decoded)) {
			foreach ($decoded as $key => $text) {
				$config[$key] = $text;
			}
		}
	}

	return $config;
}

function wpbu_get_buoop_config() {
	$options = wpbu_get_options();
	$config = array(
		'required'        => array(),
		'reminder'        => (int) $options['behaviour']['reminder'],
		'reminderClosed'  => (int) $options['behaviour']['reminderClosed'],
		'test'            => $options['behaviour']['test'] === 'true',
		'newwindow'       => $options['behaviour']['newwindow'] === 'true',
		'style'           => $options['behaviour']['style'],
		'shift_page_down' => $options['behaviour']['shift_page_down'] === 'true',
		'notify_esr'      => $options['behaviour']['notify_esr'] === 'true',
		'insecure'        => $options['behaviour']['insecure'] === 'true',
		'unsupported'     => $options['behaviour']['unsupported'] === 'true',
		'mobile'          => $options['behaviour']['mobile'] === 'true',
		'noclose'         => $options['behaviour']['noclose'] === 'true',
		'nomessage'       => $options['behaviour']['nomessage'] === 'true',
		'no_permanent_hide' => $options['behaviour']['no_permanent_hide'] === 'true',
		'jsshowurl'       => plugins_url(WPBU_BROWSER_UPDATE_SHOW_SCRIPT_FILE, __FILE__),
		'domain'          => untrailingslashit(dirname(plugins_url(WPBU_BROWSER_UPDATE_TEST_SCRIPT_FILE, __FILE__))),
		'api'             => WPBU_BROWSER_UPDATE_API_VERSION,
	);

	foreach (wpbu_browser_keys() as $key) {
		$config['required'][$key] = wpbu_format_required_version_for_buorg($options['required'][$key] ?? '0');
	}

	foreach (array('url', 'url_permanent_hide', 'burl') as $key) {
		if (!empty($options['links'][$key])) {
			$config[$key] = $options['links'][$key];
		}
	}

	if (!empty($options['language']['l'])) {
		$config['l'] = $options['language']['l'];
	}

	if (!empty($options['behaviour']['container'])) {
		$config['_wpbu_container'] = $options['behaviour']['container'];
	}

	$config = array_merge($config, wpbu_build_text_config($options['text']));

	$callbacks = array_filter($options['callbacks']);
	if (!empty($callbacks)) {
		$config['_wpbu_callbacks'] = $callbacks;
	}

	return $config;
}

function wpbu_enqueue_browserupdate() {
	wp_enqueue_style('wp-browser-update-browserupdate', plugins_url(WPBU_BROWSER_UPDATE_STYLE_FILE, __FILE__), array(), WPBU_BROWSER_UPDATE_API_VERSION);
	if (wpbu_get_custom_css() !== '') {
		wp_enqueue_style('wp-browser-update-custom', add_query_arg('action', 'wpbu_custom_css', admin_url('admin-ajax.php')), array('wp-browser-update-browserupdate'), WPBU_BROWSER_UPDATE_API_VERSION);
	}
	wp_enqueue_script('wp-browser-update-config', add_query_arg('action', 'wpbu_config_js', admin_url('admin-ajax.php')), array(), WPBU_BROWSER_UPDATE_API_VERSION, true);
	wp_enqueue_script('wp-browser-update-browserupdate', plugins_url(WPBU_BROWSER_UPDATE_SCRIPT_FILE, __FILE__), array('wp-browser-update-config'), WPBU_BROWSER_UPDATE_API_VERSION, true);
}

function wpbu_render_config_js() {
	$config = wpbu_get_buoop_config();
	$callbacks = isset($config['_wpbu_callbacks']) ? $config['_wpbu_callbacks'] : array();
	$container = isset($config['_wpbu_container']) ? $config['_wpbu_container'] : '';
	unset($config['_wpbu_callbacks']);
	unset($config['_wpbu_container']);

	header('Content-Type: application/javascript; charset=' . get_option('blog_charset'));
	echo 'window.$buoop = ' . wp_json_encode($config, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ';';

	if ($container !== '') {
		echo "\n(function(config, selector) {";
		echo 'try{var el=document.querySelector(selector);if(el){config.container=el;}}catch(e){}';
		echo '})(window.$buoop, ' . wp_json_encode($container, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ');';
	}

	if (!empty($callbacks)) {
		echo "\n(function(config, callbacks) {";
		echo 'function resolve(name){var parts=name.replace(/^window\./,"").split("."), scope=window; for(var i=0;i<parts.length;i++){scope=scope&&scope[parts[i]];} return typeof scope==="function"?scope:null;}';
		echo 'Object.keys(callbacks).forEach(function(key){var fn=resolve(callbacks[key]); if(fn){config[key]=function(infos){return fn(infos);};}});';
		echo '})(window.$buoop, ' . wp_json_encode($callbacks, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ');';
	}

	exit;
}

function wpbu_get_custom_css() {
	$options = wpbu_get_options();

	return $options['custom_css'];
}

function wpbu_render_custom_css() {
	header('Content-Type: text/css; charset=' . get_option('blog_charset'));
	echo wpbu_get_custom_css();
	exit;
}

function wpbu_register_settings() {
	register_setting('wp_browserupdate', WPBU_OPTIONS_NAME, 'wpbu_sanitize_options');

	add_settings_section('wpbu_browser_versions', __('Outdated Browser Versions', 'wp-browser-update'), 'wpbu_render_browser_versions_section', 'wp-browserupdate');
	add_settings_field('wpbu_required_versions', __('Required versions', 'wp-browser-update'), 'wpbu_render_browser_versions_field', 'wp-browserupdate', 'wpbu_browser_versions');

	add_settings_section('wpbu_behaviour', __('Notification Behaviour', 'wp-browser-update'), 'wpbu_render_behaviour_section', 'wp-browserupdate');
	add_settings_field('wpbu_behaviour_fields', __('Behaviour options', 'wp-browser-update'), 'wpbu_render_behaviour_fields', 'wp-browserupdate', 'wpbu_behaviour');

	add_settings_section('wpbu_links', __('Links', 'wp-browser-update'), 'wpbu_render_links_section', 'wp-browserupdate');
	add_settings_field('wpbu_link_fields', __('Link options', 'wp-browser-update'), 'wpbu_render_link_fields', 'wp-browserupdate', 'wpbu_links');

	add_settings_section('wpbu_language_text', __('Language and Text', 'wp-browser-update'), 'wpbu_render_language_text_section', 'wp-browserupdate');
	add_settings_field('wpbu_language_text_fields', __('Language and message text', 'wp-browser-update'), 'wpbu_render_language_text_fields', 'wp-browserupdate', 'wpbu_language_text');

	add_settings_section('wpbu_callbacks', __('Callbacks', 'wp-browser-update'), 'wpbu_render_callbacks_section', 'wp-browserupdate');
	add_settings_field('wpbu_callback_fields', __('Callback function names', 'wp-browser-update'), 'wpbu_render_callback_fields', 'wp-browserupdate', 'wpbu_callbacks');

	add_settings_section('wpbu_custom_css', __('Custom CSS', 'wp-browser-update'), 'wpbu_render_custom_css_section', 'wp-browserupdate');
	add_settings_field('wpbu_custom_css_field', __('Custom CSS', 'wp-browser-update'), 'wpbu_render_custom_css_field', 'wp-browserupdate', 'wpbu_custom_css');

	add_settings_section('wpbu_runtime', __('Runtime', 'wp-browser-update'), 'wpbu_render_runtime_section', 'wp-browserupdate');
	add_settings_field('wpbu_runtime_fields', __('Locked local runtime values', 'wp-browser-update'), 'wpbu_render_runtime_fields', 'wp-browserupdate', 'wpbu_runtime');
}

function wpbu_admin_options() {
	static $options = null;
	if ($options === null) {
		$options = wpbu_get_options();
	}

	return $options;
}

function wpbu_render_browser_versions_section() {
	echo '<p>' . esc_html__('Configure the required version for every browser key supported by the bundled runtime.', 'wp-browser-update') . '</p>';
	echo '<p>' . esc_html__('Use 0 for default detection, a major version such as 137, an exact dotted version such as 137.0.3912.63, or a negative whole number such as -2 for latest minus 2 major versions.', 'wp-browser-update') . '</p>';
}

function wpbu_render_browser_versions_field() {
	$options = wpbu_admin_options();
	$configs = wpbu_browser_configs();
	echo '<table class="widefat striped"><tbody>';
	foreach ($configs as $key => $browser) {
		$field_id = 'wpbu_required_' . $key;
		echo '<tr><th scope="row"><label for="' . esc_attr($field_id) . '">' . esc_html($browser['name']) . ' <code>' . esc_html($key) . '</code></label></th><td>';
		echo '<input type="text" class="regular-text code" pattern="(?:-?[0-9]+|[0-9]+(?:\.[0-9]+)+)" name="' . esc_attr(WPBU_OPTIONS_NAME) . '[required][' . esc_attr($key) . ']" id="' . esc_attr($field_id) . '" value="' . esc_attr($options['required'][$key]) . '" />';
		echo ' <a href="' . esc_url($browser['download']) . '" target="_blank" rel="noopener noreferrer">' . esc_html__('Browser download page', 'wp-browser-update') . '</a>';
		echo '</td></tr>';
	}
	echo '</tbody></table>';
}

function wpbu_render_behaviour_section() {
	echo '<p>' . esc_html__('These fields map to the documented bundled-runtime behaviour options.', 'wp-browser-update') . '</p>';
}

function wpbu_yes_no_select($name, $value) {
	echo '<select name="' . esc_attr($name) . '">';
	echo '<option value="true"' . selected($value, 'true', false) . '>' . esc_html__('Yes', 'wp-browser-update') . '</option>';
	echo '<option value="false"' . selected($value, 'false', false) . '>' . esc_html__('No', 'wp-browser-update') . '</option>';
	echo '</select>';
}

function wpbu_render_behaviour_fields() {
	$options = wpbu_admin_options();
	$behaviour = $options['behaviour'];
	$base = WPBU_OPTIONS_NAME . '[behaviour]';
	$fields = array(
		'reminder'          => __('Reappearance interval in hours', 'wp-browser-update'),
		'reminderClosed'    => __('Reappearance interval after explicit close in hours', 'wp-browser-update'),
		'test'              => __('Testing mode', 'wp-browser-update'),
		'newwindow'         => __('Open update link in a new tab', 'wp-browser-update'),
		'shift_page_down'   => __('Shift page down', 'wp-browser-update'),
		'notify_esr'        => __('Notify Firefox ESR', 'wp-browser-update'),
		'insecure'          => __('Notify insecure browsers', 'wp-browser-update'),
		'unsupported'       => __('Notify unsupported browsers', 'wp-browser-update'),
		'mobile'            => __('Notify mobile browsers', 'wp-browser-update'),
		'noclose'           => __('Hide ignore button', 'wp-browser-update'),
		'nomessage'         => __('Call onshow without showing a message', 'wp-browser-update'),
		'no_permanent_hide' => __('Disable permanent hide option', 'wp-browser-update'),
	);

	echo '<table class="widefat striped"><tbody>';
	echo '<tr><th><label for="wpbu_style">' . esc_html__('Notification position', 'wp-browser-update') . '</label></th><td><select id="wpbu_style" name="' . esc_attr($base) . '[style]">';
	foreach (array('top' => __('Top', 'wp-browser-update'), 'bottom' => __('Bottom', 'wp-browser-update'), 'corner' => __('Corner', 'wp-browser-update')) as $value => $label) {
		echo '<option value="' . esc_attr($value) . '"' . selected($behaviour['style'], $value, false) . '>' . esc_html($label) . '</option>';
	}
	echo '</select></td></tr>';
	echo '<tr><th><label for="wpbu_container">' . esc_html__('Notification container', 'wp-browser-update') . '</label></th><td>';
	echo '<input type="text" class="regular-text code" id="wpbu_container" name="' . esc_attr($base) . '[container]" value="' . esc_attr($behaviour['container']) . '" placeholder="body" />';
	echo '<p class="description">' . esc_html__('Optional CSS selector for the element that should receive the notification. Leave blank to use the bundled runtime default placement.', 'wp-browser-update') . '</p>';
	echo '</td></tr>';

	foreach ($fields as $key => $label) {
		echo '<tr><th><label for="wpbu_behaviour_' . esc_attr($key) . '">' . esc_html($label) . '</label></th><td>';
		if (in_array($key, array('reminder', 'reminderClosed'), true)) {
			echo '<input type="number" class="small-text" min="0" max="9999" id="wpbu_behaviour_' . esc_attr($key) . '" name="' . esc_attr($base) . '[' . esc_attr($key) . ']" value="' . esc_attr($behaviour[$key]) . '" />';
		} else {
			wpbu_yes_no_select($base . '[' . $key . ']', $behaviour[$key]);
		}
		echo '</td></tr>';
	}
	echo '</tbody></table>';
}

function wpbu_render_links_section() {
	echo '<p>' . esc_html__('Optional update-link options. Leave blank to use the bundled runtime defaults.', 'wp-browser-update') . '</p>';
}

function wpbu_render_link_fields() {
	$options = wpbu_admin_options();
	$base = WPBU_OPTIONS_NAME . '[links]';
	$fields = array(
		'url'                => __('Update URL', 'wp-browser-update'),
		'url_permanent_hide' => __('Permanent-hide URL', 'wp-browser-update'),
		'burl'               => __('Base update URL', 'wp-browser-update'),
	);

	foreach ($fields as $key => $label) {
		echo '<p><label for="wpbu_link_' . esc_attr($key) . '"><strong>' . esc_html($label) . '</strong></label><br>';
		echo '<input type="url" class="large-text code" id="wpbu_link_' . esc_attr($key) . '" name="' . esc_attr($base) . '[' . esc_attr($key) . ']" value="' . esc_attr($options['links'][$key]) . '" /></p>';
	}
}

function wpbu_render_language_text_section() {
	echo '<p>' . esc_html__('Set a fixed language or override the bundled notification text. Text override JSON may contain keys such as text_de, text_for_i, or text_for_i_in_de.', 'wp-browser-update') . '</p>';
}

function wpbu_render_language_text_fields() {
	$options = wpbu_admin_options();
	$base = WPBU_OPTIONS_NAME . '[text][default]';
	echo '<p><label for="wpbu_language"><strong>' . esc_html__('Fixed language', 'wp-browser-update') . '</strong></label><br>';
	echo '<input type="text" class="small-text code" id="wpbu_language" name="' . esc_attr(WPBU_OPTIONS_NAME) . '[language][l]" value="' . esc_attr($options['language']['l']) . '" placeholder="en" /></p>';

	foreach (wpbu_text_fields() as $field) {
		echo '<p><label for="wpbu_text_' . esc_attr($field) . '"><strong><code>' . esc_html($field) . '</code></strong></label><br>';
		echo '<textarea class="large-text" rows="2" id="wpbu_text_' . esc_attr($field) . '" name="' . esc_attr($base) . '[' . esc_attr($field) . ']">' . esc_textarea($options['text']['default'][$field]) . '</textarea></p>';
	}

	echo '<p><label for="wpbu_text_overrides"><strong>' . esc_html__('Language/browser text overrides JSON', 'wp-browser-update') . '</strong></label><br>';
	echo '<textarea class="large-text code" rows="8" id="wpbu_text_overrides" name="' . esc_attr(WPBU_OPTIONS_NAME) . '[text][overrides_json]">' . esc_textarea($options['text']['overrides_json']) . '</textarea></p>';
}

function wpbu_render_callbacks_section() {
	echo '<p>' . esc_html__('Callbacks are configured as global JavaScript function names. Script bodies are not stored for security and CSP compatibility.', 'wp-browser-update') . '</p>';
}

function wpbu_render_callback_fields() {
	$options = wpbu_admin_options();
	foreach (array('onshow', 'onclick', 'onclose') as $key) {
		echo '<p><label for="wpbu_callback_' . esc_attr($key) . '"><strong><code>' . esc_html($key) . '</code></strong></label><br>';
		echo '<input type="text" class="regular-text code" id="wpbu_callback_' . esc_attr($key) . '" name="' . esc_attr(WPBU_OPTIONS_NAME) . '[callbacks][' . esc_attr($key) . ']" value="' . esc_attr($options['callbacks'][$key]) . '" placeholder="window.wpbuCallbacks.' . esc_attr($key) . '" /></p>';
	}
}

function wpbu_render_custom_css_section() {
	echo '<p>' . esc_html__('Optional trusted CSS overrides for the bundled notification.', 'wp-browser-update') . '</p>';
}

function wpbu_render_custom_css_field() {
	$options = wpbu_admin_options();
	echo '<textarea id="wpbu_custom_css" name="' . esc_attr(WPBU_OPTIONS_NAME) . '[custom_css]" rows="15" cols="45" class="large-text code" aria-describedby="wpbu_custom_css_description">' . esc_textarea($options['custom_css']) . '</textarea>';
	echo '<p class="description" id="wpbu_custom_css_description">' . sprintf(esc_html__('Override the default CSS with your own rules (%sread more%s). Leave blank to use the default.', 'wp-browser-update'), '<a href="https://browserupdate.org/customize.html" target="_blank" rel="noopener noreferrer">', '</a>') . '</p>';
}

function wpbu_render_runtime_section() {
	echo '<p>' . esc_html__('These runtime options are locked by WP BrowserUpdate to keep the bundled runtime local and CSP-compatible.', 'wp-browser-update') . '</p>';
}

function wpbu_render_runtime_fields() {
	$runtime = array(
		'jsshowurl' => plugins_url(WPBU_BROWSER_UPDATE_SHOW_SCRIPT_FILE, __FILE__),
		'domain'    => untrailingslashit(dirname(plugins_url(WPBU_BROWSER_UPDATE_TEST_SCRIPT_FILE, __FILE__))),
		'api'       => WPBU_BROWSER_UPDATE_API_VERSION,
	);

	echo '<table class="widefat striped"><tbody>';
	foreach ($runtime as $key => $value) {
		echo '<tr><th><code>' . esc_html($key) . '</code></th><td><code>' . esc_html((string) $value) . '</code></td></tr>';
	}
	echo '</tbody></table>';
}

function wpbu_render_settings_page() {
	echo '<div class="wrap">';
	echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';
	echo '<form action="options.php" method="post">';
	settings_fields('wp_browserupdate');
	do_settings_sections('wp-browserupdate');
	submit_button(__('Save Settings', 'wp-browser-update'));
	echo '</form></div>';
}

function wpbu_administration() {
	if (!current_user_can('manage_options')) {
		wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'wp-browser-update'));
	}

	wpbu_render_settings_page();
}

function wpbu_admin() {
	add_options_page('WP BrowserUpdate', 'WP BrowserUpdate', 'manage_options', 'wp-browserupdate', 'wpbu_administration');
}

function wpbu_settings_link($links) {
	return array_merge(array('settings' => '<a href="' . esc_url(admin_url('options-general.php?page=wp-browserupdate')) . '">' . esc_html__('Settings', 'wp-browser-update') . '</a>'), $links);
}

function wpbu_plugin_links($links, $file) {
	if ($file === plugin_basename(__FILE__)) {
		$links[] = '<a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/support/plugin/wp-browser-update" title="' . esc_attr__('Get help', 'wp-browser-update') . '">' . esc_html__('Support', 'wp-browser-update') . '</a> | <a target="_blank" rel="noopener noreferrer" href="https://wpbu.steinbrecher.co/" title="' . esc_attr__('Plugin Homepage', 'wp-browser-update') . '">' . esc_html__('Plugin Homepage', 'wp-browser-update') . '</a> | <a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/support/plugin/wp-browser-update/reviews/#new-post" title="' . esc_attr__('Rate this plugin. Thanks for your support!', 'wp-browser-update') . '">' . esc_html__('Rate this plugin', 'wp-browser-update') . '</a>';
	}

	return $links;
}

add_filter('plugin_action_links_' . basename(dirname(__FILE__)) . '/' . basename(__FILE__), 'wpbu_settings_link');
add_filter('plugin_row_meta', 'wpbu_plugin_links', 10, 2);
add_action('wp_enqueue_scripts', 'wpbu_enqueue_browserupdate');
add_action('wp_ajax_wpbu_config_js', 'wpbu_render_config_js');
add_action('wp_ajax_nopriv_wpbu_config_js', 'wpbu_render_config_js');
add_action('wp_ajax_wpbu_custom_css', 'wpbu_render_custom_css');
add_action('wp_ajax_nopriv_wpbu_custom_css', 'wpbu_render_custom_css');
add_action('admin_init', 'wpbu_register_settings');
add_action('admin_menu', 'wpbu_admin');
