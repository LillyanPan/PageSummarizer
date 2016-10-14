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
	<title>Edit</title>
    <style>
		.edit {
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
		echo customHeaderAdmin("Edit", "Edit items");
	}
	else {
		echo customHeader("Edit", "Edit items");
	}

	require_once 'includes/config.php';
	$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
	?>
	<div class="content">
		<div class="inner-border">
			<div class="form-content">
				<div class="eAlbum">
					<h2>Edit Albums</h2>
					<table>
						<?php
						$sql = "SELECT * FROM Albums";
						$result = $mysqli->query($sql);
						editDisplayAlbums($result);
						?>
					</table>
				</div>
				<div class='longline'></div>
				<div class="ePhoto">
					<h2>Edit Photos</h2>
					<table>
						<?php
						$sql = "SELECT * FROM Photos";
						$result = $mysqli->query($sql);
						editPhotosFromAlubm($result);
						?>
					</table>
				</div>
				<div class='contentSubmit'>
				</div>
			</div>
		</div>
	</div>
    
	<?php echo customFooter(); $mysqli->close(); ?>
    
    <script src="js/main.js"></script>
</body>

</html>