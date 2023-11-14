jQuery(document).ready(function($) {
    console.log('Custom script is running');

    // Initialize Clipboard.js on "Copy Shortcode" buttons
    new ClipboardJS('.copy-shortcode', {
        text: function(trigger) {
            var widgetIDContainer = $(trigger).prev('.widget-id');
            return widgetIDContainer.text();
        }
    });

    $('.remove-widget-button').on('click', function(e) {
        e.preventDefault();
        var $removeForm = $(this).closest('.remove-widget-form');
        var widgetID = $removeForm.find('input[name="remove_widget"]').val();

        // Send an AJAX request to handle widget removal
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'remove_holberton_widget',
                widget_id: widgetID
            },
            success: function(response) {
                // Handle the removal on success (e.g., hide the widget box)
                if (response === 'success') {
                    $removeForm.closest('.widget-box').remove();
                }
            }
        });
    });
    
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


    // Your custom JavaScript code for other advanced features
    console.log(jQuery); // A helpful log statement to check if jQuery is loaded
});