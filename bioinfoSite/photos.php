<?php

/* Initialize the template variables */
$encoding = "UTF-8";
$desc = "These are Natalie's Photos";
$favicon = "favicon.ico";
$css_files = array("css/style.css", 
                   "css/photo-style.css");
$title = "";
$nav_links = array("Home" => "index.html", 
                   "Photos" => "photos.php",
                   "Test PHP Version" => "test.php");
$page_content = "";
$copy_date = "";
$author = "Natalie Diebold";


/* Use PHP functions to fill the page with more interesting content */

// Get the year from today's date
$copy_date = date("Y");


// Add a heading to the page content with dot concatenation
$page_content .= "<h1>Random Photo!</h1>";

// Store a bunch of photo filenames we want to use
$photos = array("cat",
                "dog1",
                "dog2",
                "grass",
                "potato",
                "pancakes",
                "squash",
                "trees",
                "water" ); 


// Randomly select a photo to display
$rand_index = mt_rand(0,6);

// Add the photo to the $page_content
$page_content .= "<img class=\"photo\"
                       src=\"assets/{$photos[$rand_index]}.jpg\" 
                       alt=\"{$photos[$rand_index]}\"
                       title=\"{$photos[$rand_index]}\" />";


/* Use the template to output the HTML5 page*/
require("template.php");

?>