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
	<title>Photos</title>
    <style>
		.search {
			text-decoration: underline;
		}
	</style>
</head>

<!-- 
Search Page: Users can search for a specific album or photo in the database

 -->

<body>
	<canvas id="canvas" resize></canvas>
	<?php 
	include("includes/phpfile.php"); 
	if (isset($_SESSION['logged_user']) && $_SESSION['logged_user'] == 'ldp54') {
		echo customHeaderAdmin("Photo Collection", "Search for a specfic album");
	}
	else {
		echo customHeader("Photo Collection", "Search for a specfic album");
	}

	require_once 'includes/config.php';
	$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
	?>
	<div class="content">
		<div class="inner-border">
			<div class="form-content">
				<!-- <form action="functions.php" method="post"> -->
				<form action="search.php" method="post">
					Photo Title <br>
					<input type="text" name="title"> <br>
					Album Name <br>
					<select name="album">
						<option value='0'>None</option>
						<?php
						$sql = "SELECT * FROM Albums";
						$result = $mysqli->query($sql);
						while ( $aRow = $result->fetch_assoc()) {
							$aid = $aRow["aId"];
							$aTitle = $aRow["aTitle"];
							echo "<option value='$aid'>$aTitle</option>";
						}
						?>
					</select>
					<br>
					Caption <br>
					<input type="text" name="captionKey"> <br>
					<button type="submit" name="submit" class="form-button">Search</button>
				</form>
			</div>
			<table>
			<?php

				/*********** Form Validation ***********/ 

				if (isset($_POST['submit'])) {
					$title = trim(filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING));
					$album = filter_input(INPUT_POST, "album", FILTER_SANITIZE_STRING);
					$caption = trim(filter_input(INPUT_POST, "caption", FILTER_SANITIZE_STRING));

					// For-loop checking if form is all filled
					$oneReq = array($title, $caption);
					$atLeastOneFill = false; // false no field is filled
					foreach ($oneReq as $field) {
						if (!empty($field)) {
							$atLeastOneFill = $atLeastOneFill || true;
						}
					}
					if (!$atLeastOneFill) {
						echo "<div class='contentSubmit'>";
						echo "Please fill out at least one search field";
						echo "</div>";
					}

					// Nothing is missing, check is entries are valid
					else {
						// Validation using regex
						$valid = true;
						if (!empty($title)) {
							if ((!preg_match("#[A-Z a-z-']+#",$title)) || (strlen($title) > 50)) {
								echo "<div class='contentSubmit'>";
								echo "Please use letters, apostrophes, and dashes only for the title. 50 character limit";
								echo "</div>";
								$valid = false;
							}
						}
						if (!empty($caption)) {
							if ((!preg_match("#[a-z]*[A-Z]*[0-9]*[\s]*[.]*#",$caption)) || (strlen($caption) > 500)) {
								echo "<div class='contentSubmit'>";
								echo "Please use letters, apostrophes, and dashes only for the title. 500 character limit";
								echo "</div>";
							}
						}
						// Valid Search
						if ($valid) {
							// Specific album requested
							if ($album != 0) {
								// Case: title is empty, caption search
								if (!empty($caption) && empty($title)) {
									$sql = "SELECT * FROM Photos 
										INNER JOIN PhotosAlbums 
										ON PhotosAlbums.pID = Photos.pID
										INNER JOIN Albums
										ON PhotosAlbums.aID = Albums.aID
										WHERE Albums.aID = $album 
										AND Photos.pCaption LIKE '%' + '$caption' + '%'";
								}
								// Case: caption is empty, title search
								else if (empty($caption) && !empty($title)) {
									$sql = "SELECT * FROM Photos 
										INNER JOIN PhotosAlbums 
										ON PhotosAlbums.pID = Photos.pID
										INNER JOIN Albums
										ON PhotosAlbums.aID = Albums.aID
										WHERE Albums.aID = $album
										AND Photos.pTitle = '$title'";
								}

								// Case: Caption and title search
								else {
									$sql = "SELECT * FROM Photos 
										INNER JOIN PhotosAlbums 
										ON PhotosAlbums.pID = Photos.pID
										INNER JOIN Albums
										ON PhotosAlbums.aID = Albums.aID
										WHERE Albums.aID = $album
										AND Photos.pTitle = '$title'
										AND Photos.pCaption LIKE ('%' + '$caption' + '%')";
								}
							}
							else {
								// Case: title is empty, caption search
								if (!empty($caption) && empty($title)) {
									$sql = "SELECT * FROM Photos
										WHERE Photos.pCaption LIKE '%' + '$caption' + '%'";
								}
								// Case: caption is empty, title search
								else if (empty($caption) && !empty($title)) {
									$sql = "SELECT * FROM Photos
										WHERE Photos.pTitle = '$title'";
								}

								// Case: Caption and title search
								else {
									$sql = "SELECT * FROM Photos 
										WHERE Photos.pTitle = '$title'
										AND Photos.pCaption LIKE ('%' + '$caption' + '%')";
								}
							}
							$result = $mysqli->query($sql);
							if (!$result) {
								echo("error");
							}
							if ( $result->num_rows != 0) {
								displayPhotosFromAlbum($result);
							}
							else {
								echo "<div class='contentSubmit'>";
								echo "No matches!";
								echo "</div>";
							}
						}
					}
				}
			?>
			</table>
		</div>
	</div>

	<?php echo customFooter(); ?>
    
    <script src="js/main.js"></script>
</body>

</html>