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
    if (isset($_POST['add_contact_widget'])) {
        add_contact_widget();
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact_form'])) {
        $admin_email = get_option('admin_email'); // Get the admin email address
    
        // Retrieve form data
        $contact_name = sanitize_text_field($_POST['contact_name']);
        $contact_email = sanitize_email($_POST['contact_email']);
        $contact_message = sanitize_textarea_field($_POST['contact_message']);
    
        // Email subject
        $subject = 'New Contact Form Submission';
    
        // Email message body
        $message = "Name: $contact_name\n";
        $message .= "Email: $contact_email\n";
        $message .= "Message: $contact_message\n";
    
        // Email headers
        $headers = array('Content-Type: text/html; charset=UTF-8');
    
        // Send email
        $sent = wp_mail($admin_email, $subject, $message, $headers);
    
        if ($sent) {
            // Email sent successfully
            echo '<div class="success-message">Thank you! Your message has been sent.</div>';
        } else {
            // Email not sent
            echo '<div class="error-message">Oops! Something went wrong. Please try again later.</div>';
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modify_template'])) {
        $modified_content = wp_kses_post($_POST['template_content']); // Get modified template content
    
        // Assuming you have sanitized widget title and other fields before submission
        $widget_title = sanitize_text_field($_POST['widget_title']);
        $widget_key = 'template_' . sanitize_title($widget_title);
    
        $new_widget = array(
            'title' => $widget_title,
            'content' => $modified_content,
            'css_filename' => "custom-css-widget-{$widget_key}.css",
        );
    
        $widgets = get_option('holberton_widgets', array());
        $widgets[$widget_key] = $new_widget;
        update_option('holberton_widgets', $widgets);
    
        $shortcode = "[holberton-widget widget_id={$widget_key}]"; // Create shortcode for the new widget
    
        echo '<div class="updated">';
        echo '<p>Widget data and styles saved successfully!</p>';
        echo '<p>Use this shortcode on your WordPress page: <strong>' . esc_html($shortcode) . '</strong></p>';
        echo '</div>';
    }

    // Define the path to the external CSS file containing styles for premade widgets
$css_file = plugin_dir_url(__FILE__) . 'includes/css/user-panel.css';

// Define the premade widgets with their respective content and CSS class
$premade_widgets = array(
    'template_1' => array(
        'title' => 'Template 1',
        'content' => '<div class="premade-widget template-1">
            <h2>Title for Template 1</h2>
            <p>This is a sample content for Template 1. You can add any HTML elements and content here.</p>
            <ul>
                <li>List item 1</li>
                <li>List item 2</li>
                <li>List item 3</li>
            </ul>
            <p>Feel free to modify this content as needed!</p>
        </div>',
        'css' => '.template-1 { /* Specific CSS styles for Template 1 */ }',
    ),
    );
    

    // Retrieve widgets data from the database
    $widgets = get_option('holberton_widgets', array());

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

    document.addEventListener('DOMContentLoaded', function() {
    const templateContentInput = document.querySelector('.widget-code textarea[name="template_content"]');
    const templatePreview = document.querySelector('.template-preview .preview-container');
    const head = document.head || document.getElementsByTagName('head')[0];

    // Function to apply the CSS to the live preview
    function applyCSS(css) {
        const style = document.createElement('style');
        style.type = 'text/css';
        style.appendChild(document.createTextNode(css));
        head.appendChild(style);
    }

    // Update the live preview and apply the CSS when the user changes the content in the modification form
    templateContentInput.addEventListener('input', function() {
        templatePreview.innerHTML = templateContentInput.value;

        // Retrieve the CSS from the form
        const templateCSSInput = document.querySelector('.widget-code textarea[name="template_css"]');
        const newCSS = templateCSSInput.value;

        // Apply the new CSS to the live preview
        applyCSS(newCSS);
    });
});
    
</script>

    
<div class="wrap">
    <h2>Holberton Plugin</h2>
    <div class="wrap">
        <h2>Modify Pre-Made Widgets</h2>

        <div class="template-selection">
            <?php foreach ($premade_widgets as $template_id => $template) : ?>
                <div class="template-preview">
                <div class="premade-widget template-1">
                    <!-- Container for the template preview -->
                    <div class="preview-container" style="float: right; width: 40%;">
                        <?php echo $template['content']; ?>
                    </div>
                    <!-- Form to modify the template code -->
                    
                    <div class="widget-code" style="float: left; width: 50%;">
                        <h3><?php echo $template['title']; ?></h3>
                        <form method="post" action="">
    <label for="template_content">Modify Template Content:</label><br>
    <textarea name="template_content" rows="4" cols="50"><?php echo htmlspecialchars($template['content']); ?></textarea>
    <br><br>
    <label for="widget_title">Widget Title:</label>
    <input type="text" name="widget_title">
    <br><br>
    <input type="submit" name="modify_template" value="Save Changes">
</form>

                    </div>
                    <div style="clear: both;"></div>
                </div>
            <?php endforeach; ?>

            <!-- Social Media Sharing Widget form -->
            <div class="social-media-sharing-widget">
                <?php include(plugin_dir_path(__FILE__) . 'includes/social_media.php'); ?>
            </div>
        </div>
    <!-- logo -->
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

    <div class="holberton-container">
        <!-- Contact form widget -->
        <?php echo do_shortcode('[holberton-contact-form-widget]'); ?>
    </div>
    <div class="wrap">
        <!-- Your existing content -->

        <?php display_all_widgets(); ?>
    </div>
            </div>
            </div>
    <?php
}




// Function to save contact form widget data to the database
function save_contact_form_widget_data() {
    $contact_email = sanitize_email($_POST['contact_email']);
    $contact_message = wp_kses_post($_POST['contact_message']);

    $contact_form_widget = array(
        'contact_email' => $contact_email,
        'contact_message' => $contact_message,
    );

    // Save the contact form widget data
    update_option('holberton_contact_form_widget', $contact_form_widget);

    echo '<div class="updated"><p>Contact form widget data saved successfully!</p></div>';
}

function include_template_css($atts) {
    $atts = shortcode_atts(array(
        'widget_id' => '',
    ), $atts);

    if (empty($atts['widget_id'])) {
        return 'Please specify a widget ID.';
    }

    $uploads_dir = wp_upload_dir();
    $css_directory = $uploads_dir['baseurl'] . '/holberton-css-files/';
    $css_filename = "custom-css-widget-{$atts['widget_id']}.css";

    // Enqueue the generated CSS file for the specified widget ID
    wp_enqueue_style('template_css', $css_directory . $css_filename);

    return ''; // Return empty string to prevent content duplication
}
add_shortcode('template_css', 'include_template_css');
function save_css_file($widget_key, $css_content) {
    $uploads_dir = wp_upload_dir(); // Get the uploads directory

    // Directory path to store CSS files
    $css_directory = $uploads_dir['basedir'] . '/holberton-css-files/';
    $css_filename = "custom-css-widget-{$widget_key}.css"; // Create a unique filename based on the widget ID

    if (!file_exists($css_directory)) {
        wp_mkdir_p($css_directory); // Create the directory if it doesn't exist
    }

    // Save or update the CSS file content
    file_put_contents($css_directory . $css_filename, $css_content);
}

// Add shortcodes dynamically for each widget key
function register_widget_shortcodes() {
    $widgets = get_option('holberton_widgets', array());
    
    foreach ($widgets as $widget_key => $widget) {
        add_shortcode($widget_key, 'display_widget_content_shortcode');
    }
}
add_action('init', 'register_widget_shortcodes');


// Function to save widget data to the database
function save_widget_data() {
    $widget_title = sanitize_text_field($_POST['widget_title']);
    $widget_content = wp_kses_post($_POST['widget_content']);

    $widgets = get_option('holberton_widgets', array());

    $widget_key = 'template_' . sanitize_title($widget_title);

    $new_widget = array(
        'title' => $widget_title,
        'content' => $widget_content,
        'css_filename' => "custom-css-widget-{$widget_key}.css",
    );

    $widgets[$widget_key] = $new_widget;
    update_option('holberton_widgets', $widgets);

    echo '<div class="updated"><p>Widget data and styles saved successfully!</p></div>';
}

// Add a shortcode handler for [template_1]
function display_template_1_content() {
    $widgets = get_option('holberton_widgets', array());

    // Check if the template_1 widget exists
    if (isset($widgets['template_1'])) {
        return $widgets['template_1']['content'];
    }

    // If template_1 doesn't exist, return an empty string or a default message
    return 'Template 1 not found.';
}
add_shortcode('template_1', 'display_template_1_content');




// Add a shortcode handler for each widget key
function display_widget_content_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'widget_id' => '', // Add other attributes if required
        ),
        $atts
    );

    // Get the content of the specific widget based on the passed ID
    $widget_id = sanitize_text_field($atts['widget_id']);
    $widgets = get_option('holberton_widgets', array());

    if (isset($widgets[$widget_id])) {
        return $widgets[$widget_id]['content'];
    } else {
        return 'Widget not found!';
    }
}
add_shortcode('holberton_widget', 'display_widget_content');

// Function to render the Social Media Sharing Widget content in the admin dashboard
function render_social_media_sharing_widget() {
    // Instantiate the Social Media Sharing Widget
    $widget = new Social_Media_Sharing_Widget();
    
    // Define default instance data
    $instance = array(
        'facebook_url' => '', // Define default Facebook URL
        // Add default values for other social media URLs...
    );

    // Render the widget form
    echo '<div class="widget-content">';
    echo '<p>Add your social media URLs:</p>';
    echo '<form method="post">';
    echo '<label for="facebook_url">Facebook URL:</label>';
    echo '<input type="text" id="facebook_url" name="facebook_url" value="' . esc_attr($instance['facebook_url']) . '"><br>';
    // Add form fields for other social media URLs...
    echo '<input type="submit" name="save_social_media_urls" value="Save">';
    echo '</form>';
    $widget->form($instance);
    echo '</div>';
}









