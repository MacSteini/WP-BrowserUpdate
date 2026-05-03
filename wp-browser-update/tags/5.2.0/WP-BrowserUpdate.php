<?php
/*
Plugin Name: WP BrowserUpdate
Plugin URI: https://wpbu.steinbrecher.co/
Description: This plugin notifies website visitors to update their outdated browser in a non-intrusive way. Visit <a href="https://browserupdate.org/" title="browserupdate.org" target="_blank" rel="noopener noreferrer">browserupdate.org</a> for more information…
Version: 5.2.0
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
define('WPBU_BROWSER_UPDATE_SCRIPT_FILE', 'assets/browser-update/upstream/update.min.js');
define('WPBU_BROWSER_UPDATE_SHOW_SCRIPT_FILE', 'assets/browser-update/update.show.wpbu.min.js');
define('WPBU_BROWSER_UPDATE_TEST_SCRIPT_FILE', 'assets/browser-update/update.test.wpbu.js');
define('WPBU_BROWSER_UPDATE_STYLE_FILE', 'assets/browser-update/update.show.wpbu.css');
define('WPBU_BROWSER_UPDATE_API_VERSION', 2026.01);

if (version_compare(PHP_VERSION, MIN_PHP_VERSION, '<')) {
	add_action('admin_notices', function () {
		echo '<div class="notice notice-error"><p><strong>' . sprintf(esc_html__('Your PHP v%s is outdated: This plugin requires PHP v%s or higher. Please update your PHP version or %s for compatibility with older PHP versions…', 'wp-browser-update'), esc_html(PHP_VERSION), esc_html(MIN_PHP_VERSION), '<a href="https://downloads.wordpress.org/plugin/wp-browser-update.4.8.1.zip" rel="noopener noreferrer">' . esc_html__('download plugin version 4.8.1', 'wp-browser-update') . '</a>') . '</strong></p></div>';
	});
	deactivate_plugins(plugin_basename(__FILE__));
	return;
}

function wpbu_default_browser_versions() {
	return array('0', '0', '0', '0', '0', '0');
}

function wpbu_default_js_settings() {
	return array('12', 'false', 'true', 'top', 'true', 'true', 'true', 'true');
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
			'user-agent' => 'WP BrowserUpdate/' . WPBU_BROWSER_UPDATE_API_VERSION . '; ' . home_url('/'),
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

function wpbu_sanitize_reminder_setting($value) {
	$value = (int) wpbu_unslash_scalar($value, '12');

	return (string) max(0, min(99, $value));
}

function wpbu_sanitize_custom_css($css) {
	$css = sanitize_textarea_field(wpbu_unslash_scalar($css));

	return trim(wp_strip_all_tags($css));
}

function wpbu_get_browser_configs($selected_values) {
	$selected_values = array_pad($selected_values, count(wpbu_browser_field_order()), '0');
	$selected = array_combine(wpbu_browser_field_order(), array_slice($selected_values, 0, count(wpbu_browser_field_order())));

	return array(
		'edge'    => array(
			'name'     => 'Microsoft Edge',
			'selected' => $selected['edge'],
			'download' => 'https://microsoft.com/edge',
			'url'      => 'https://en.wikipedia.org/wiki/Microsoft_Edge',
			'xpath'    => "//table[contains(@class,'infobox')]//tr[th//a[text()='Stable release(s)']]/following-sibling::tr[1]//table[contains(@class, 'infobox-subbox')]//tr[th[contains(text(),'Windows')]]/td",
		),
		'msie'    => array(
			'name'     => 'Microsoft Internet Explorer',
			'selected' => $selected['msie'],
			'download' => 'https://blogs.windows.com/windowsexperience/2022/06/15/internet-explorer-11-has-retired-and-is-officially-out-of-support-what-you-need-to-know/',
		),
		'firefox' => array(
			'name'     => 'Mozilla Firefox',
			'selected' => $selected['firefox'],
			'download' => 'https://firefox.com/',
			'url'      => 'https://en.wikipedia.org/wiki/Firefox',
			'xpath'    => "//table[contains(@class,'infobox')]//tr[th//a[text()='Stable release(s)']]/following-sibling::tr[1]//table[contains(@class, 'infobox-subbox')]//tr[th[text()='Standard']]/td",
			'regex'    => '/\d+(\.\d+)+/',
		),
		'opera'   => array(
			'name'     => 'Opera',
			'selected' => $selected['opera'],
			'download' => 'https://opera.com/',
			'url'      => 'https://en.wikipedia.org/wiki/Opera_(web_browser)',
			'xpath'    => "//table[contains(@class,'infobox')]//tr[th//a[text()='Stable release']]/td",
		),
		'safari'  => array(
			'name'     => 'Apple Safari',
			'selected' => $selected['safari'],
			'download' => 'https://support.apple.com/102665',
			'url'      => 'https://support.apple.com/en-us/100100',
			'xpath'    => "(//a[starts-with(normalize-space(.), 'Safari ')])[1]",
			'regex'    => '/\d+(?:\.\d+)+/',
		),
		'google'  => array(
			'name'     => 'Google Chrome',
			'selected' => $selected['google'],
			'download' => 'https://chrome.google.com/',
			'url'      => 'https://en.wikipedia.org/wiki/Google_Chrome',
			'xpath'    => "//table[contains(@class,'infobox')]//tr[th//a[text()='Stable release(s)']]/following-sibling::tr[1]//table[contains(@class, 'infobox-subbox')]//tr[th[contains(text(),'Windows')]]/td",
		),
	);
}

function wpbu_browser_field_order() {
	return array('edge', 'firefox', 'opera', 'safari', 'google', 'msie');
}

function wpbu_js_setting_keys() {
	return array('wpbu_reminder', 'wpbu_testing', 'wpbu_newwindow', 'wpbu_style', 'wpbu_secis', 'wpbu_unsup', 'wpbu_mobile', 'wpbu_shift');
}

function wpbu_get_browser_versions_option() {
	$values = explode(' ', get_option('wp_browserupdate_browsers', implode(' ', wpbu_default_browser_versions())));

	return array_slice(array_pad($values, count(wpbu_browser_field_order()), '0'), 0, count(wpbu_browser_field_order()));
}

function wpbu_get_js_settings_option() {
	$defaults = wpbu_default_js_settings();
	$settings = explode(' ', get_option('wp_browserupdate_js', implode(' ', $defaults)));
	$settings = array_pad($settings, 8, null);

	foreach ($defaults as $index => $default) {
		if ($settings[$index] === null || $settings[$index] === '') {
			$settings[$index] = $default;
		}
	}

	return $settings;
}

function wpbu_get_buoop_config() {
	$raw = wpbu_get_browser_versions_option();
	$js = wpbu_get_js_settings_option();

	return array(
		'required'        => array(
			'e' => wpbu_format_required_version_for_buorg($raw[0]),
			'f' => wpbu_format_required_version_for_buorg($raw[1]),
			'o' => wpbu_format_required_version_for_buorg($raw[2]),
			's' => wpbu_format_required_version_for_buorg($raw[3]),
			'c' => wpbu_format_required_version_for_buorg($raw[4]),
			'i' => wpbu_format_required_version_for_buorg($raw[5]),
		),
		'reminder'        => (int) $js[0],
		'test'            => $js[1] === 'true',
		'newwindow'       => $js[2] === 'true',
		'style'           => in_array($js[3], array('top', 'bottom', 'corner'), true) ? $js[3] : 'top',
		'insecure'        => $js[4] === 'true',
		'unsupported'     => $js[5] === 'true',
		'mobile'          => $js[6] === 'true',
		'shift_page_down' => $js[7] === 'true',
		'jsshowurl'       => plugins_url(WPBU_BROWSER_UPDATE_SHOW_SCRIPT_FILE, __FILE__),
		'domain'          => untrailingslashit(dirname(plugins_url(WPBU_BROWSER_UPDATE_TEST_SCRIPT_FILE, __FILE__))),
		'api'             => WPBU_BROWSER_UPDATE_API_VERSION,
	);
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
	header('Content-Type: application/javascript; charset=' . get_option('blog_charset'));
	echo 'window.$buoop = ' . wp_json_encode(wpbu_get_buoop_config(), JSON_UNESCAPED_SLASHES) . ';';
	exit;
}

function wpbu_get_custom_css() {
	return wpbu_sanitize_custom_css(get_option('wp_browserupdate_css_buorg', ''));
}

function wpbu_render_custom_css() {
	header('Content-Type: text/css; charset=' . get_option('blog_charset'));
	echo wpbu_get_custom_css();
	exit;
}

function wpbu_sanitize_browser_versions_option($value) {
	$order = wpbu_browser_field_order();
	$browsers = array();

	if (is_array($value)) {
		foreach ($order as $field) {
			$browsers[] = wpbu_sanitize_version_setting($value[$field] ?? '0');
		}
	} else {
		$values = explode(' ', wpbu_unslash_scalar($value, implode(' ', wpbu_default_browser_versions())));
		$values = array_pad($values, count($order), '0');

		foreach (array_keys($order) as $index) {
			$browsers[] = wpbu_sanitize_version_setting($values[$index] ?? '0');
		}
	}

	return implode(' ', $browsers);
}

function wpbu_sanitize_js_settings_option($value) {
	$defaults = wpbu_default_js_settings();
	$keys = wpbu_js_setting_keys();
	$settings = array();

	if (is_array($value)) {
		$raw = $value;
	} else {
		$values = array_pad(explode(' ', wpbu_unslash_scalar($value, implode(' ', $defaults))), count($keys), null);
		$raw = array_combine($keys, array_slice($values, 0, count($keys)));
	}

	$settings[] = wpbu_sanitize_reminder_setting($raw['wpbu_reminder'] ?? $defaults[0]);
	$settings[] = wpbu_sanitize_bool_string($raw['wpbu_testing'] ?? $defaults[1], $defaults[1]);
	$settings[] = wpbu_sanitize_bool_string($raw['wpbu_newwindow'] ?? $defaults[2], $defaults[2]);
	$settings[] = wpbu_sanitize_style_setting($raw['wpbu_style'] ?? $defaults[3]);
	$settings[] = wpbu_sanitize_bool_string($raw['wpbu_secis'] ?? $defaults[4], $defaults[4]);
	$settings[] = wpbu_sanitize_bool_string($raw['wpbu_unsup'] ?? $defaults[5], $defaults[5]);
	$settings[] = wpbu_sanitize_bool_string($raw['wpbu_mobile'] ?? $defaults[6], $defaults[6]);
	$settings[] = wpbu_sanitize_bool_string($raw['wpbu_shift'] ?? $defaults[7], $defaults[7]);

	return implode(' ', $settings);
}

function wpbu_register_settings() {
	register_setting('wp_browserupdate', 'wp_browserupdate_browsers', 'wpbu_sanitize_browser_versions_option');
	register_setting('wp_browserupdate', 'wp_browserupdate_js', 'wpbu_sanitize_js_settings_option');
	register_setting('wp_browserupdate', 'wp_browserupdate_css_buorg', 'wpbu_sanitize_custom_css');

	add_settings_section('wpbu_browser_versions', __('Outdated Browser Versions', 'wp-browser-update'), 'wpbu_render_browser_versions_section', 'wp-browserupdate');

	foreach (wpbu_get_browser_configs(wpbu_default_browser_versions()) as $key => $browser) {
		add_settings_field('wpbu_browser_' . $key, $browser['name'], 'wpbu_render_browser_version_field', 'wp-browserupdate', 'wpbu_browser_versions', array('key' => $key, 'label_for' => 'wpbu_' . $key));
	}

	add_settings_section('wpbu_notification_behaviour', __('Notification Behaviour', 'wp-browser-update'), 'wpbu_render_notification_behaviour_section', 'wp-browserupdate');
	add_settings_field('wpbu_reminder', __('Reappearance Interval', 'wp-browser-update'), 'wpbu_render_reminder_field', 'wp-browserupdate', 'wpbu_notification_behaviour', array('label_for' => 'wpbu_reminder'));
	add_settings_field('wpbu_newwindow', __('Open Links in New Tab', 'wp-browser-update'), 'wpbu_render_select_field', 'wp-browserupdate', 'wpbu_notification_behaviour', array('name' => 'wpbu_newwindow', 'label_for' => 'wpbu_newwindow'));
	add_settings_field('wpbu_testing', __('Testing Mode', 'wp-browser-update'), 'wpbu_render_select_field', 'wp-browserupdate', 'wpbu_notification_behaviour', array('name' => 'wpbu_testing', 'label_for' => 'wpbu_testing'));
	add_settings_field('wpbu_style', __('Notification Position', 'wp-browser-update'), 'wpbu_render_select_field', 'wp-browserupdate', 'wpbu_notification_behaviour', array('name' => 'wpbu_style', 'label_for' => 'wpbu_style'));
	add_settings_field('wpbu_secis', __('Notify Security Risks', 'wp-browser-update'), 'wpbu_render_select_field', 'wp-browserupdate', 'wpbu_notification_behaviour', array('name' => 'wpbu_secis', 'label_for' => 'wpbu_secis'));
	add_settings_field('wpbu_unsup', __('Notify Unsupported Browsers', 'wp-browser-update'), 'wpbu_render_select_field', 'wp-browserupdate', 'wpbu_notification_behaviour', array('name' => 'wpbu_unsup', 'label_for' => 'wpbu_unsup'));
	add_settings_field('wpbu_mobile', __('Notify Mobile Browsers', 'wp-browser-update'), 'wpbu_render_select_field', 'wp-browserupdate', 'wpbu_notification_behaviour', array('name' => 'wpbu_mobile', 'label_for' => 'wpbu_mobile'));
	add_settings_field('wpbu_shift', __('Prevent Content Overlap', 'wp-browser-update'), 'wpbu_render_select_field', 'wp-browserupdate', 'wpbu_notification_behaviour', array('name' => 'wpbu_shift', 'label_for' => 'wpbu_shift'));

	add_settings_section('wpbu_custom_css', __('Custom CSS', 'wp-browser-update'), 'wpbu_render_custom_css_section', 'wp-browserupdate');
	add_settings_field('wp_browserupdate_css_buorg', __('Custom CSS', 'wp-browser-update'), 'wpbu_render_custom_css_field', 'wp-browserupdate', 'wpbu_custom_css', array('label_for' => 'wpbu_css_buorg'));
}

function wpbu_migrate_negative_browser_versions($browser_values, $browsers) {
	$needs_migration = false;

	foreach (wpbu_browser_field_order() as $index => $key) {
		if (!isset($browsers[$key])) {
			continue;
		}

		$value = $browser_values[$index] ?? '0';

		if (!is_numeric($value) || (float) $value >= 0) {
			continue;
		}

		$browser = $browsers[$key];
		if (empty($browser['url']) || empty($browser['xpath'])) {
			continue;
		}

		$regex = isset($browser['regex']) ? $browser['regex'] : '/\d+(\.\d+)+/';
		$version = wpbu_getversion_cached($browser['url'], $browser['xpath'], $regex);

		if (!is_string($version) || !preg_match('/^([\d\.]+)/', $version, $match)) {
			continue;
		}

		$parts = explode('.', $match[1]);
		$parts[0] = max(0, (int) $parts[0] - abs((int) $value));
		$browser_values[$index] = implode('.', $parts);
		$needs_migration = true;
	}

	if ($needs_migration) {
		update_option('wp_browserupdate_browsers', implode(' ', $browser_values));
	}

	return $needs_migration ? wpbu_get_browser_versions_option() : $browser_values;
}

function wpbu_get_admin_browser_configs() {
	static $browsers = null;

	if ($browsers !== null) {
		return $browsers;
	}

	$browser_values = wpbu_get_browser_versions_option();
	$browsers = wpbu_get_browser_configs($browser_values);
	$browser_values = wpbu_migrate_negative_browser_versions($browser_values, $browsers);
	$browsers = wpbu_get_browser_configs($browser_values);

	return $browsers;
}

function wpbu_get_js_settings_map() {
	$keys = wpbu_js_setting_keys();
	$wpbu_js = array_slice(wpbu_get_js_settings_option(), 0, count($keys));

	return array_combine($keys, $wpbu_js);
}

function wpbu_get_select_field_configs() {
	$wpbu_values = wpbu_get_js_settings_map();

	return array(
		'wpbu_newwindow' => array('description' => __('Open the notification bar link in a new browser tab or window.', 'wp-browser-update'), 'value' => $wpbu_values['wpbu_newwindow']),
		'wpbu_testing'   => array('description' => __('Always display the notification bar (useful for testing).', 'wp-browser-update'), 'value' => $wpbu_values['wpbu_testing']),
		'wpbu_style'     => array('description' => __('Select where the notification bar should appear on the page.', 'wp-browser-update'), 'value' => $wpbu_values['wpbu_style'], 'options' => array('top' => __('Top', 'wp-browser-update'), 'bottom' => __('Bottom', 'wp-browser-update'), 'corner' => __('Corner', 'wp-browser-update'))),
		'wpbu_secis'     => array('description' => __('Alert users of all browser versions with serious security vulnerabilities.', 'wp-browser-update'), 'value' => $wpbu_values['wpbu_secis']),
		'wpbu_unsup'     => array('description' => __('Include browsers that are no longer supported by their vendor.', 'wp-browser-update'), 'value' => $wpbu_values['wpbu_unsup']),
		'wpbu_mobile'    => array('description' => __('Enable notifications for mobile browsers.', 'wp-browser-update'), 'value' => $wpbu_values['wpbu_mobile']),
		'wpbu_shift'     => array('description' => __('Adjust the page layout to avoid content being obscured by the notification bar (adds margin-top to the body tag).', 'wp-browser-update'), 'value' => $wpbu_values['wpbu_shift']),
	);
}

function wpbu_render_browser_versions_section() {
	echo '<p>' . esc_html__('Select the browser versions you consider outdated, including all earlier versions.', 'wp-browser-update') . '</p>';
	echo '<p>' . esc_html__('Use 0 for the Browser-Update.org default detection, a major version such as 137, an exact dotted version such as 137.0.3912.63, or a negative whole number such as -2 for latest minus 2 major versions.', 'wp-browser-update') . '</p>';
	echo '<p>' . esc_html__('Microsoft Edge and Microsoft Internet Explorer are configured separately and passed to Browser-Update.org as separate browser keys.', 'wp-browser-update') . '</p>';
}

function wpbu_render_browser_version_field($args) {
	$key = $args['key'] ?? '';
	$browsers = wpbu_get_admin_browser_configs();

	if (!isset($browsers[$key])) {
		return;
	}

	$browser = $browsers[$key];
	$version = '';

	if (!empty($browser['url']) && !empty($browser['xpath'])) {
		$regex = isset($browser['regex']) ? $browser['regex'] : '/\d+\.\d+\.\d+\.\d+/';
		$version = wpbu_getversion_cached($browser['url'], $browser['xpath'], $regex);
	}

	$selected_raw = trim((string) ($browser['selected'] ?? '0'));
	$required_version = wpbu_format_required_version_for_buorg($selected_raw);
	$field_id = 'wpbu_' . $key;
	$description_id = $field_id . '_description';

	echo '<input type="text" class="regular-text code" pattern="(?:-?[0-9]+|[0-9]+(?:\.[0-9]+)+)" name="wp_browserupdate_browsers[' . esc_attr($key) . ']" id="' . esc_attr($field_id) . '" value="' . esc_attr($browser['selected']) . '" aria-describedby="' . esc_attr($description_id) . '" title="' . esc_attr__('Use a whole number, a positive dotted version, or a negative whole number.', 'wp-browser-update') . '" />';
	echo '<p class="description" id="' . esc_attr($description_id) . '">';

	if ($required_version === 0) {
		echo esc_html__('Detection: show all outdated versions (default)', 'wp-browser-update');
	} elseif (is_int($required_version) && $required_version < 0) {
		echo sprintf(esc_html__('Detection: latest − %d major versions', 'wp-browser-update'), abs($required_version));
	} elseif (is_string($required_version)) {
		echo sprintf(esc_html__('Detection uses exact version: %s', 'wp-browser-update'), esc_html($required_version));
	} else {
		echo sprintf(esc_html__('Detection uses major version: %s', 'wp-browser-update'), esc_html($required_version));
	}

	if ($version) {
		echo ' — ' . esc_html__('Latest version', 'wp-browser-update') . ': ' . esc_html($version);
	}

	echo ' — <a href="' . esc_url($browser['download']) . '" target="_blank" rel="noopener noreferrer">' . esc_html__('Browser download page', 'wp-browser-update') . '</a>';
	echo '</p>';
}

function wpbu_render_notification_behaviour_section() {
	echo '<p>' . esc_html__('Configure how the Browser-Update.org notification behaves on public pages.', 'wp-browser-update') . '</p>';
}

function wpbu_render_reminder_field() {
	$wpbu_values = wpbu_get_js_settings_map();

	echo '<input type="number" class="small-text" value="' . esc_attr($wpbu_values['wpbu_reminder']) . '" id="wpbu_reminder" name="wp_browserupdate_js[wpbu_reminder]" min="0" max="99" step="1" required aria-describedby="wpbu_reminder_description" />';
	echo '<p class="description" id="wpbu_reminder_description">' . esc_html__('How many hours before the message should reappear (0 = always show).', 'wp-browser-update') . '</p>';
}

function wpbu_render_select_field($args) {
	$name = $args['name'] ?? '';
	$fields = wpbu_get_select_field_configs();

	if (!isset($fields[$name])) {
		return;
	}

	$field = $fields[$name];

	echo '<select id="' . esc_attr($name) . '" name="wp_browserupdate_js[' . esc_attr($name) . ']" aria-describedby="' . esc_attr($name . '_description') . '">';

	if (!empty($field['options'])) {
		foreach ($field['options'] as $key => $label) {
			echo '<option value="' . esc_attr($key) . '"' . selected($field['value'], $key, false) . '>' . esc_html($label) . '</option>';
		}
	} else {
		echo '<option value="true"' . selected($field['value'], 'true', false) . '>' . esc_html__('Yes', 'wp-browser-update') . '</option>';
		echo '<option value="false"' . selected($field['value'], 'false', false) . '>' . esc_html__('No', 'wp-browser-update') . '</option>';
	}

	echo '</select>';
	echo '<p class="description" id="' . esc_attr($name . '_description') . '">' . esc_html($field['description']) . '</p>';
}

function wpbu_render_custom_css_section() {
	echo '<p>' . esc_html__('Optional trusted CSS overrides for the Browser-Update.org notification.', 'wp-browser-update') . '</p>';
}

function wpbu_render_custom_css_field() {
	$wpbu_css_buorg = get_option('wp_browserupdate_css_buorg', '');

	echo '<textarea id="wpbu_css_buorg" name="wp_browserupdate_css_buorg" rows="15" cols="45" class="large-text code" aria-describedby="wpbu_css_buorg_description">' . esc_textarea($wpbu_css_buorg) . '</textarea>';
	echo '<p class="description" id="wpbu_css_buorg_description">' . sprintf(esc_html__('Override the default CSS with your own rules (%sread more%s). Leave blank to use the default.', 'wp-browser-update'), '<a href="https://browserupdate.org/customize.html" target="_blank" rel="noopener noreferrer">', '</a>') . '</p>';
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
