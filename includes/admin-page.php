<?php

function holberton_plugin_page() {
    // Check if form data is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            save_widget_data();
        }
    // Check if logo upload form is submitted
    if (isset($_POST['upload_logo'])) {
        handle_logo_upload();
        $uploaded_logo = get_option('holberton_uploaded_logo');
        
    }

    // Retrieve widgets data from the database
    $widgets = get_option('holberton_widgets', array());

    // Output the HTML markup
    ?>

    <script>
    // JavaScript code to handle logo file selection and preview
    document.addEventListener('DOMContentLoaded', function() {
        const logoInput = document.getElementById('logo');
        const logoPreview = document.getElementById('logo-preview');

        logoInput.addEventListener('change', function() {
            if (logoInput.files.length > 0) {
                const logoFile = logoInput.files[0];
                const logoURL = URL.createObjectURL(logoFile);
                logoPreview.src = logoURL;
                logoPreview.style.display = 'block'; // Show the logo preview
            } else {
                logoPreview.style.display = 'none'; // Hide the logo preview
            }
        });
    });
    


    // JavaScript code to toggle widget options
    document.addEventListener('DOMContentLoaded', function() {
        const widgetOptions = document.getElementById('widget-options');
        const readMoreButton = document.getElementById('read-more-button');

        readMoreButton.addEventListener('click', function() {
            widgetOptions.classList.toggle('hidden');
        });
    });

    
    </script>

<div class="wrap">
    <h2>Holberton Plugin</h2>

    <div id="logo-options">
        <div class="logo-container">
            <div class="holberton-form-field">
                <label for="logo">Logo:</label>
                <input type="file" id="logo" name="logo">
            </div>
            <!-- Logo Preview/Shortcode -->
            <img id="logo-preview" style="display: none;">
            <p>Copy the shortcode to display your logo:</p>
            <input type="text" id="logo-shortcode" value="[holberton-logo]" readonly>
        </div>
    </div>

        <button id="read-more-button" class="button button-primary">Widget option</button>

        <div id="widget-options" class="hidden">
            <div class="holberton-form-field">
                <label for="widget_title">Widget Title:</label>
                <input type="text" id="widget_title" name="widget_title">
            </div>
            <div class="holberton-form-field">
                <label for="widget_content">Widget Content:</label>
                <textarea id="widget_content" name="widget_content" rows="5" cols="40"></textarea>
            </div>
            <div class="holberton-form-field">
                <label for="widget_custom_css">Custom Styles (CSS):</label>
                <textarea id="widget_custom_css" name="widget_custom_css" rows="5" cols="40"></textarea>
            </div>
            <input type="submit" class="button button-primary" value="Add Widget">
        </div>

            <div class="holberton-form-field">
            <h2>Your Widgets</h2>
            <div class="widget-container">
                <?php foreach ($widgets as $widget_id => $widget) : ?>
                    <?php display_widget($widget, $widget_id); ?>
                <?php endforeach; ?>
            </div>
        </div>
            <!-- Add more options here -->
    </div>
    
    <?php
}

// Function to generate a unique CSS file for user's custom CSS
function generate_user_css_file() {
    $upload_dir = wp_upload_dir();
    $css_file = trailingslashit($upload_dir['basedir']) . 'css/custom-user.css';

    return $css_file;
}

// Function to save widget data to the database
function save_widget_data() {
    $widget_title = sanitize_text_field($_POST['widget_title']);
    $widget_content = wp_kses_post($_POST['widget_content']);
    $widget_custom_css = sanitize_text_field($_POST['widget_custom_css']);

    $widgets = get_option('holberton_widgets', array());

    // Assign a new widget ID based on the position in the array
    $new_widget_id = count($widgets);

    $new_widget = array(
        'title' => $widget_title,
        'content' => $widget_content,
        'css_filename' => "custom-css-widget-{$new_widget_id}.css", // Generate a unique CSS filename
    );

    $widgets[$new_widget_id] = $new_widget; // Use the new_widget_id as the key
    update_option('holberton_widgets', $widgets);

    // Save the custom CSS to the widget's specific CSS file
    save_user_css($widget_custom_css, $new_widget['css_filename']);

    echo '<div class="updated"><p>Widget data and styles saved successfully!</p></div>';
}

// Function to save user's custom CSS to a file
function save_user_css($custom_css, $css_filename) {
    // Define the CSS directory path
    $css_directory = plugin_dir_path(__FILE__) . 'includes/css/' . $css_filename;

    // Ensure the CSS directory exists
    if (!file_exists($css_directory)) {
        mkdir($css_directory, 0755, true);
    }

    // Define the full path to the CSS file
    $css_file_path = plugin_dir_path(__FILE__) . $css_filename;

    // Write the custom CSS to the CSS file
    file_put_contents($css_file_path, $custom_css);
}

// Function to format custom CSS with each rule on a separate line
function format_custom_css($custom_css) {
    // Split the CSS rules by the closing curly brace followed by a newline
    $rules = preg_split('/\}\s*/', $custom_css, -1, PREG_SPLIT_NO_EMPTY);

    // Reformat the CSS rules
    $formatted_rules = array_map(function ($rule) {
        // Trim whitespace
        $rule = trim($rule);
        // Add a newline and closing curly brace at the end of each rule
        return "$rule\n}";
    }, $rules);

    // Join the formatted rules with a newline
    return implode("\n", $formatted_rules);
}

// Function to display widgets in the correct order
function display_widgets() {
    $widgets = get_option('holberton_widgets', array());

    // Sort widgets by their position
    usort($widgets, function($a, $b) {
        return $a['position'] - $b['position'];
    });

    foreach ($widgets as $widget_id => $widget) {
        display_widget($widget, $widget_id);
    }
}

// Function to display a widget

function display_widget($widget, $widget_id) {
    $widget_title = esc_html($widget['title']);
    $widget_shortcode = '[holberton-widget widget_id=' . $widget_id . ']';
    $widget_content = $widget['content'];
    $css_filename = $widget['css_filename'];
    $css_handle = 'custom-css-' . $widget_id; // Ensure a unique CSS handle for each widget

    wp_enqueue_style($css_handle, plugin_dir_url(__FILE__) . 'includes/css/' . $css_filename); // Enqueue the CSS file

    // Load the custom CSS for the widget with the unique CSS handle
    $custom_css = file_get_contents(plugin_dir_path(__FILE__) . 'includes/css/' . $css_filename);
    wp_add_inline_style($css_handle, $custom_css);

    ?>
    <div class="widget-box">
        <div class="widget-name"><?php echo $widget_title; ?></div>
        <?php
        // Load the custom CSS for the widget with the unique CSS handle
        wp_add_inline_style($css_handle, ''); // Add an empty inline style for customization
        ?>
        <div class="widget-preview">
            <?php echo wp_kses_post($widget_content); ?>
        </div>
        <div class="widget-id"><?php echo $widget_shortcode; ?></div>
        <button class="copy-shortcode">Copy Shortcode</button>
        <!-- Remove Widget Form (visible to users with manage_options capability) -->
        <?php if (current_user_can('manage_options')) : ?>
            <form method="post" class="remove-widget-form">
                <input type="hidden" name="remove_widget" value="<?php echo $widget_id; ?>">
                <button type="submit" class="remove-widget-button">X</button>
            </form>
        <?php endif; ?>
    </div>
    <?php
}


?>
