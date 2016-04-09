<!DOCTYPE html>
<html>

<head>

    <!-- This is the character encoding of my website -->
	<meta charset="UTF-8">
    
    <!-- This tells search engines what the website is about -->
	<meta name="description" content="Lillyan's HTML5 website, prints catalog">
	
    <!-- Created favicon -->
	<link rel="icon" href="favicon.ico" type="image/x-icon">
    
    <!-- This is your external CSS file for making things pretty -->    
	<link rel="stylesheet" href="css/style.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	
    <!-- This is the text that appears on the browser tab -->
	<title>Prints</title>
    <style>
		.add {
			text-decoration: underline;
		}
	</style>
</head>



<body>

	<header>
		<div id="header-wrapper">
			<div class="top">
				<div id="title">
					Prints Collection
				</div>
				<div id="title-description">
					~~Expand the collection~~
				</div>
			</div>
			<nav id="sidebar">
				<ul>
					<li class="home"><a href="index.php">Home</a></li>
	                <li class="search"><a href="search.php">Search</a></li>
	                <li class="add"><a href="add.php">Add</a></li>
				</ul>
			</nav>
		</div>
	</header>
	
	<div class="content">
		<div class="inner-border">
			<div class="form-content">
				<!-- <form action="functions.php" method="post"> -->
				<form action="add.php" method="post">
					Print Title <br>
					<input type="text" name="title"> <br>
					Artist <br>
					<input type="text" name="artist"><br>
					Date <br>
					<input type="text" name="date" placeholder="Year Only (Ex. 1990)"><br>
					Country <br>
					<input type="text" name="country"><br>
					Image Link <br>
					<input type="text" name="imagelink" placeholder="https://www.image.com"><br>
					<button type="submit" name="submit" class="form-button">Submit</button>
				</form>
			</div>

				<?php

			/*********** Form Validation ***********/ 

			if (isset($_POST['submit'])) {
				$title = $_POST['title'];
				$artist = $_POST['artist'];
				$date = $_POST['date'];
				$country = $_POST['country'];
				$imagelink = $_POST['imagelink'];

					// For-loop checking if form is all filled
				$required = array('title', 'artist', 'date', 'country', 'imagelink');
					$missing = false; // false if everything's filled
					foreach ($required as $field) {
						if (empty($_POST[$field])) {
							$missing = true;
						}
					}
					if ($missing) {
						echo "<div class='contentSubmit'>";
						echo "Please fill out all fields";
						echo "</div>";
					}

					// Nothing is missing, check is entries are valid
					else {
						// Validation using regex
						if ((!preg_match("#[A-Z a-z-']+#",$title)) || (strlen($title) > 50)) {
							echo "<div class='contentSubmit'>";
							echo "Please use letters, apostrophes, and dashes only for the title. 50 character limit";
							echo "</div>";
						}
						else if ((!preg_match("#[A-Z a-z-']+#",$artist)) || (strlen($artist) > 50)) {
							echo "<div class='contentSubmit'>";
							echo "Please use letters, apostrophes, and dashes only for the artist's name. 50 character limit";
							echo "</div>";
						}
						else if ((!preg_match("#[0-9]{4}#",$date)) || (intval($date) < 1500) || (intval($date) > 2017)) {
							echo "<div class='contentSubmit'>";
							echo "Please enter valid year (Ex. 1990)";
							echo "</div>";
						}
						else if ((!preg_match("#[A-Z a-z-']+#",$country)) || (strlen($artist) > 80)) {
							echo "<div class='contentSubmit'>";
							echo "Please use letters, apostrophes, and dashes only for the country. 80 character limit";
							echo "</div>";
						}
					// Regular Expression adapted from http://stackoverflow.com/questions/3809401/what-is-a-good-regular-expression-to-match-a-url
						// else if (!preg_match("#^((http[s]?|ftp):\/)?\/?([^:\/\s]+)((\/\w+)*\/)([\w\-\.]+[^#?\s]+)(.*)?(#[\w\-]+)?$#",$imagelink)) {
						
						else if (filter_var($imagelink, FILTER_VALIDATE_URL) === false) {
							echo "<div class='contentSubmit'>";
							echo "Not a valid image link";
							echo "</div>";
						}
					// Successful submit
						else {
						    $file = fopen("data.txt", "a");
						    if (!$file) {
						      die("There was a problem opening the data.txt file");
						    }
						    $row = array($title, $artist, $date, $country, $imagelink);
						    $newLine = implode("	", $row);
						    fwrite($file, "\n".$newLine);

						    fclose($file);
						    
							echo "<div class='contentSubmit'>";
							echo "Thanks for adding! Check the Home page for your submission";
							echo "</div>";
						}
					}
				}

			?>
		</div>
	</div>
    
	<footer>
        <div id="copyright-date">
            Copyright &copy; 2016 Lillyan Pan. All rights reserved.
        </div>
	</footer>
    
    <script src="js/main.js"></script>
</body>

</html>