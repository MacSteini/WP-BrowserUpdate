<?php
/*
Plugin Name: WP BrowserUpdate
Plugin URI: https://wpbu.steinbrecher.co/
Description: This plugin notifies website visitors to update their outdated browser in a non-intrusive way. Visit <a href="https://browserupdate.org/" title="browserupdate.org" target="_blank" rel="noopener noreferrer">browserupdate.org</a> for more information…
Version: 5.1.1
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
define('WPBU_BROWSER_UPDATE_SCRIPT_URL', 'https://browser-update.org/update.min.js');
define('WPBU_BROWSER_UPDATE_API_VERSION', 2026.01);

if (version_compare(PHP_VERSION, MIN_PHP_VERSION, '<')) {
	add_action('admin_notices', function () {
		echo '<div class="notice notice-error"><p><strong>' . sprintf(esc_html__('Your PHP v%s is outdated: This plugin requires PHP v%s or higher. Please update your PHP version or %s for compatibility with older PHP versions…', 'wp-browser-update'), esc_html(PHP_VERSION), esc_html(MIN_PHP_VERSION), '<a href="https://downloads.wordpress.org/plugin/wp-browser-update.4.8.1.zip" rel="noopener noreferrer">' . esc_html__('download plugin version 4.8.1', 'wp-browser-update') . '</a>') . '</strong></p></div>';
	});
	deactivate_plugins(plugin_basename(__FILE__));
	return;
}

function wpbu_default_browser_versions() {
	return array('0', '0', '0', '0', '0');
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

function wpbu_normalize_version_for_buorg($v) {
	$v = trim((string) $v);

	if ($v === '') {
		return 0;
	}

	if (preg_match('/^-?\d+$/', $v)) {
		return (int) $v;
	}

	if (preg_match('/^\d+(?:\.\d+)+$/', $v)) {
		return (int) explode('.', $v)[0];
	}

	if (preg_match('/^-?\d+/', $v, $m)) {
		return (int) $m[0];
	}

	return 0;
}

function wpbu_sanitize_version_setting($value) {
	$value = trim(wpbu_unslash_scalar($value, '0'));
	$value = preg_replace('/(?!^-)[^0-9.]/', '', $value);

	if (!preg_match('/^-?\d+(\.\d+)*$/', $value)) {
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
	$selected_values = array_pad($selected_values, 5, '0');

	return array(
		'msie'    => array(
			'name'     => 'Microsoft Edge',
			'selected' => $selected_values[0],
			'download' => 'https://microsoft.com/edge',
			'url'      => 'https://en.wikipedia.org/wiki/Microsoft_Edge',
			'xpath'    => "//table[contains(@class,'infobox')]//tr[th//a[text()='Stable release(s)']]/following-sibling::tr[1]//table[contains(@class, 'infobox-subbox')]//tr[th[contains(text(),'Windows')]]/td",
		),
		'firefox' => array(
			'name'     => 'Mozilla Firefox',
			'selected' => $selected_values[1],
			'download' => 'https://firefox.com/',
			'url'      => 'https://en.wikipedia.org/wiki/Firefox',
			'xpath'    => "//table[contains(@class,'infobox')]//tr[th//a[text()='Stable release(s)']]/following-sibling::tr[1]//table[contains(@class, 'infobox-subbox')]//tr[th[text()='Standard']]/td",
			'regex'    => '/\d+(\.\d+)+/',
		),
		'opera'   => array(
			'name'     => 'Opera',
			'selected' => $selected_values[2],
			'download' => 'https://opera.com/',
			'url'      => 'https://en.wikipedia.org/wiki/Opera_(web_browser)',
			'xpath'    => "//table[contains(@class,'infobox')]//tr[th//a[text()='Stable release']]/td",
		),
		'safari'  => array(
			'name'     => 'Apple Safari',
			'selected' => $selected_values[3],
			'download' => 'https://support.apple.com/102665',
			'url'      => 'https://support.apple.com/en-us/100100',
			'xpath'    => "(//a[starts-with(normalize-space(.), 'Safari ')])[1]",
			'regex'    => '/\d+(?:\.\d+)+/',
		),
		'google'  => array(
			'name'     => 'Google Chrome',
			'selected' => $selected_values[4],
			'download' => 'https://chrome.google.com/',
			'url'      => 'https://en.wikipedia.org/wiki/Google_Chrome',
			'xpath'    => "//table[contains(@class,'infobox')]//tr[th//a[text()='Stable release(s)']]/following-sibling::tr[1]//table[contains(@class, 'infobox-subbox')]//tr[th[contains(text(),'Windows')]]/td",
		),
	);
}

function wpbu_get_browser_versions_option() {
	$values = explode(' ', get_option('wp_browserupdate_browsers', implode(' ', wpbu_default_browser_versions())));

	return array_pad($values, 5, '0');
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
			'e' => wpbu_normalize_version_for_buorg($raw[0]),
			'f' => wpbu_normalize_version_for_buorg($raw[1]),
			'o' => wpbu_normalize_version_for_buorg($raw[2]),
			's' => wpbu_normalize_version_for_buorg($raw[3]),
			'c' => wpbu_normalize_version_for_buorg($raw[4]),
		),
		'reminder'        => (int) $js[0],
		'test'            => $js[1] === 'true',
		'newwindow'       => $js[2] === 'true',
		'style'           => in_array($js[3], array('top', 'bottom', 'corner'), true) ? $js[3] : 'top',
		'insecure'        => $js[4] === 'true',
		'unsupported'     => $js[5] === 'true',
		'mobile'          => $js[6] === 'true',
		'shift_page_down' => $js[7] === 'true',
		'api'             => WPBU_BROWSER_UPDATE_API_VERSION,
	);
}

function wpbu_enqueue_browserupdate() {
	wp_enqueue_script('wp-browser-update-browserupdate', WPBU_BROWSER_UPDATE_SCRIPT_URL, array(), null, true);
	wp_add_inline_script('wp-browser-update-browserupdate', 'var $buoop = ' . wp_json_encode(wpbu_get_buoop_config(), JSON_UNESCAPED_SLASHES) . ';', 'before');
}

function wpbu_handle_settings_update() {
	if (!isset($_POST['wpbu_submit'])) {
		return false;
	}

	check_admin_referer('wpbu_settings', 'wpbu_nonce');

	$browser_fields = array('wpbu_msie', 'wpbu_firefox', 'wpbu_opera', 'wpbu_safari', 'wpbu_google');
	$browsers = array();

	foreach ($browser_fields as $field) {
		$browsers[] = wpbu_sanitize_version_setting($_POST[$field] ?? '0');
	}

	$js_settings = array(
		wpbu_sanitize_reminder_setting($_POST['wpbu_reminder'] ?? 12),
		wpbu_sanitize_bool_string($_POST['wpbu_testing'] ?? 'false', 'false'),
		wpbu_sanitize_bool_string($_POST['wpbu_newwindow'] ?? 'true', 'true'),
		wpbu_sanitize_style_setting($_POST['wpbu_style'] ?? 'top'),
		wpbu_sanitize_bool_string($_POST['wpbu_secis'] ?? 'true', 'true'),
		wpbu_sanitize_bool_string($_POST['wpbu_unsup'] ?? 'true', 'true'),
		wpbu_sanitize_bool_string($_POST['wpbu_mobile'] ?? 'true', 'true'),
		wpbu_sanitize_bool_string($_POST['wpbu_shift'] ?? 'true', 'true'),
	);

	update_option('wp_browserupdate_browsers', implode(' ', $browsers));
	update_option('wp_browserupdate_js', implode(' ', $js_settings));
	update_option('wp_browserupdate_css_buorg', wpbu_sanitize_custom_css($_POST['wpbu_css_buorg'] ?? ''));

	return true;
}

function wpbu_migrate_negative_browser_versions($browser_values, $browsers) {
	$needs_migration = false;

	foreach (array_keys($browsers) as $index => $key) {
		$value = $browser_values[$index] ?? '0';

		if (!is_numeric($value) || (float) $value >= 0) {
			continue;
		}

		$browser = $browsers[$key];
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

function wpbu_render_browser_rows($browsers) {
	$output = '<table class="form-table">';

	foreach ($browsers as $key => $browser) {
		$version = '';

		if (!empty($browser['url']) && !empty($browser['xpath'])) {
			$regex = isset($browser['regex']) ? $browser['regex'] : '/\d+\.\d+\.\d+\.\d+/';
			$version = wpbu_getversion_cached($browser['url'], $browser['xpath'], $regex);
		}

		$selected_raw = trim((string) ($browser['selected'] ?? '0'));
		$normalized = wpbu_normalize_version_for_buorg($selected_raw);

		$output .= '<tr><th scope="row"><label for="wpbu_' . esc_attr($key) . '"><a href="' . esc_url($browser['download']) . '" target="_blank" rel="noopener noreferrer">' . esc_html($browser['name']) . '</a></label></th><td><input type="text" pattern="^-?[0-9]+(\.[0-9]+)*$" name="wpbu_' . esc_attr($key) . '" id="wpbu_' . esc_attr($key) . '" value="' . esc_attr($browser['selected']) . '" title="' . esc_attr__('Only numbers, dots and an optional leading minus are allowed', 'wp-browser-update') . '" size="12" />';

		if ($selected_raw === '0') {
			$output .= ' <small>' . esc_html__('Detection: show all outdated versions (default)', 'wp-browser-update') . '</small>';
		} elseif ($normalized < 0) {
			$output .= ' <small>' . sprintf(esc_html__('Detection: latest − %d major versions', 'wp-browser-update'), abs($normalized)) . '</small>';
		} else {
			$output .= ' <small>' . sprintf(esc_html__('Detection uses major version: %s', 'wp-browser-update'), esc_html($normalized)) . '</small>';
		}

		if ($version) {
			$output .= ' <small> — ' . esc_html__('Latest version', 'wp-browser-update') . ': ' . esc_html($version) . '</small>';
		}

		$output .= '</td></tr>';
	}

	return $output . '</table>';
}

function wpbu_render_select_field($name, $field) {
	echo '<p><label for="' . esc_attr($name) . '"><strong>' . esc_html($field['label']) . ':</strong></label><br><select id="' . esc_attr($name) . '" name="' . esc_attr($name) . '">';

	if (!empty($field['options'])) {
		foreach ($field['options'] as $key => $label) {
			echo '<option value="' . esc_attr($key) . '"' . selected($field['value'], $key, false) . '>' . esc_html($label) . '</option>';
		}
	} else {
		echo '<option value="true"' . selected($field['value'], 'true', false) . '>' . esc_html__('Yes', 'wp-browser-update') . '</option>';
		echo '<option value="false"' . selected($field['value'], 'false', false) . '>' . esc_html__('No', 'wp-browser-update') . '</option>';
	}

	echo '</select><br>' . esc_html($field['description']) . '</p>';
}

function wpbu_render_settings_page() {
	$browser_values = wpbu_get_browser_versions_option();
	$browsers = wpbu_get_browser_configs($browser_values);
	$browser_values = wpbu_migrate_negative_browser_versions($browser_values, $browsers);
	$browsers = wpbu_get_browser_configs($browser_values);
	$wpbu_js = wpbu_get_js_settings_option();
	$wpbu_keys = array('wpbu_reminder', 'wpbu_testing', 'wpbu_newwindow', 'wpbu_style', 'wpbu_secis', 'wpbu_unsup', 'wpbu_mobile', 'wpbu_shift');
	$wpbu_values = array_combine($wpbu_keys, $wpbu_js);

	$select_fields = array(
		'wpbu_newwindow' => array('label' => __('Open Links in New Tab', 'wp-browser-update'), 'description' => __('Open the notification bar link in a new browser tab or window.', 'wp-browser-update'), 'value' => $wpbu_values['wpbu_newwindow']),
		'wpbu_testing'   => array('label' => __('Testing Mode', 'wp-browser-update'), 'description' => __('Always display the notification bar (useful for testing).', 'wp-browser-update'), 'value' => $wpbu_values['wpbu_testing']),
		'wpbu_style'     => array('label' => __('Notification Position', 'wp-browser-update'), 'description' => __('Select where the notification bar should appear on the page.', 'wp-browser-update'), 'value' => $wpbu_values['wpbu_style'], 'options' => array('top' => __('Top', 'wp-browser-update'), 'bottom' => __('Bottom', 'wp-browser-update'), 'corner' => __('Corner', 'wp-browser-update'))),
		'wpbu_secis'     => array('label' => __('Notify Security Risks', 'wp-browser-update'), 'description' => __('Alert users of all browser versions with serious security vulnerabilities.', 'wp-browser-update'), 'value' => $wpbu_values['wpbu_secis']),
		'wpbu_unsup'     => array('label' => __('Notify Unsupported Browsers', 'wp-browser-update'), 'description' => __('Include browsers that are no longer supported by their vendor.', 'wp-browser-update'), 'value' => $wpbu_values['wpbu_unsup']),
		'wpbu_mobile'    => array('label' => __('Notify Mobile Browsers', 'wp-browser-update'), 'description' => __('Enable notifications for mobile browsers.', 'wp-browser-update'), 'value' => $wpbu_values['wpbu_mobile']),
		'wpbu_shift'     => array('label' => __('Prevent Content Overlap', 'wp-browser-update'), 'description' => __('Adjust the page layout to avoid content being obscured by the notification bar (adds margin-top to the body tag).', 'wp-browser-update'), 'value' => $wpbu_values['wpbu_shift']),
	);

	$wpbu_css_buorg = get_option('wp_browserupdate_css_buorg', '');

	echo '<div class="wrap"><form action="' . esc_url(menu_page_url('wp-browserupdate', false)) . '" method="post">';
	wp_nonce_field('wpbu_settings', 'wpbu_nonce');
	echo '<h1>WP BrowserUpdate</h1><h2>' . esc_html__('Outdated Browser Versions', 'wp-browser-update') . '</h2><p>' . esc_html__('Select the browser versions you consider outdated (including all earlier versions). If left unchanged, WP BrowserUpdate will use the default settings.', 'wp-browser-update') . '</p><p>' . esc_html__('If you set the browser version to 0, a notification will be shown for every outdated browser version.', 'wp-browser-update') . '</p>';
	echo wpbu_render_browser_rows($browsers);
	echo '<h2>' . esc_html__('Script Customizations', 'wp-browser-update') . '</h2><p><label for="wpbu_reminder"><strong>' . esc_html__('Reappearance Interval', 'wp-browser-update') . ':</strong></label><br><input type="number" value="' . esc_attr($wpbu_values['wpbu_reminder']) . '" id="wpbu_reminder" name="wpbu_reminder" min="0" max="99" step="1" required placeholder="' . esc_attr__('How many hours before the message should reappear (0 = Always show)?', 'wp-browser-update') . '"><br>' . esc_html__('How many hours before the message should reappear (0 = Always show)?', 'wp-browser-update') . '</p>';

	foreach ($select_fields as $name => $field) {
		wpbu_render_select_field($name, $field);
	}

	echo '<p><label for="wpbu_css_buorg"><strong>' . esc_html__('Custom CSS', 'wp-browser-update') . ':</strong></label><br><textarea id="wpbu_css_buorg" name="wpbu_css_buorg" rows="15" cols="45">' . esc_textarea($wpbu_css_buorg) . '</textarea><br>' . sprintf(esc_html__('Override the default CSS with your own rules (%sread more%s) – leave blank to use the default.', 'wp-browser-update'), '<a href="https://browserupdate.org/customize.html" target="_blank" rel="noopener noreferrer">', '</a>') . '</p><p class="submit"><input type="submit" name="wpbu_submit" id="submit" class="button button-primary" value="' . esc_attr__('Update Settings', 'wp-browser-update') . '" /></p></form></div>';
}

function wpbu_administration() {
	if (!current_user_can('manage_options')) {
		wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'wp-browser-update'));
	}

	if (wpbu_handle_settings_update()) {
		echo '<div class="updated"><p><strong>' . esc_html__('Settings saved.', 'wp-browser-update') . '</strong></p></div>';
	}

	wpbu_render_settings_page();
}

function wpbu_css() {
	$wpbu_css_buorg = wpbu_sanitize_custom_css(get_option('wp_browserupdate_css_buorg', ''));

	if (!empty($wpbu_css_buorg)) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSS is sanitised by wpbu_sanitize_custom_css().
		echo '<style id="wpbu-custom-css">' . $wpbu_css_buorg . "\n\n</style>";
	}
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
add_action('wp_head', 'wpbu_css');
add_action('admin_menu', 'wpbu_admin');
