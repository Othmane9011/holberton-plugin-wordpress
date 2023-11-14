<?php

// Handle the Logo Upload and Save It
function save_logo_data() {
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
        $upload_dir = wp_upload_dir();
        $logo_filename = sanitize_file_name($_FILES['logo']['name']);
        $logo_path = $upload_dir['path'] . '/' . $logo_filename;

        move_uploaded_file($_FILES['logo']['tmp_name'], $logo_path);
        
        // Save the path to the logo in the database or elsewhere for retrieval.
        update_option('custom_logo_path', $logo_path);
    }
    // ... other save logic ...
}

//  Generate a Shortcode for the Logo
function generate_logo_shortcode() {
    $logo_path = get_option('custom_logo_path');
    if (!empty($logo_path)) {
        // The shortcode to display the logo
        return '<img src="' . esc_url($logo_path) . '" alt="Your Logo">';
    } else {
        return 'No logo uploaded.';
    }
}

//  Display the Shortcode to the User
function display_logo_shortcode() {
    $shortcode = generate_logo_shortcode();
    echo '<div class="logo-shortcode">' . $shortcode . '</div>';
}

// Add a shortcode to display the logo
add_shortcode('custom-logo', 'generate_logo_shortcode');

// Function to handle logo upload
function handle_logo_upload() {
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $logo = $_FILES['logo'];

        // Define the directory to store uploaded logos
        $upload_dir = wp_upload_dir();

        if (move_uploaded_file($logo['tmp_name'], $upload_dir['basedir'] . '/' . $logo['name'])) {
            $uploaded_logo_url = $upload_dir['baseurl'] . '/' . $logo['name'];

            // Save the uploaded logo URL in the database
            update_option('holberton_uploaded_logo', $uploaded_logo_url);
        }
    }
}

// Shortcode function to display the uploaded logo
function holberton_logo_shortcode() {
    $uploaded_logo = get_option('holberton_uploaded_logo');

    if ($uploaded_logo) {
        return '<img src="' . esc_url($uploaded_logo) . '" alt="Uploaded Logo">';
    }

    return ''; // Return an empty string if no logo has been uploaded
}

// Register the shortcode
add_shortcode('holberton-logo', 'holberton_logo_shortcode');