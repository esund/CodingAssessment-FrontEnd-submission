<?php
/**
 * Created by Ethan Sundstrom
 */

// The save path for the uploaded file. This directory must exist and must have proper permissions that allow the
// apache user to write there. (Also make sure the that umask will set up permissions properly on uploaded files.)
$target_path = 'uploaded-images/' . basename($_FILES['image_file']['name']);

// Check to make sure the file is of the correct type. If it's not, send an error and exit.
if(!in_array($_FILES['image_file']['type'], array('image/jpeg', 'image/gif', 'image/png')) )
{
    echo 'Failed to accept the uploaded file because it is of an unacceptable type. Please upload a jpeg, gif or png.';
    exit;
}

// Try to save the uploaded file to the $target_path. If it succeeds then retuen the save path and filename. If it fails
// then send back a failure message.
if(move_uploaded_file($_FILES['image_file']['tmp_name'], $target_path)) {
    echo $target_path;
} else{
    echo "Failed to save this uploaded file to server location. The submitted file may be too large, or the destination " .
    "path may not exist, or the destination may not have the correct write permissions.";
    print_r($_FILES);
}
