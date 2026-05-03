<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

foreach (array('wp_browserupdate_options', 'wp_browserupdate_browsers', 'wp_browserupdate_js', 'wp_browserupdate_css_buorg', 'wp_browserupdate_css_buorgdiv', 'wp_browserupdate_css_buorga', 'wp_browserupdate_css_buorgig') as $option) {
	delete_option($option);
}

global $wpdb;

$wpdb->query(
	$wpdb->prepare(
		"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
		$wpdb->esc_like('_transient_wpbu_') . '%',
		$wpdb->esc_like('_transient_timeout_wpbu_') . '%'
	)
);
