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
		.add {
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
		echo customHeaderAdmin("Add Photo", "~~Expand the collection~~");
	}
	else {
		echo customHeader("Add Photo", "~~Expand the collection~~");
	}

	require_once 'includes/config.php';
	$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
	?>
	<div class="content">
		<div class="inner-border">
			<div class="form-content">
				<!-- <form action="functions.php" method="post"> -->
				<form action="add.php" method="post" enctype="multipart/form-data">
					Photo Title <br>
					<input type="text" name="title"> <br>
					Album <br>
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
					<textarea name="caption"></textarea><br>
					Image Link <br>
					<input type="text" name="imagelink" placeholder="https://www.image.com; Either upload image or provide image link"><br>
					Single Photo Upload <br>
					<input type="file" name="newphoto"> <br>
					<button type="submit" name="submit" class="form-button">Submit</button>
				</form>
			</div>

	<?php

			/*********** Form Validation ***********/ 

			if (isset($_POST['submit'])) {
				// $title = $_POST['title'];
				// $album = $_POST['album'];
				// $caption = $_POST['caption'];
				$title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING);
				$album = $_POST['album'];
				$caption = filter_input(INPUT_POST, "caption", FILTER_SANITIZE_STRING);
				
				$imagelink = $_POST['imagelink'];
				$newFile = $_FILES[ 'newphoto' ];
				$originalName = $newFile[ 'name' ];
				$size_in_bytes = $newFile[ 'size' ];
				$tempName = $newFile[ 'tmp_name' ];
				$type = $newFile[ 'type' ];
				$error = $newFile[ 'error' ];

					// For-loop checking if form is all filled
				$required = array($title, $caption);
					$missing = false; // false if everything's filled
					foreach ($required as $field) {
						// If required fileds are empty and both image link and file upload is empty
						if (isset($field) && (empty($newFile) && empty($imagelink) )) {
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

						if ((!preg_match("#[A-Z a-z-'.]+#",$caption)) || (strlen($caption) > 500)) {
							echo "<div class='contentSubmit'>";
							echo "Please use letters, apostrophes, and dashes only for the title. 500 character limit";
							echo "</div>";
						}

						// Both image upload and image link are provided
						if (!isset($newFile) && !isset($imagelink)) {
							echo "<div class='contentSubmit'>";
							echo "Only upload an image or provide an image link.";
							echo "</div>";
						}

						// File upload
						if ($size_in_bytes > 0 && $error) {
							echo "<div class='contentSubmit'>";
							echo "Not a valid file upload";
							echo "</div>";
						}

						// Image Link
						if (!isset($newFile) && filter_var($imagelink, FILTER_VALIDATE_URL) === false) {
							echo "<div class='contentSubmit'>";
							echo "Not a valid image link";
							echo "</div>";
						}
						
					// Successful submit
						else {
							// Duplicates
							$query = "SELECT * FROM Photos WHERE pTitle = '$title'";
							$result = $mysqli->query($query);
							if ( $result->num_rows != 0) {
								echo "<div class='contentSubmit'>";
								echo "Duplicate Name.";
								echo "</div>";
							}
							else {
								// Image Link
								if (!empty($imagelink)) {
									$sql1 = "INSERT INTO Photos (pTitle, pCaption, pURL) 
								VALUES ('$title', '$caption', '$imagelink')";
								}

								// File Upload
								else {
									move_uploaded_file($tempName, "assets/$originalName" );
									$url = "assets/$originalName";
									$sql1 = "INSERT INTO Photos (pTitle, pCaption, pURL)
										VALUES ('$title', '$caption', '$url')";
								}

								$result1 = $mysqli->query($sql1);
								$pid = $mysqli -> insert_id;

								// If user chooses to add to some album
								if ($album != 0) {
									$sql2 = "INSERT INTO PhotosAlbums (pID, aID)
										VALUES ('$pid','$album')";
									$result2 = $mysqli->query($sql2);
							    }
								echo "<div class='contentSubmit'>";
								echo "Thanks for adding! Check the album to view.";
								echo "</div>";
							}
						}
					}
				}

			?>


		</div>
	</div>
    
	<?php echo customFooter(); ?>
    
    <script src="js/main.js"></script>
</body>

</html>