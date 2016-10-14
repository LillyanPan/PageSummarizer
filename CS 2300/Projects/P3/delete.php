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
	<title>Delete Item</title>
    <style>
		.delete {
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
		echo customHeaderAdmin("Delete Item", "Cleaning");
	}
	else {
		echo customHeader("Delete Item", "Cleaning");
	}

	require_once 'includes/config.php';
	$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
	?>
	<div class="content">
		<div class="inner-border">
			<div class="form-content">
				<form class="add" action="delete.php" method="post" enctype="multipart/form-data">
					<h2>Delete Album</h2> <br>
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
					<button type="submit" name="submit" class="form-button">Delete</button><br>
					<div class="space"></div>
					<div class="splitline"></div>
					<h2>Delete Photo</h3> <br>
					<select name="photo">
						<option value='0'>None</option>
						<?php
						$sql = "SELECT * FROM Photos";
						$result = $mysqli->query($sql);
						while ( $pRow = $result->fetch_assoc()) {
							$pid = $pRow["pId"];
							$pTitle = $pRow["pTitle"];
							echo "<option value='$pid'>$pTitle</option>";
						}
						?>
					</select> <br>
					<button type="submit" name="submit" class="form-button">Delete</button>
				</form>
			</div>

	<?php
			if (isset($_POST['submit'])) {
				$album = $_POST['album'];
				$photo = $_POST['photo'];
				if ($album != 0) {
					$sql = "DELETE FROM Albums WHERE aId = '$album'";
					$sql2 = "DELETE FROM PhotosAlbums WHERE aId = '$album'";
					$result = $mysqli->query($sql);
					$result1 = $mysqli->query($sql2);
					echo "<div class='contentSubmit'>";
					echo "The album has been deleted!";
					echo "</div>";
				}
				if ($photo != 0) {
					$sql = "DELETE FROM Photos WHERE pId = '$photo'";
					$sql2 = "DELETE FROM PhotosAlbums WHERE pId = '$photo'";
					$result = $mysqli->query($sql);
					$result1 = $mysqli->query($sql2);
					echo "<div class='contentSubmit'>";
					echo "The photo has been deleted!";
					echo "</div>";
				}
			}
		?>


		</div>
	</div>
    
	<?php echo customFooter(); $mysqli->close(); ?>
    
    <script src="js/main.js"></script>
</body>

</html>