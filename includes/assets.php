<?php

// Function to enqueue the user-panel.css file
function enqueue_custom_styles() {
    wp_enqueue_style('user-panel', plugin_dir_url(__FILE__) . 'css/user-panel.css', array(), '1.0');
    
}

add_action('admin_enqueue_scripts', 'enqueue_custom_styles');

function remove_holberton_widget() {
    if (isset($_POST['widget_id'])) {
        $widget_id = intval($_POST['widget_id']);
        $widgets = get_option('holberton_widgets', array());

        // Check if the widget ID exists
        if (isset($widgets[$widget_id])) {
            // Remove the widget from the database
            unset($widgets[$widget_id]);
            update_option('holberton_widgets', $widgets);

            // Delete the associated user's custom CSS file
            delete_user_css($widget_id);

            // Send a success response
            echo 'success';
        }
    }

    wp_die(); // Required to terminate and return a proper response
}
function delete_user_css($widget_id) {
    $widgets = get_option('holberton_widgets', array());
    if (isset($widgets[$widget_id]) && isset($widgets[$widget_id]['css_filename'])) {
        $css_filename = $widgets[$widget_id]['css_filename'];

        $css_file_path = plugin_dir_path(__FILE__) . $css_filename;

        // Check if the CSS file exists before attempting to delete
        if (file_exists($css_file_path)) {
            unlink($css_file_path);
            echo 'CSS file deleted: ' . $css_file_path;
        } else {
            echo 'CSS file does not exist: ' . $css_file_path;
        }
    }
}




add_action('wp_ajax_remove_holberton_widget', 'remove_holberton_widget');



// Function to render the widget preview
function render_widget_preview($widget, $widget_id) {
    $widget_content = isset($widget['content']) ? $widget['content'] : '';

    // Create the CSS filename based on the widget's ID
    $css_filename = "custom-css-widget-{$widget_id}.css";

    // Point to the "css" directory where your custom CSS files are located
    $css_file_path = plugin_dir_path(__FILE__) . 'css/' . $css_filename;

    // Check if the CSS file exists
    if (file_exists($css_file_path)) {
        $custom_css = file_get_contents($css_file_path);

        echo '<style>' . $custom_css . '</style>';
    }

    echo '<div class="widget-preview">';
    echo wp_kses_post($widget_content);
    echo '</div>';
}


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