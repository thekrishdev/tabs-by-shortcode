<?php
/**
 * Plugin Name: Tabs By Shortcodes
 * Description: Plugin adds shortcodes to place a page content in tabs. Uses a lightweight jQuery script, no additional CSS files.
 * Requires at least: 4.6
 * Requires PHP: 7.0
 * Author: Krish Dev
 * Author URI: https://krish.dev/
 */

// Enqueue plugin script
function sts_enqueue_scripts() {
	global $post;
	if(is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'tabs')) {
		$min = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
		wp_enqueue_script('simple-tabs-shortcodes', plugin_dir_url(__FILE__).'js.tabs'.$min.'.js', array(), '1.3', true);
	}
}
add_action('wp_enqueue_scripts', 'sts_enqueue_scripts');

// Tabs wrapper shortcode
function sts_tabs_data_shortcode($atts, $content) {
	// Create empty tabs data array
	global $sts_tabs_data;
	$sts_tabs_data = array();
	// Get tabs content
	$tabs_content = do_shortcode($content);
	// Start the tabs navigation
	$out = '<div class="tabs-container"><div class="tabs-nav"><ul>';
	// Loop through the tabs data
	foreach($sts_tabs_data as $tabs => $tab) {
		$active = $tabs == 0 ? ' class="active"' : '';
		$out .= '<li><a href="#'.$tab['id'].'"'.$active.'>'.$tab['title'].'</a></li>';
	}
	// Close the tabs navigation and add tabs content
	$out .= '</ul></div><div class="tabs-content">'.$tabs_content.'</div></div>';
	return $out;
}
add_shortcode('tabs', 'sts_tabs_data_shortcode');

// Tab item shortcode
function sts_tab_shortcode($atts, $content) {
	// Default attributes value
	$atts = shortcode_atts(
		array(
			'id' => '',
			'title' => __('Undefined title', 'simple-tabs-shortcodes')
		), $atts, 'tab');
	// Get tab ID
	$id = $atts['id'] ?: rawurldecode(sanitize_title($atts['title']));
	// Add tabs data to array
	global $sts_tabs_data;
	array_push($sts_tabs_data, array('id' => $id, 'title' => $atts['title']));
	// Make tab section
	$active = count($sts_tabs_data) == 1 ? ' active' : '';
	$out = '<section id="'.$id.'" class="tab'.$active.'">
		'.do_shortcode($content).'
	</section>';
	return $out;
}
add_shortcode('tab', 'sts_tab_shortcode');

// Activation hook
function sts_activated() {
	// Add notice option
	add_option('sts_activation_notice', true, '', 'no');
}
register_activation_hook(plugin_basename(__FILE__), 'sts_activated');

// Activation notice
function sts_admin_notices() {
	// Check for option before displaying notice
	if(get_option('sts_activation_notice')) {
		// Display notice
		echo '<div class="notice notice-info"><p>'.sprintf(__('<strong>Important</strong>: Make sure to <a href="%s">add own CSS style</a> to your theme’s stylesheet to ensure proper display of the tabs.', 'simple-tabs-shortcodes'), 'https://wordpress.org/plugins/simple-tabs-shortcodes/#faq').'</p></div>';
		// Delete option after notice appears
		delete_option('sts_activation_notice');
	}
}
add_action('admin_notices', 'sts_admin_notices');
