<?php
/*
Plugin Name: Holberton Plugin
Description: Create and customize widgets for your WordPress site, including custom CSS.
Version: 1.0
Author: Your Name
*/

// Widget rendering function
function render_holberton_widgets() {
    $widgets = get_option('holberton_widgets', array());

    foreach ($widgets as $widget) {
        $widget_title = $widget['title'];
        $widget_content = $widget['content'];
        $widget_styles = $widget['styles'];

        if (!empty($widget_title) && !empty($widget_content)) {
            echo '<div class="holberton-widget" style="' . esc_attr($widget_styles) . '">';
            echo '<h2>' . esc_html($widget_title) . '</h2>';
            echo wp_kses_post($widget_content);
            echo '</div>';
        }
    }
}

// Hook the widget rendering function to the appropriate action or filter
add_action('wp_footer', 'render_holberton_widgets'); // Example: Render in the footer

// Admin page for widget creation and customization
function holberton_plugin_page() {
    if (isset($_POST['widget_title']) && isset($_POST['widget_content'])) {
        $widget_title = sanitize_text_field($_POST['widget_title']);
        $widget_content = wp_kses_post($_POST['widget_content']);
        $widget_styles = sanitize_text_field($_POST['widget_styles']);

        $widgets = get_option('holberton_widgets', array());

        $new_widget = array(
            'title' => $widget_title,
            'content' => $widget_content,
            'styles' => $widget_styles,
        );

        $widgets[] = $new_widget;
        update_option('holberton_widgets', $widgets);

        echo '<div class="updated"><p>Widget data and styles saved successfully!</p></div>';
    }

    $widgets = get_option('holberton_widgets', array());
    ?>

    <div class="wrap">
        <h2>Holberton Plugin</h2>
        <form method="post">
            <div class="holberton-form-field">
                <label for="widget_title">Widget Title:</label>
                <input type="text" id="widget_title" name="widget_title">
            </div>
            <div class="holberton-form-field">
                <label for="widget_content">Widget Content:</label>
                <textarea id="widget_content" name="widget_content" rows="5" cols="40"></textarea>
            </div>
            <div class="holberton-form-field">
                <label for="widget_styles">Custom Styles (CSS):</label>
                <textarea id="widget_styles" name="widget_styles" rows="5" cols="40"></textarea>
            </div>
            <p><input type="submit" class="button button-primary" value="Save Widget"></p>
        </form>

        

        <h2>Your Widgets</h2>
        <div class="widget-container">
    <?php
    foreach ($widgets as $widget_id => $widget) {
        $widget_title = esc_html($widget['title']);
        $widget_shortcode = '[holberton-widget widget_id=' . $widget_id . ']';
        $widget_styles = $widget['styles'];
        $widget_content = $widget['content'];
        ?>
        <div class="widget-box">
    <div class="widget-name"><?php echo $widget_title; ?></div>
    <div class="widget-preview" style="<?php echo esc_attr($widget_styles); ?>">
        <?php echo wp_kses_post($widget_content); ?>
    </div>
    <div class="widget-id"><?php echo $widget_shortcode; ?></div>
    <button class="copy-shortcode">Copy Shortcode</button>
    <button class="copy-widget-id">Copy Widget ID</button>
    <div class="widget-box">
        <!-- Widget content and other elements -->
        <form method="post" class="remove-widget-form">
            <input type="hidden" name="remove_widget" value="<?php echo $widget_id; ?>">
            <button type="submit" class="remove-widget-button">Remove</button>
        </form>
    </div>
</div>
        <?php
    }
    ?>
    </div>
    <?php
}

// Shortcode function to display a selected widget
function holberton_widget_shortcode($atts) {
    $atts = shortcode_atts(array(
        'widget_id' => 0, // Default to the first widget
    ), $atts);

    $widget_id = intval($atts['widget_id']);
    $widgets = get_option('holberton_widgets', array());

    if (isset($_POST['remove_widget'])) {
        $widget_id_to_remove = intval($_POST['remove_widget']);
        $widgets = get_option('holberton_widgets', array());

        if (isset($widgets[$widget_id_to_remove])) {
            unset($widgets[$widget_id_to_remove]);
            update_option('holberton_widgets', $widgets);
        }
    }

    if (isset($widgets[$widget_id])) {
        $widget = $widgets[$widget_id];
        $widget_title = esc_attr($widget['title']);
        $widget_styles = esc_attr($widget['styles']);

        // Generate the HTML output
        $output = '<div class="holberton-widget" style="' . $widget_styles . '">';
        $output .= '<h2>' . esc_html($widget_title) . '</h2>';
        $output .= wp_kses_post($widget['content']);

        // Add a "Remove" button inside the widget
        $output .= '<form method="post" class="remove-widget-form">';
        $output .= '<input type="hidden" name="remove_widget" value="' . $widget_id . '">';
        $output .= '<button type="submit" class="remove-widget-button">Remove</button>';
        $output .= '</form>';

        $output .= '</div>';

        return $output;
    }

    return ''; // Return an empty string if the specified widget ID is not found
}

// Register the shortcode with widget selection
add_shortcode('holberton-widget', 'holberton_widget_shortcode');


// Enqueue CSS and JavaScript assets for the refined user interface

// Function to enqueue the user-panel.css file
function enqueue_custom_styles() {
    wp_enqueue_style('user-panel', plugin_dir_url(__FILE__) . 'css/user-panel.css', array(), '1.0');
}

add_action('admin_enqueue_scripts', 'enqueue_custom_styles');

// Function to render the widget preview
function render_widget_preview($widget) {
    $widget_content = isset($widget['content']) ? $widget['content'] : '';
    $widget_styles = isset($widget['styles']) ? $widget['styles'] : '';

    echo '<div class="widget-preview" style="' . esc_attr($widget_styles) . '">';
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