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

	<script type="text/javascript" src="js/paper-full.js"></script>
	<script type="text/paperscript" src="js/paperScript.js" canvas="canvas"></script>
	
    <!-- This is the text that appears on the browser tab -->
	<title>Home</title>
	<style>
		.home {
			text-decoration: underline;
		}
	</style>
    
</head>

<!-- 
This page will display all albums. Eventually users can click on an album cover and 
all of the photos associated will that album will be displayed either in a new page/below

 -->

<body>
	<canvas id="canvas" resize></canvas>
	<?php 
	include("includes/phpfile.php"); 
	if (isset($_SESSION['logged_user']) && $_SESSION['logged_user'] == 'ldp54') {
		echo customHeaderAdmin("Albums", "Explore the photo albums");
	}
	else {
		echo customHeader("Albums", "Explore the photo albums");
	}

	require_once 'includes/config.php';
	$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
	?>
	<div class="content">
		<div class="inner-border">
			<!-- Displaying table of album info -->
			<?php
			$sql1 = "SELECT * FROM Albums";
				$result1 = $mysqli->query($sql1);
				if (!isset($_GET['aid']) && !isset($_GET['pid'])) {
					echo "<table class='aitems'>
							<tr>
								<th>Title</th>
				    			<th>Date Created</th>
				    			<th>Date Modified</th>
				    		</tr>";
					while ( $aRow = $result1->fetch_assoc()) {
						$title = $aRow["aTitle"];
						$dateMod = $aRow["aDateMod"];
						$dateCreate = $aRow["aDateMod"];
						$aid = $aRow["aId"];
						echo albumRows($title, $dateCreate, $dateMod, $aid);
					}
				}

				else if (isset($_GET['pid'])) {
					$pid = $_GET['pid'];
					$sql2 = "SELECT * FROM Photos WHERE pId = '$pid'";
					$result2 = $mysqli->query($sql2);
					echo "<table class='items'>";
					displaySinglePhoto($result2);
				}

				// else if (isset($_GET['aid'])) {
				else {
					$aID = $_GET['aid'];
					echo "<table class='items'>";
					$result = getPhotosFromAlbum($aID, $mysqli);
					displayPhotosFromAlbum($result);
				}
				$mysqli->close();
			?>
			</table>


		</div>
	</div>
	<?php echo customFooter();?>
    <script src="js/main.js"></script>
</body>

</html>