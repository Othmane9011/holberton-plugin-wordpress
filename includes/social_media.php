<?php
class Social_Media_Sharing_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'social_media_sharing_widget',
            'Social Media Sharing Widget',
            array('description' => 'Add social media sharing buttons to your site')
        );
    }

    public function widget($args, $instance) {
        // Display social media icons based on user-provided URLs
        echo $args['before_widget'];

        // Get social media URLs from widget settings
        $facebook_url = isset($instance['facebook_url']) ? esc_url($instance['facebook_url']) : '';
        // Get other social media URLs similarly...

        // Display social media icons/buttons linked to the respective URLs
        echo '<div class="social-media-icons">';
        if (!empty($facebook_url)) {
            echo '<a href="' . $facebook_url . '" target="_blank"><img src="facebook-icon.png" alt="Facebook"></a>';
        }
        // Display other social media icons/buttons similarly...
        echo '</div>';

        echo $args['after_widget'];
    }

    public function form($instance) {
        // Form fields to input social media URLs
        $facebook_url = !empty($instance['facebook_url']) ? esc_url($instance['facebook_url']) : '';
        // Add other social media URL fields similarly...
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('facebook_url'); ?>">Facebook URL:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('facebook_url'); ?>" name="<?php echo $this->get_field_name('facebook_url'); ?>" type="text" value="<?php echo esc_attr($facebook_url); ?>">
        </p>
        <!-- Add fields for other social media URLs similarly... -->
        <?php
    }

    public function update($new_instance, $old_instance) {
        // Save widget settings when updated
        $instance = array();
        $instance['facebook_url'] = !empty($new_instance['facebook_url']) ? esc_url($new_instance['facebook_url']) : '';
        // Save other social media URLs similarly...
        return $instance;
    }
}

// Register Social Media Sharing Widget
function register_social_media_sharing_widget() {
    register_widget('Social_Media_Sharing_Widget');
}
add_action('widgets_init', 'register_social_media_sharing_widget');



