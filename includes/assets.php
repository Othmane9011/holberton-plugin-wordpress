<?php

// Function to enqueue the user-panel.css file
function enqueue_custom_styles() {
    wp_enqueue_style('user-panel', plugin_dir_url(__FILE__) . 'css/user-panel.css', array(), '1.0');
    
}

add_action('admin_enqueue_scripts', 'enqueue_custom_styles');


add_action('wp_ajax_remove_holberton_widget', 'remove_holberton_widget');



// Function to render the widget preview


function enqueue_advanced_assets() {
    // Enqueue JavaScript files
    wp_enqueue_script('jquery-ui-sortable'); // Example: jQuery UI for widget ordering
    wp_enqueue_script('custom-scripts', plugin_dir_url(__FILE__) . 'js/custom-scripts.js', array('jquery'), '1.0', true);

    // Enqueue CSS files
    wp_enqueue_style('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'); // Example: Bootstrap for advanced styling
    wp_enqueue_style('custom-styles', plugin_dir_url(__FILE__) . 'css/custom-styles.css', array(), '1.0');
}

add_action('wp_enqueue_scripts', 'enqueue_advanced_assets');

// Add a menu item for the admin page
function holberton_plugin_menu() {
    add_menu_page('Holberton Plugin', 'Holberton Plugin', 'manage_options', 'holberton-plugin', 'holberton_plugin_page');
}

add_action('admin_menu', 'holberton_plugin_menu');