<?php

if (!defined('WP_UNINSTALL_PLUGIN')) exit();

foreach (array('wp_browserupdate_browsers', 'wp_browserupdate_js', 'wp_browserupdate_css_buorg', 'wp_browserupdate_css_buorgdiv', 'wp_browserupdate_css_buorga', 'wp_browserupdate_css_buorgig') as $option) delete_option($option);

?>