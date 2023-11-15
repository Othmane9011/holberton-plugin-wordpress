<?php
/*
Plugin Name: Holberton Plugin
Description: Create and customize widgets for your WordPress site, including custom CSS.
Version: 1.0
Author: Your Name
*/

// Enqueue CSS and JavaScript assets
function enqueue_custom_assets() {
    // Enqueue CSS files
    wp_enqueue_style('user-panel', plugin_dir_url(__FILE__) . 'includes/css/user-panel.css', array(), '1.0');
    wp_enqueue_style('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'); // Example: Bootstrap for advanced styling
    wp_enqueue_style('custom-styles', plugin_dir_url(__FILE__) . 'includes/css/custom-styles.css', array(), '1.0');

    // Enqueue JavaScript files
    wp_enqueue_script('jquery-ui-sortable'); // Example: jQuery UI for widget ordering
    wp_enqueue_script('custom-scripts', plugin_dir_url(__FILE__) . 'includes/js/custom-scripts.js', array('jquery'), '1.0', true);
    wp_enqueue_script('clipboard-js', 'https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js', array('jquery'), '2.0.8', true);
    wp_enqueue_script('image-upload-script', plugin_dir_url(__FILE__) . 'includes/js/image-upload.js', array('jquery'), null, true);
}
function enqueue_contact_form_widget_styles() {
    wp_enqueue_style('contact-form-widget-styles', plugin_dir_url(__FILE__) . 'includes/css/contact-form-widget.css');
}

add_action('wp_enqueue_scripts', 'enqueue_contact_form_widget_styles');
add_action('admin_enqueue_scripts', 'enqueue_custom_assets');
add_action('wp_enqueue_scripts', 'enqueue_custom_assets');

require_once(plugin_dir_path(__FILE__) . 'includes/widgets.php');
require_once(plugin_dir_path(__FILE__) . 'includes/admin-page.php');
require_once(plugin_dir_path(__FILE__) . 'includes/shortcodes.php');
require_once(plugin_dir_path(__FILE__) . 'includes/assets.php');
require_once(plugin_dir_path(__FILE__) . 'includes/logo-feature.php');
// require_once(plugin_dir_path(__FILE__) . 'includes/image_slider.php');
// require_once(plugin_dir_path(__FILE__) . 'includes/widget-functions.php');
// require_once(plugin_dir_path(__FILE__) . 'includes/image-upload-widget.php');
require_once(plugin_dir_path(__FILE__) . 'includes/social_media.php');