<?php

// Shortcode function to display a selected widget
function holberton_widget_shortcode($atts) {
    $atts = shortcode_atts(array(
        'widget_id' => 0, // Default to the first widget
    ), $atts);

    $widget_id = intval($atts['widget_id']);
    $widgets = get_option('holberton_widgets', array());

    if (isset($widgets[$widget_id])) {
        $widget = $widgets[$widget_id];
        $widget_title = esc_attr($widget['title']);
        $widget_styles = esc_attr($widget['styles']); // Include custom CSS

        // Generate the HTML output, including custom CSS
        $output = '<div class="holberton-widget" style="' . $widget_styles . '">';
        $output .= '<h2>' . esc_html($widget_title) . '</h2>';
        $output .= wp_kses_post($widget['content']);

        $output .= '</div>';

        return $output;
    }

    return ''; // Return an empty string if the specified widget ID is not found
}

// Register the shortcode with widget selection
add_shortcode('holberton-widget', 'holberton_widget_shortcode');
