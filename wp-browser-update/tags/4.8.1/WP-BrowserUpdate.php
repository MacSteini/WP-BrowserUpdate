<?php
/*
Plugin Name: WP BrowserUpdate
Plugin URI: https://wpbu.steinbrecher.co/
Description: This plugin notifies website visitors to update their outdated browser in a non-intrusive way. Visit <a href="https://browserupdate.org/" title="browserupdate.org" target="_blank">browserupdate.org</a> for more information…
Version: 4.8.1
Author: Marco Steinbrecher
Author URI: https://profiles.wordpress.org/macsteini
Requires at least: 4.6
License: GPLv3 or later
License URI: https://gnu.org/licenses/gpl
*/

if (!defined('ABSPATH')) die();

function wpbu() {
$wpbu_vars = explode(' ', get_option('wp_browserupdate_browsers', '0 0 0 0 0'));
$wpbu_js = explode(' ', get_option('wp_browserupdate_js', '12 false true top true true true true'));
$browser = 'e:'.$wpbu_vars[0].',f:'.$wpbu_vars[1].',o:'.$wpbu_vars[2].',s:'.$wpbu_vars[3].(!isset($wpbu_vars[4])?'':',c:'.$wpbu_vars[4]);

echo '<script type="text/javascript">
var $buoop = {required:{'.$browser.'},test:'.(isset($wpbu_js[1]) ? $wpbu_js[1] : '').',newwindow:'.(isset($wpbu_js[2]) ? $wpbu_js[2] : '').',style:"'.(isset($wpbu_js[3]) ? $wpbu_js[3] : '').'",insecure:'.(isset($wpbu_js[4]) ? $wpbu_js[4] : '').',unsupported:'.(isset($wpbu_js[5]) ? $wpbu_js[5] : '').',mobile:'.(isset($wpbu_js[6]) ? $wpbu_js[6] : '').',shift_page_down:'.(isset($wpbu_js[7]) ? $wpbu_js[7] : '').',api:2025.04};

function $buo_f(){
var e = document.createElement("script");
e.src = "//browser-update.org/update.min.js";
document.body.appendChild(e);
};
try {document.addEventListener("DOMContentLoaded", $buo_f, false)}
catch(e){window.attachEvent("onload", $buo_f)}
</script>';
}

function wpbu_administration() {
if (isset($_POST['wpbu_submit']) and wp_verify_nonce($_POST['form_nonce'], 'test-nonce')) {
$_POST['wpbu_msie'] = sanitize_text_field($_POST['wpbu_msie']);
$_POST['wpbu_firefox'] = sanitize_text_field($_POST['wpbu_firefox']);
$_POST['wpbu_opera'] = sanitize_text_field($_POST['wpbu_opera']);
$_POST['wpbu_safari'] = sanitize_text_field($_POST['wpbu_safari']);
$_POST['wpbu_google'] = sanitize_text_field($_POST['wpbu_google']);

$_POST['wpbu_reminder'] = sanitize_text_field($_POST['wpbu_reminder']);
$_POST['wpbu_testing'] = sanitize_text_field($_POST['wpbu_testing']);
$_POST['wpbu_newwindow'] = sanitize_text_field($_POST['wpbu_newwindow']);
$_POST['wpbu_style'] = sanitize_text_field($_POST['wpbu_style']);
$_POST['wpbu_secis'] = sanitize_text_field($_POST['wpbu_secis']);
$_POST['wpbu_unsup'] = sanitize_text_field($_POST['wpbu_unsup']);
$_POST['wpbu_mobile'] = sanitize_text_field($_POST['wpbu_mobile']);
$_POST['wpbu_shift'] = sanitize_text_field($_POST['wpbu_shift']);

$_POST['wpbu_css_buorg'] = sanitize_textarea_field($_POST['wpbu_css_buorg']);

update_option('wp_browserupdate_browsers', $_POST['wpbu_msie'].' '.$_POST['wpbu_firefox'].' '.$_POST['wpbu_opera'].' '.$_POST['wpbu_safari'].' '.$_POST['wpbu_google']);
update_option('wp_browserupdate_js', (int)$_POST['wpbu_reminder'].' '.$_POST['wpbu_testing'].' '.$_POST['wpbu_newwindow'].' '.$_POST['wpbu_style'].' '.(empty($_POST['wpbu_secis']) ? 'false' : $_POST['wpbu_secis']).' '.(empty($_POST['wpbu_unsup']) ? 'false' : $_POST['wpbu_unsup']).' '.(empty($_POST['wpbu_mobile']) ? 'false' : $_POST['wpbu_mobile']).' '.(empty($_POST['wpbu_shift']) ? 'false' : $_POST['wpbu_shift']));
update_option('wp_browserupdate_css_buorg', $_POST['wpbu_css_buorg']);
echo '<div class="updated"><p><strong>'.esc_html__('Settings saved.', 'wp-browser-update').'</strong></p></div>';
unset($_POST['form_nonce']);
unset($_POST['wpbu_submit']);
}

$morethan = [
['0', __('Every outdated version', 'wp-browser-update')],
['-5', __('More than five versions behind', 'wp-browser-update')],
['-4', __('More than four versions behind', 'wp-browser-update')],
['-3', __('More than three versions behind', 'wp-browser-update')],
['-2', __('More than two versions behind', 'wp-browser-update')],
['-1', __('More than one version behind', 'wp-browser-update')]
];

$wpbu_vars = explode(' ', get_option('wp_browserupdate_browsers', '0 0 0 0 0'));
$msie = $wpbu_vars[0];
$firefox = $wpbu_vars[1];
$opera = $wpbu_vars[2];
$safari = $wpbu_vars[3];
$google = empty($wpbu_vars[4]) ? '' : $wpbu_vars[4];

$wpbu_js = explode(' ', get_option('wp_browserupdate_js', '12 false true top true true true true'));
$wpbu_reminder = $wpbu_js[0];
$wpbu_testing = $wpbu_js[1];
$wpbu_newwindow = $wpbu_js[2];
$wpbu_style = $wpbu_js[3];
$wpbu_secis = $wpbu_js[4];
$wpbu_unsup = $wpbu_js[5];
$wpbu_mobile = $wpbu_js[6];
$wpbu_shift = $wpbu_js[7];

$wpbu_css_buorg = get_option('wp_browserupdate_css_buorg', '');

$msie_vers = array_merge($morethan, [[135, '<=135'], [120, '<=120'], [110, '<=110'], [100, '<=100'], [90, '<=90']]);
$firefox_vers = array_merge($morethan, [[137, '<=137'], [120, '<=120'], [100, '<=100'], [80, '<=80'], [60, '<=60']]);
$opera_vers = array_merge($morethan, [[117, '<=117'], [85, '<=85'], [75, '<=75'], [65, '<=65'], [55, '<=55']]);
$safari_vers = array_merge($morethan, [[18, '<=18'], [17, '<=17'], [16, '<=16'], [15, '<=15'], [14, '<=14']]);
$google_vers = array_merge($morethan, [[135, '<=135'], [120, '<=120'], [100, '<=100'], [80, '<=80'], [60, '<=60']]);

echo '<div class="wrap"><form action="'.$_SERVER['REQUEST_URI'].'" method="post"><input name="form_nonce" type="hidden" value="'.wp_create_nonce('test-nonce').'" /><h1>WP BrowserUpdate</h1><h2>'.esc_html__('Outdated Browser Versions', 'wp-browser-update').'</h2><p>'.esc_html__('Select the browser versions you consider outdated (including all earlier versions). If left unchanged, WP BrowserUpdate will use the default settings.', 'wp-browser-update').'</p><p>Microsoft Edge: <select name="wpbu_msie">';

for ($x=0; $x<count($msie_vers); $x++) echo '<option value="'.$msie_vers[$x][0].'"'.($msie==$msie_vers[$x][0] ? ' selected="selected"' : '').'>'.$msie_vers[$x][1].'</option>';

echo '</select> <a href="https://microsoft.com/edge" title="'.esc_html__('Download', 'wp-browser-update').'" target="_blank">'.esc_html__('Download', 'wp-browser-update').'</a></p><p>Mozilla Firefox: <select name="wpbu_firefox">';

for ($x=0; $x<count($firefox_vers); $x++) echo '<option value="'.$firefox_vers[$x][0].'"'.($firefox==$firefox_vers[$x][0] ? ' selected="selected"' : '').'>'.$firefox_vers[$x][1].'</option>';

echo '</select> <a href="https://mozilla.org/firefox" title="'.esc_html__('Download', 'wp-browser-update').'" target="_blank">'.esc_html__('Download', 'wp-browser-update').'</a></p><p>Opera: <select name="wpbu_opera">';

for ($x=0; $x<count($opera_vers); $x++) echo '<option value="'.$opera_vers[$x][0].'"'.($opera==$opera_vers[$x][0] ? ' selected="selected"' : '').'>'.$opera_vers[$x][1].'</option>';

echo '</select> <a href="https://opera.com/" title="'.esc_html__('Download', 'wp-browser-update').'" target="_blank">'.esc_html__('Download', 'wp-browser-update').'</a></p><p>Apple Safari: <select name="wpbu_safari">';

for ($x=0; $x<count($safari_vers); $x++) echo '<option value="'.$safari_vers[$x][0].'"'.($safari==$safari_vers[$x][0] ? ' selected="selected"' : '').'>'.$safari_vers[$x][1].'</option>';

echo '</select> <a href="https://support.apple.com/102665" title="'.esc_html__('Download', 'wp-browser-update').'" target="_blank">'.esc_html__('Download', 'wp-browser-update').'</a></p><p>Google Chrome: <select name="wpbu_google">';

for ($x=0; $x<count($google_vers); $x++) echo '<option value="'.$google_vers[$x][0].'"'.($google==$google_vers[$x][0] ? ' selected="selected"' : '').'>'.$google_vers[$x][1].'</option>';

echo '</select> <a href="https://chrome.google.com/" title="'.esc_html__('Download', 'wp-browser-update').'" target="_blank">'.esc_html__('Download', 'wp-browser-update').'</a></p><h3>'.esc_html__('Script Customizations', 'wp-browser-update').'</h3><p>'.esc_html__('How many hours before the message should reappear (0 = Always show)?', 'wp-browser-update').'<br /><input type="number" value="'.$wpbu_reminder.'" name="wpbu_reminder" min="0" max="99" step="1" required placeholder="(min: 0, max: 99)" /></p><p>'.esc_html__('Open the notification bar link in a new browser tab or window', 'wp-browser-update').':<br /><select name="wpbu_newwindow"><option value="true"'.($wpbu_newwindow=='true' ? ' selected="selected"' : '').'>'.esc_html__('Yes', 'wp-browser-update').'</option><option value="false"'.($wpbu_newwindow=='false' ? ' selected="selected"' : '').'>'.esc_html__('No', 'wp-browser-update').'</option></select></p><p>'.esc_html__('Always display the notification bar (useful for testing)', 'wp-browser-update').':<br /><select name="wpbu_testing"><option value="true"'.($wpbu_testing=='true' ? ' selected="selected"' : '').'>'.esc_html__('Yes', 'wp-browser-update').'</option><option value="false"'.($wpbu_testing=='false' ? ' selected="selected"' : '').'>'.esc_html__('No', 'wp-browser-update').'</option></select></p><p>'.esc_html__('Select where the notification bar should appear on the page', 'wp-browser-update').':<br /><select name="wpbu_style"><option value="top"'.($wpbu_style=='top' ? ' selected="selected"' : '').'>'.esc_html__('Top', 'wp-browser-update').'</option><option value="bottom"'.($wpbu_style=='bottom' ? ' selected="selected"' : '').'>'.esc_html__('Bottom', 'wp-browser-update').'</option><option value="corner"'.($wpbu_style=='corner' ? ' selected="selected"' : '').'>'.esc_html__('Corner', 'wp-browser-update').'</option></select></p><p>'.esc_html__('Alert users of all browser versions with serious security vulnerabilities', 'wp-browser-update').':<br /><select name="wpbu_secis"><option value="true"'.($wpbu_secis=='true' ? ' selected="selected"' : '').'>'.esc_html__('Yes', 'wp-browser-update').'</option><option value="false"'.($wpbu_secis=='false' ? ' selected="selected"' : '').'>'.esc_html__('No', 'wp-browser-update').'</option></select></p><p>'.esc_html__('Include browsers that are no longer supported by their vendor', 'wp-browser-update').':<br /><select name="wpbu_unsup"><option value="true"'.($wpbu_unsup=='true' ? ' selected="selected"' : '').'>'.esc_html__('Yes', 'wp-browser-update').'</option><option value="false"'.($wpbu_unsup=='false' ? ' selected="selected"' : '').'>'.esc_html__('No', 'wp-browser-update').'</option></select></p><p>'.esc_html__('Enable notifications for mobile browsers', 'wp-browser-update').':<br /><select name="wpbu_mobile"><option value="true"'.($wpbu_mobile=='true' ? ' selected="selected"' : '').'>'.esc_html__('Yes', 'wp-browser-update').'</option><option value="false"'.($wpbu_mobile=='false' ? ' selected="selected"' : '').'>'.esc_html__('No', 'wp-browser-update').'</option></select></p><p>'.esc_html__('Adjust the page layout to avoid content being obscured by the notification bar (adds margin-top to the body tag)', 'wp-browser-update').':<br /><select name="wpbu_shift"><option value="true"'.($wpbu_shift=='true' ? ' selected="selected"' : '').'>'.esc_html__('Yes', 'wp-browser-update').'</option><option value="false"'.($wpbu_shift=='false' ? ' selected="selected"' : '').'>'.esc_html__('No', 'wp-browser-update').'</option></select></p><h3>'.esc_html__('Custom CSS', 'wp-browser-update').'</h3><p>'.sprintf(__('Override the default CSS with your own rules (%sread more%s) – leave blank to use the default', 'wp-browser-update'), '<a href="https://browser-update.org/customize.html" target="_blank">', "</a>").':</p><p><textarea name="wpbu_css_buorg" rows="15" cols="50" class="large-text code">'.$wpbu_css_buorg.'</textarea></p><p class="submit"><input type="submit" name="wpbu_submit" id="submit" class="button button-primary" value="'.esc_html__('Update Settings', 'wp-browser-update').'" /></p></form></div>';
}

function wpbu_css() {
$wpbu_css_buorg = get_option('wp_browserupdate_css_buorg', '');
if (!empty($wpbu_css_buorg)) echo "<style type=\"text/css\">".$wpbu_css_buorg."\r\n</style>";
}

function wpbu_admin() {
add_options_page('WP BrowserUpdate', 'WP BrowserUpdate', 'manage_options', 'wp-browserupdate', 'wpbu_administration');
}

function wpbu_settings_link($links) {
return array_merge(array('settings' => '<a href="'.admin_url('options-general.php?page=wp-browserupdate').'">'.esc_html__('Settings').'</a>'), $links);
}

function wpbu_activation() {
}

function wpbu_plugin_links($links, $file) {
if ($file===plugin_basename(__FILE__)) $links[] = '<a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/support/plugin/wp-browser-update" title="'.esc_html__('Get help', 'wp-browser-update').'">'.esc_html__('Support', 'wp-browser-update').'</a> | <a target="_blank" href="https://wpbu.steinbrecher.co/" title="'.esc_html__('Plugin Homepage', 'wp-browser-update').'">'.esc_html__('Plugin Homepage', 'wp-browser-update').'</a> | <a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/support/plugin/wp-browser-update/reviews/#new-post" title="'.esc_attr__('Rate this plugin. Thanks for your support!', 'wp-browser-update').'">'.esc_html__('Rate this plugin', 'wp-browser-update').'</a>';
return $links;
}

register_activation_hook(__FILE__, 'wpbu_activation');
add_filter('plugin_action_links_'.basename(dirname(__FILE__)).'/'.basename(__FILE__), 'wpbu_settings_link');
add_filter('plugin_row_meta', 'wpbu_plugin_links', 10, 2);
add_action('wp_footer', 'wpbu');
add_action('wp_head', 'wpbu_css');
add_action('admin_menu', 'wpbu_admin');
