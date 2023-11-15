jQuery(document).ready(function($) {
    $('#upload_image_button').on('click', function(e) {
        e.preventDefault();
        var image = wp.media({
            title: 'Select or Upload Image',
            button: {
                text: 'Use This Image'
            },
            multiple: false
        }).open();

        image.on('select', function() {
            var uploadedImage = image.state().get('selection').first();
            var imageUrl = uploadedImage.toJSON().url;
            $('#image_slider_image_url').val(imageUrl);
        });
    });
});