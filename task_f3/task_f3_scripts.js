/**
 * Created by Ethan Sundstrom
 *
 * Lightbox script. Uses jQuery library.
 */

$(document).ready(function(){

    // When the image is clicked create the HTML element which will load the image from the server.
    $('img').on('click', function(e){

        // Stop the click from registering on the anchor's href. This is set up this way for grace fallback
        // for non-js browsers.
        e.preventDefault();

        // Grab the URL in the href attribute of the anchor that contains the clicked image.
        var image_url = $(this).parent().attr('href');

        // Show the lightbox div, and create the HTML / load the full-size image.
        $('#lightbox_background').html('<p id="lightbox"><img src="' + image_url + '" /></p>').show();
    });

    // When the lightbox background (or the displayed image) is clicked, then hide the ligghtbox background and
    // destroy the image container.
    $('#lightbox_background').on('click', function(){

        // Un-display the lightbox background (and all of its children.
        $(this).hide();

        // Destroy the image and its container element.
        $('#lightbox').remove();
    });
});
