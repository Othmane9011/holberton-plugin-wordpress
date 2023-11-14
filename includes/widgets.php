<?php
function render_holberton_widgets() {
    $widgets = get_option('holberton_widgets', array());

    foreach ($widgets as $widget) {
        $widget_title = $widget['title'];
        $widget_content = $widget['content'];
        $widget_styles = $widget['styles']; // Include custom CSS

        if (!empty($widget_title) && !empty($widget_content)) {
            echo '<div class="holberton-widget" style="' . esc_attr($widget_styles) . '">';
            echo '<h2>' . esc_html($widget_title) . '</h2>';
            echo wp_kses_post($widget_content);
            echo '</div>';
        }
    }
}

function holberton_widgets_shortcode() {
    $widgets = get_option('holberton_widgets', array());
    $output = '';

    foreach ($widgets as $widget_id => $widget) {
        // Enqueue the CSS for each widget
        $css_filename = $widget['css_filename'];
        $css_handle = 'custom-css-' . $widget_id;
        wp_enqueue_style($css_handle, plugin_dir_url(__FILE__) . 'css/' . $css_filename);

        // Display the widget content
        $output .= display_widget($widget, $widget_id);
    }

    return $output;
}


// Register the shortcode
add_shortcode('holberton-widgets', 'holberton_widgets_shortcode');

// Hook the widget rendering function to the appropriate action or filter
//add_action('wp_footer', 'render_holberton_widgets'); // Example: Render in the footer
