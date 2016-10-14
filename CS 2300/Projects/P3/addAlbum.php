<?php session_start(); ?>

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

	<!-- Source: http://paperjs.org/tutorials/ ! -->
	<script type="text/javascript" src="js/paper-full.js"></script>
	<script type="text/paperscript" src="js/paperScript.js" canvas="canvas"></script>
	
    <!-- This is the text that appears on the browser tab -->
	<title>Album</title>
    <style>
		.addAlbum {
			text-decoration: underline;
		}
	</style>
</head>

<!--
Add Page: Users can add images to a specific/their albums or create a new album

 -->

<body>
	<canvas id="canvas" resize></canvas>
	<?php 
	include("includes/phpfile.php"); 
	if (isset($_SESSION['logged_user']) && $_SESSION['logged_user'] == 'ldp54') {
		echo customHeaderAdmin("Add Album", "~~Expand the collection~~");
	}
	else {
		echo customHeader("Add Album", "~~Expand the collection~~");
	}


	require_once 'includes/config.php';
	$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
	?>
	<div class="content">
		<div class="inner-border">
			<div class="form-content">
				<!-- <form action="functions.php" method="post"> -->
				<form class="add" action="addAlbum.php" method="post" enctype="multipart/form-data">
					Album <br>
					<input type="text" name="title"> <br>
					Image Link <br>
					<input type="text" name="imagelink" placeholder="https://www.image.com"><br>
					<button type="submit" name="submit" class="form-button">Submit</button>
				</form>
			</div>

	<?php

			/*********** Form Validation ***********/ 

			if (isset($_POST['submit'])) {
				$title = trim(filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING));
				$imagelink = $_POST['imagelink'];

					// For-loop checking if form is all filled
				$required = array($title, $imagelink);
					$missing = false; // false if everything's filled
					foreach ($required as $field) {
						if (empty($field)) {
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
					// Regular Expression adapted from http://stackoverflow.com/questions/3809401/what-is-a-good-regular-expression-to-match-a-url
						// else if (!preg_match("#^((http[s]?|ftp):\/)?\/?([^:\/\s]+)((\/\w+)*\/)([\w\-\.]+[^#?\s]+)(.*)?(#[\w\-]+)?$#",$imagelink)) {
						
						if (filter_var($imagelink, FILTER_VALIDATE_URL) === false) {
							echo "<div class='contentSubmit'>";
							echo "Not a valid image link";
							echo "</div>";
						}
					// Successful submit
						else {
							$query = "SELECT * FROM Albums WHERE aTitle = '$title'";
							$result = $mysqli->query($query);
							if ( $result->num_rows != 0) {
								echo "<div class='contentSubmit'>";
								echo "Duplicate Name.";
								echo "</div>";
							}
							else {
								$sql1 = "INSERT INTO Albums (aTitle, aCoverPhoto) 
								VALUES ('$title', '$imagelink')";
								$result1 = $mysqli->query($sql1);
								echo "<div class='contentSubmit'>";
								echo "Thanks for adding!";
								echo "</div>";
							}
						}
					}
				}

			?>


		</div>
	</div>
    
	<?php echo customFooter(); $mysqli->close(); ?>
    
    <script src="js/main.js"></script>
</body>

</html>