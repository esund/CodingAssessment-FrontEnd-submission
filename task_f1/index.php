<?php
/**
 * Created by Ethan Sundstrom
 *
 * Uses vanilla javascript.
 *
 */
?><!DOCTYPE html><html>
<head>
    <title>Task F1: HTML5 Image Upload and View</title>
    <script type="text/javascript" id="myself_script">

        // When the form's #image_file file-type input is changed, provide some information to the user.
        function file_chosen() {

            // Grab the filename from the form element.
            var file = document.getElementById('image_file').files[0];

            // Make sure we have a filename, then calculate the information and display it.
            if (file)
            {
                // Init the size_of_vile variable to 0.
                var size_of_file = 0;

                if (file.size > 1048576) // If it's more than a megabyte...
                    // ... then calculate the megabyre size value, or ...
                    size_of_file = (Math.round(file.size * 100 / 1048576) / 100).toString() + 'MB';
                else // it's less than a megabyte so calculate how many kilobytes the size is.
                    size_of_file =  (Math.round(file.size * 100 / 1024) / 100).toString() + 'KB';

                // Put the file name, size, and MIME type into the informational section for display.
                document.getElementById('file_name').innerHTML = 'File Name: ' + file.name;
                document.getElementById('file_size').innerHTML = 'File Size: ' + size_of_file;
                document.getElementById('file_type').innerHTML = 'File Type: ' + file.type;
            }
        }

        // When the form's submit button is clicked then upload the file with AJAX.
        function upload_to_server() {

            // Create a new XMLHttpRequest object.
            var xml_http_request = new XMLHttpRequest();

            // Create a nre FormData object.
            var form_data = new FormData();

            // Grab the information from the form's file-type input and push it into the FormData object.
            form_data.append('image_file', document.getElementById('image_file').files[0]);

            // Create an event listeners for the XMLHttpRequest upload progress, completion and failure responses and
            // set the functions to be called for each case.
            xml_http_request.upload.addEventListener('progress', upload_progress, false);
            xml_http_request.addEventListener('load', upload_done, false);
            xml_http_request.addEventListener('error', upload_fail, false);

            // Set the method and action for the actual HTTP request.
            xml_http_request.open('POST', 'upload.php');

            // Perform the request by sending the form_data object to the action URL using the specified method.
            xml_http_request.send(form_data);
        }


        // Function called by the progress event listener on the XMLHttpRequest object.
        function upload_progress(e) {

            // Check to see of the response is actually a valid computable value.
            if (e.lengthComputable) {

                // Calculate the percentage of completeness (in N% rather than .N)
                var progress_percentage = Math.round(e.loaded * 100 / e.total);

                // Push the completness percentage into the HTML element.
                document.getElementById('progress_display').innerHTML = 'Upload progress:' + progress_percentage.toString() + '%';
            }
            else
            {
                // If the response is not computable then display that error message instead.
                document.getElementById('progress_display').innerHTML = 'Failed to display progress.';
            }
        }

        // Function called by the load event listener on the XMLHttpRequest object.
        function upload_done(e) {

            // If the data returned from the POSY action does not begin with Fail then we have success and can do
            // some drawing now.
            if (e.target.responseText.substring(0,4) != "Fail")
            {

                // Get the canvas HTML5 element...
                var canvas = document.getElementById('returned_image');

                // ... and  context object so we can draw on the canvas.
                var context = canvas.getContext('2d');

                // Create a new Image object.
                var imageObj = new Image();

                // When we load the src onto the object...
                imageObj.onload = function() {

                    // ... set the canvas size to the same width and height as the image data being loaded into it.
                    canvas.setAttribute('width', this.width);
                    canvas.setAttribute('height', this.height);

                    // Draw the image ojbect data into the cavnas context object, starting at 0,0 and finishing
                    // at the final pixel of the canvas (since we've already set the canvas size to exactly match
                    // the size of the image object.)
                    context.drawImage(imageObj, 0, 0, this.width, this.height);
                };

                // Set up the href for loading the image into the canvas element. Do this by getting the current URL
                // and appending the uploaded file path. And just in case the browser is at /index.php instrad of /,
                // we use a substr to get the URL up to the last slast, effectively trimming off the filename, if it's
                // there, from the data returned from document.location.href
                var image_location = document.location.href.substr(0, location.href.lastIndexOf('/') + 1) + e.target.responseText;

                // Display the full URL of the saved file.
                document.getElementById('file_saved').innerHTML = image_location;

                // Use the HTML5 Image API to push the image into the canvas object.
                imageObj.src = image_location;

                // Set up the event listener for the Clear Canvas button.
                document.getElementById('clear').addEventListener('click', function() {

                    // Clear the canvas by making it 0x0
                    canvas.setAttribute('width', 0).setAttribute('height', 0);

                    // Find all of the .file_details elements and create an array.
                    var file_details = document.getElementsByClassName('file_details');

                    // Set up a counting var based on the array length.
                    var i = file_details.length;

                    // Loop through each array element, counting down to 0...
                    while(i--)
                    {
                        // ... and clear out the HTML within.
                        file_details[i].innerHTML = '';
                    }
                }, false);
            }
            else
            {
                // If the PHp uploader returns a Failure message, then just pop up an alert with that error.
                alert(e.target.responseText);
            }

        }

        function upload_fail(e) {
            // If the XMLHttpRequest returned a failure, pop up an alert.
            alert('Upload failed.');
        }
    </script>
</head>
<body>

<form id="upload_form" action="upload.php" method="post" enctype="multipart/form-data">
    <div class="full_width">
        <input type="file" id="image_file" name="image_file"  onchange="file_chosen();" accept="image/gif, image/jpeg, image/png" />
    </div>

    <div class="full_width">
        <input type="button" onclick="upload_to_server()" value="Upload Image">
        <input type="button" id="clear" value="Clear Canvas">
    </div>

</form>

<!-- This section is the informational data regarding the selected file, and is populated then the
        onchange="file_chosen()" is fired off. -->
<div><p id="file_name" class="file_details"></p></div>
<div><p id="file_size" class="file_details"></p></div>
<div><p id="file_type" class="file_details"></p></div>
<div><p id="progress_display" class="file_details"></p></div>
<div><p id="file_saved" class="file_details"></p></div>

<!-- This is the HTML2 canvas that the image will be drawn into. -->
<canvas id="returned_image"></canvas>

</body>
</html>