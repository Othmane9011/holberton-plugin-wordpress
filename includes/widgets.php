<?php



class Contact_Form_Widget extends WP_Widget {

    

    // Constructor
    public function __construct() {
        parent::__construct(
            'contact_form_widget', // Base ID
            'Contact Form Widget', // Widget name
            array( 'description' => 'Add a contact form to your site' ) // Widget description
        );
    }
    

    // Widget Front-end Display
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        echo '<div class="contact-form-widget">';
        echo '<button class="toggle-widget">Reduce/Expand</button>'; // Add the button here
        echo '<form action="" method="post">';
        echo '<label for="name">Name:</label><br>';
        echo '<input type="text" id="name" name="name"><br>';
        echo '<label for="email">Email:</label><br>';
        echo '<input type="email" id="email" name="email"><br>';
        echo '<label for="message">Message:</label><br>';
        echo '<textarea id="message" name="message" rows="4" cols="30"></textarea><br>';
        echo '<input type="submit" value="Submit">';
        echo '</form>';
        echo '</div>';
        echo $args['after_widget'];
    }

    // Widget Backend Form
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : 'Contact';
        ?>
        
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>

        
        <?php
    }

    // Save Widget Settings
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
}




// Shortcode function to display the contact form widget
function holberton_contact_form_widget_shortcode($atts) {
    // Process attributes if needed
    $atts = shortcode_atts(
        array(
            // Define default attribute values if necessary
        ),
        $atts
    );

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact_form'])) {
        // Process form data
        $contact_name = sanitize_text_field($_POST['contact_name']);
        $contact_email = sanitize_email($_POST['contact_email']);
        $contact_message = sanitize_textarea_field($_POST['contact_message']);

        // Here, you can perform any action with the submitted data (e.g., send an email, save to database, etc.)

        // Display a success message after form submission
        $output = '<div class="contact-form-success">';
        $output .= '<p>Thank you for your message, ' . esc_html($contact_name) . '! We will get back to you soon.</p>';
        $output .= '</div>';

        return $output;
    }

    // If the form is not submitted or it's the initial load, display the contact form
    ob_start(); // Start output buffering
    ?>



    
    <div class="holberton-contact-form-widget">
        <!-- Contact form HTML -->
        
        <form action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post">
            <div class="holberton-form-field">
                <label for="contact_name">Name:</label>
                <input type="text" id="contact_name" name="contact_name" required>
            </div>
            
            <div class="holberton-form-field">
                <label for="contact_email">Email:</label>
                <input type="email" id="contact_email" name="contact_email" required>
            </div>
            <div class="holberton-form-field">
                <label for="contact_message">Message:</label>
                <textarea id="contact_message" name="contact_message" rows="5" required></textarea>
            </div>
            <input type="submit" name="submit_contact_form" class="button button-primary" value="Submit">
        </form>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = document.querySelectorAll('.toggle-widget');

        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const widgetContainer = this.parentElement;
                widgetContainer.classList.toggle('reduced'); // Add a class to reduce/expand the widget
            });
        });
    });
    </script>
    <?php if (is_admin()) : ?>
    <!-- Container for admin view -->
    <div class="admin-contact-form-widget">
    <p>Copy the shortcode to display your contact form:</p>
    <input type="text" id="contact-form-shortcode" value="[holberton-contact-form-widget]" style="width: 250px;" readonly>
</div>
<?php endif; ?>
    <?php

    
    $output = ob_get_clean(); // Get the buffered content and clean the buffer

    return $output;
}

// Register the shortcode for the contact form widget
add_shortcode('holberton-contact-form-widget', 'holberton_contact_form_widget_shortcode');



// Register Contact Form Widget
function register_contact_form_widget() {
    register_widget( 'Contact_Form_Widget' );
}
add_action( 'widgets_init', 'register_contact_form_widget' );

function render_holberton_widgets() {
    $widgets = get_option('holberton_widgets', array());

    // Display other widgets
    foreach ($widgets as $widget) {
        // Display other widgets here...
    }

    // Display the contact form widget
    $contact_form_widget = get_option('contact_form_widget_data'); // Retrieve contact form widget data
    if (!empty($contact_form_widget)) {
        $contact_widget_title = $contact_form_widget['title'];
        $contact_widget_content = $contact_form_widget['content'];
        $contact_widget_styles = $contact_form_widget['styles'];

        if (!empty($contact_widget_title) && !empty($contact_widget_content)) {
            echo '<div class="holberton-widget" style="' . esc_attr($contact_widget_styles) . '">';
            echo '<h2>' . esc_html($contact_widget_title) . '</h2>';
            echo wp_kses_post($contact_widget_content);
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


