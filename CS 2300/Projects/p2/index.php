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
		.home {
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
					A collection of print designs
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
			<table class="items">
				<?php
					$delimiter = "\t";
					$lines = array();
					$collection = array();
		// Can read and write
					$file = fopen("data.txt", "r+");
					if (!$file) {
						die("There was a problem opening the data.txt file");
					}
	    // Read data
					while (!feof( $file )) {
	    				$lines[] = fgets( $file ); //array, each element is a line
	    			}
				    // Create collection array
				    foreach ($lines as $item) {
				    	// FIX delimiter not working?
						$info = explode($delimiter, trim($item)); //info is array of that line
						$collection[] = array('title' => $info[0],'artist' => $info[1],'date' => $info[2],'country' => $info[3],'imagelink' => $info[4]);
					}
					fclose($file);

					// Loop through to display
					$total = 0;
					$rowCount = 0;
					foreach ($collection as $arr) {
						$title = $arr['title'];
						$artist = $arr["artist"];
						$date = $arr["date"];
						$country = $arr["country"];
						$imagelink = $arr["imagelink"];	
						if ($rowCount == 0) {		//beginning of row
							$rowCount++;
							$total++;
								echo "<tr>";
								echo 	"<td id='$total'>";
								echo 		"<div class='item'>";
								echo			"<div class='item-border'>";
								echo				"<div class='slider'>";
								echo					"<p>$title is from $date and created by $artist. The print
								comes from $country. Image source is $imagelink</p>";
								echo			"</div>";
								echo				"<img id='img-$total' src='$imagelink' alt=''>";
								echo			"</div>";
								echo			"<div class='line'></div>";
								echo			"<div class='item-title'>";
								echo				"$title";
								echo			"</div>";
								echo 		"</div>";
								echo 	"</td>";
						}
						elseif ($rowCount == 2) {		//end of row
							$rowCount = 0;
							$total++;
								echo 	"<td id='$total'>";
								echo 		"<div class='item'>";
								echo			"<div class='item-border'>";
								echo				"<div class='slider'>";
								echo					"<p>$title is from $date and created by $artist. The print
								comes from $country. Image source is $imagelink</p>";
								echo			"</div>";
								echo				"<img id='img-$total' src='$imagelink' alt=''>";
								echo			"</div>";
								echo			"<div class='line'></div>";
								echo			"<div class='item-title'>";
								echo				"$title";
								echo			"</div>";
								echo 		"</div>";
								echo 	"</td>";
								echo "</tr>";
						}
						else {
							$rowCount++;
							$total++;
								echo 	"<td id='$total'>";
								echo 		"<div class='item'>";
								echo			"<div class='item-border'>";
								echo				"<div class='slider'>";
								echo					"<p>$title is from $date and created by $artist. The print
								comes from $country. Image source is $imagelink</p>";
								echo			"</div>";
								echo				"<img id='img-$total' src='$imagelink' alt=''>";
								echo			"</div>";
								echo			"<div class='line'></div>";
								echo			"<div class='item-title'>";
								echo				"$title";
								echo			"</div>";
								echo 		"</div>";
								echo 	"</td>";
						}
					}
				?>
			</table>
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