<?php
/**
 * Created by Ethan Sundstrom
 */

// The following code
$url = "https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=giftcards.com&rsz=8";

// sendRequest

// First initialize the curl session and get the handle.
$ch = curl_init();

// Set up the options for the request.
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_REFERER, "giftcards.com");

// make the call and fetch the return data.
$body = curl_exec($ch);

// Close the session.
curl_close($ch);


// Convert the JSON string and convert it into a variable object.
$json = json_decode($body);

// Grab the actual results array of objects.
$results = $json->responseData->results;

?><!DOCTYPE html>
<html>
<head>
    <title>Task F3</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="task_f3_scripts.js" type="text/javascript"></script>
</head>
<body>

<?php

// Loop through each array element and grab the properties we want to put into the HTML. Then create a series of
// divs displaying the thumbnails for each image, the alt text and title, as well as a caption.
foreach($results as $k=>$result){
    echo "<div class='image_frame'>
            <div class='image_wrapper'>
                <p>
                    <a href='$result->url'><img src='$result->tbUrl' alt='$result->contentNoFormatting' title='$result->contentNoFormatting'/></a>
                </p>
            </div><!-- .image_wrapper -->
            <div class='caption_wrapper'>
                <p>$result->title</p>
            </div><!-- .caption_wrapper -->
        </div><!-- .image_frame -->";
}


// Finally, create a hidden lightbox div that our script can display full-size images within.
?>

<div id="lightbox_background"></div><!-- #lightbox_background -->
</body>
</html>