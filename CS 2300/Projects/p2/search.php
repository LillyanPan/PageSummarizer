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
		.search {
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
					Search for a print
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
				<form action="search.php" method="post">
					Print Title <br>
					<input type="text" name="title"> <br>
					Date <br>
					<input type="text" name="date" placeholder="Year Only (Ex. 1990)"><br>
					<button type="submit" name="submit" class="form-button">Search</button>
				</form>
			</div>
			<table>
		<?php

			/*********** Form Validation ***********/ 

			if (isset($_POST['submit'])) {
				$title = $_POST['title'];
				$date = $_POST['date'];

					// For-loop checking if form is all filled
				$oneReq = array('title', 'date');
				$atLeastOneFill = false; // false no field is filled
				foreach ($oneReq as $field) {
					if (!empty($_POST[$field])) {
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
					if (!empty($date)) {
						if (!preg_match("#[0-9]{4}#",$date) || (intval($date) < 1500) || (intval($date) > 2017)) {
							echo "<div class='contentSubmit'>";
							echo "Please enter valid year (Ex. 1990)";
							echo "</div>";
							$valid = false;
						}
					}
				// Successful search

					if ($valid) {
						echo "<div class='contentSubmit'>";
						echo "Filtered results below";
						echo "</div>";

			//Look thorugh collection array
					    $delimiter = "\t";
						$lines = array();
						$collection = array();
						$filtered = array();
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
							$collection[] = array('title' => $info[0],
								'artist' => $info[1],
								'date' => $info[2],
								'country' => $info[3],
								'imagelink' => $info[4]);
						}
					    fclose($file);

					    // Search just title
					    if (!empty($title) && empty($date)) {
					    // Search for match
					    	foreach ($collection as $arr) {
					    		if ($arr['title'] == $title) {
					    			$filtered[] = $arr;
						    	}
						    }
						}
						else if (empty($title) && !empty($date)) {
							foreach ($collection as $arr) {
					    		if ($arr['date'] == $date) {
					    			$filtered[] = $arr;
						    	}
						    }
						}
						//Both are filled
						else {
							foreach ($collection as $arr) {
					    		if ($arr['title'] == $title && $arr['date'] == $date) {
					    			$filtered[] = $arr;
						    	}
						    }
						}
						if (count($filtered) == 0) {
							echo "<div class='contentSubmit'>";
							echo "No found matches";
							echo "</div>";
						}
						else {
						// Loop through to display
							$total = 0;
							$rowCount = 0;
							foreach ($filtered as $arr) {
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
								else if ($rowCount == 2) {		//end of row
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
						}
					}
				}
			}

		?>
	</table>
		</div>
	</div>
    <!-- should just have it add filtered results to bottom of page, 
    you can scroll because of y-overflow -->
	<footer>
        <div id="copyright-date">
            Copyright &copy; 2016 Lillyan Pan. All rights reserved.
        </div>
	</footer>
    
    <script src="js/main.js"></script>
</body>

</html>