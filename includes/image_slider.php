<?php
/*
Plugin Name: Your Image Slider Plugin
*/

// Function to display the image selector in the admin panel
function image_slider_admin_settings() {
    // Check if the current user can manage options
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form method="post" action="options.php">
            <?php
            // Output necessary settings fields
            settings_fields('image_slider_section');
            do_settings_sections('image_slider_section');
            submit_button('Save Changes');
            ?>
        </form>
    </div>
    <?php
}


// Function to register and display fields in the admin panel
function image_slider_settings_init() {
    // Register a setting for image URLs
    register_setting('image_slider_section', 'image_slider_image_urls', 'sanitize_image_urls');

    // Add a section for the image slider settings
    add_settings_section(
        'image_slider_section',
        'Image Slider Settings',
        'image_slider_section_callback',
        'image_slider_section'
    );

    // Add a field to upload/select an image
    add_settings_field(
        'image_slider_image_urls',
        'Select Images',
        'image_slider_image_urls_callback',
        'image_slider_section',
        'image_slider_section'
    );
}

// Function to sanitize image URLs
function sanitize_image_urls($input) {
    // Sanitize and validate image URLs before saving
    return $input;
}

// Callback to display the section description
function image_slider_section_callback() {
    echo '<p>Select images for the image slider.</p>';
}

// Callback to display the image uploader
function image_slider_image_urls_callback() {
    $image_urls = esc_attr(get_option('image_slider_image_urls'));
    ?>
    <input type="text" id="image_slider_image_urls" name="image_slider_image_urls" value="<?php echo $image_urls; ?>" readonly>
    <input type="button" id="upload_image_button" class="button" value="Upload Image" onclick="uploadImage(event)">
    <?php
}

// Function to enqueue necessary scripts and styles for media uploader
function enqueue_media_uploader() {
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'enqueue_media_uploader');

// Function to handle image upload
function handle_image_upload() {
    check_admin_referer('image_upload_nonce', 'image_upload_nonce');

    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['image_slider_image_urls'])) {
        update_option('image_slider_image_urls', $_POST['image_slider_image_urls']);
    }
}

// Save image on form submit
function save_image_slider_settings() {
    ?>
    <script>
    function uploadImage(event) {
        event.preventDefault();
        var image = wp.media({
            title: 'Select or Upload Image',
            button: {
                text: 'Use This Image'
            },
            multiple: true // Allow multiple image selection
        }).open();

        image.on('select', function() {
            var uploadedImages = image.state().get('selection');
            var imageURLs = [];

            uploadedImages.each(function(attachment) {
                var imageUrl = attachment.attributes.url;
                imageURLs.push(imageUrl);
            });

            document.getElementById('image_slider_image_urls').value = imageURLs.join(',');
        });
    }
    </script>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        handle_image_upload();
    }
}
add_action('admin_init', 'save_image_slider_settings');

// Add admin menu page for image slider settings
function image_slider_menu() {
    add_menu_page(
        'Image Slider Settings',
        'Image Slider',
        'manage_options',
        'image-slider-settings',
        'image_slider_admin_settings',
        'dashicons-format-gallery'
    );
}
add_action('admin_menu', 'image_slider_menu');
add_action('admin_init', 'image_slider_settings_init');

// Display stored images in the image slider container
function display_image_slider() {
    $image_urls = get_option('image_slider_image_urls');
    if ($image_urls) {
        $image_urls = explode(',', $image_urls);
        echo '<div class="image-slider-container">';
        foreach ($image_urls as $image_url) {
            echo '<img src="' . esc_url($image_url) . '" alt="Slider Image">';
        }
        echo '</div>';
    }
}

