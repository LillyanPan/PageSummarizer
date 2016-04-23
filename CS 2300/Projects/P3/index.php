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
	<title>Photos</title>
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
	<?php 
	include("includes/phpfile.php"); 
	echo customHeader("Albums", "Look at a photo album");

	require_once 'includes/config.php';
	$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
	?>
	
	<div class="content">
		<div class="inner-border">
<!-- 			<?php
			$sql1 = "SELECT * FROM Albums WHERE Albums.aID = 1";
					$result1 = $mysqli->query($sql1);
					while ( $aRow = $result1->fetch_assoc()) {
						$title = $aRow["aTitle"];
						$aCover = $aRow["aCoverPhoto"];
						echo 
						"<div class='album'>
							<div class='album-border'>
								<img id='albumPhoto' src='$aCover' alt=''>
							</div>
							<div class='aline'></div>
							<div class='album-title'>
								Album $title
							</div>
						</div>";
				}
			?> -->
			<!-- Displaying table of album info -->
			<?php
			$sql1 = "SELECT * FROM Albums";
				$result1 = $mysqli->query($sql1);
				if (!isset($_GET['aid'])) {
					echo "<table class='aitems'>
							<tr>
								<td>Title</td>
				    			<td>Date Created</td>
				    			<td>Date Modified</td>
				    		</tr>";
					while ( $aRow = $result1->fetch_assoc()) {
						$title = $aRow["aTitle"];
						$dateMod = $aRow["aDateMod"];
						$dateCreate = $aRow["aDateMod"];
						$aid = $aRow["aId"];
						echo albumRows($title, $dateCreate, $dateMod, $aid);
					}
				}

				// else if (isset($_GET['aid'])) {
				else {
					$aID = $_GET['aid'];
					echo "<table class='items'>";
					$rowCount = 0;
					$total = 0;
					$sql = "SELECT * FROM Photos 
						INNER JOIN PhotosAlbums 
						ON PhotosAlbums.pID = Photos.pID
						INNER JOIN Albums
						ON PhotosAlbums.aID = Albums.aID
						WHERE Albums.aID = $aID";
					$result = $mysqli->query($sql);
					while ( $row = $result->fetch_assoc()) {
						$title = $row["pTitle"];
						$imagelink = $row["pURL"];
						$caption = $row["pCaption"];
						echo tableRows($title, $imagelink, $caption, $rowCount, $total);

						if ($rowCount == 2) $rowCount = 0;
						else ($rowCount++);
						$total++;
					}
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