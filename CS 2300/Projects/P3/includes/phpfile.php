<?php

// Creates footer
function customFooter() {
	return "<footer>
        <div id='copyright-date'>
            Copyright &copy; 2016 Lillyan Pan. All rights reserved.
        </div>
	</footer>";
}

// Creates Header for Admin
function customHeaderAdmin($title, $titleDescription) {
	return "<header>
		<div id='header-wrapper'>
			<div class='top'>
				<div id='title'>
					$title
				</div>
				<div id='title-description'>
					$titleDescription
				</div>
			</div>
			<nav id='sidebar'>
				<ul>
					<li class='home'><a href='index.php'>Albums</a></li>
	                <li class='search'><a href='search.php'>Search</a></li>
	                <li class='add'><a href='add.php'>Add Image</a></li>
	                <li class='addAlbum'><a href='addAlbum.php'>Add Album</a></li>
	                <li class='delete'><a href='delete.php'>Delete</a></li>
	                <li class='edit'><a href='edit.php'>Edit</a></li>
	                <li class='login'><a href='login.php'>Log In</a></li>
				</ul>
			</nav>
		</div>
	</header>";
}

// Header for general users
function customHeader($title, $titleDescription) {
	return "<header>
		<div id='header-wrapper'>
			<div class='top'>
				<div id='title'>
					$title
				</div>
				<div id='title-description'>
					$titleDescription
				</div>
			</div>
			<nav id='sidebar'>
				<ul>
					<li class='home'><a href='index.php'>Albums</a></li>
	                <li class='search'><a href='search.php'>Search</a></li>
	                <li class='login'><a href='login.php'>Log In</a></li>
				</ul>
			</nav>
		</div>
	</header>";
}

// Index page; display photos
function tableRows($title, $imagelink, $caption, $rowCount, $total, $pid) {
	if ($rowCount == 0) {
		return "<tr>
			<td id='$total'>
				<div class='item'>
					<div class='item-border'>
						<div class='slider'>
							<p>$title : $caption. Image source is $imagelink.</p>
						</div>
						<a href='https://info2300.coecis.cornell.edu/users/ldp54sp16/www/P3/index.php?pid=$pid'>
						<img class='img-$total' src='$imagelink' alt=''>
						</a>
					</div>
					<div class='item-title'>
						$title
					</div>
				</div>
			</td>";
	}
	else if ($rowCount == 2) {
		return "<td>
				<div class='item'>
					<div class='item-border'>
						<div class='slider'>
							<p>$title : $caption. Image source is $imagelink.</p>
						</div>
						<a href='https://info2300.coecis.cornell.edu/users/ldp54sp16/www/P3/index.php?pid=$pid'>
						<img class='img-$total' src='$imagelink' alt=''>
						</a>
					</div>
					<div class='item-title'>
						$title
					</div>
				</div>
			</td>
		</tr>";
	}

	else {
		return "<td>
				<div class='item'>
					<div class='item-border'>
						<div class='slider'>
							<p>$title : $caption. Image source is $imagelink.</p>
						</div>
						<a href='https://info2300.coecis.cornell.edu/users/ldp54sp16/www/P3/index.php?pid=$pid'>
						<img class='img-$total' src='$imagelink' alt=''>
						</a>
					</div>
					<div class='item-title'>
						$title
					</div>
					<div class='line'></div>
				</div>
			</td>";
	}
}

// Edit page; table display
function editPictures($title, $imagelink, $caption, $rowCount, $total, $pid) {
	if ($rowCount == 0) {
		return "<tr>
			<td id='$total'>
			<div class='item-border'>
				<img class='img-$total' src='$imagelink' alt=''>
			</div>
				<form class='editPhotos'>
					<input class='item-title' type='text' name='title' value='$title'><br>
					<input class='imagelink' type='text' name='imagelink' value='$imagelink'><br>
					<input class='pid' type='text' name='pid' value='$pid'>
					<textarea class='caption' name='caption'>$caption</textarea><br>
					<button type='button' class='form-button pic-button'>Submit</button>
				</form>
			</td>";
	}
	else if ($rowCount == 2) {
		return "<td>
			<div class='item-border'>
				<img class='img-$total' src='$imagelink' alt=''>
			</div>
				<form class='editPhotos' action='edit.php' method='post' enctype='multipart/form-data'>
					<input class='item-title' type='text' name='title' value='$title'><br>
					<input class='imagelink' type='text' name='imagelink' value='$imagelink'><br>
					<input class='pid' type='text' name='pid' value='$pid'>
					<textarea class='caption' name='caption'>$caption</textarea><br>
					<button type='button' name='submit' class='form-button pic-button'>Submit</button>
				</form>
			</td>
		</tr>";
	}

	else {
		return "<td>
			<div class='item-border'>
				<img class='img-$total' src='$imagelink' alt=''>
			</div>
				<form class='editPhotos' action='edit.php' method='post' enctype='multipart/form-data'>
					<input class='item-title' type='text' name='title' value='$title'><br>
					<input class='imagelink' type='text' name='imagelink' value='$imagelink'><br>
					<input class='pid' type='text' name='pid' value='$pid'>
					<textarea class='caption' name='caption'>$caption</textarea><br>
					<button type='button' name='submit' class='form-button pic-button'>Submit</button>
				</form>
			</td>";
	}
}

// Edit page; table display for albums
function editAlubms($title, $imagelink, $total, $rowCount, $aid) {
	if ($rowCount == 0) {
		return "<tr>
			<td>
			<div class='item-border'>
				<img class='img-$total' src='$imagelink' alt=''>
			</div>
				<form class='editAlubms'>
					<input class='item-title' type='text' name='title' value='$title'><br>
					<input class='imagelink' type='text' name='imagelink' value='$imagelink'><br>
					<input class='aid' type='text' name='aid' value='$aid'>
					<button type='button' class='form-button alb-button'>Submit</button>
				</form>
			</td>";
	}
	else if ($rowCount == 2) {
		return "<td>
			<div class='item-border'>
				<img class='img-$total' src='$imagelink' alt=''>
			</div>
				<form class='editAlubms' action='edit.php' method='post' enctype='multipart/form-data'>
					<input class='item-title' type='text' name='title' value='$title'><br>
					<input class='imagelink' type='text' name='imagelink' value='$imagelink'><br>
					<input class='aid' type='text' name='aid' value='$aid'>
					<button type='button' name='submit' class='form-button alb-button'>Submit</button>
				</form>
			</td>
		</tr>";
	}

	else {
		return "<td>
			<div class='item-border'>
				<img class='img-$total' src='$imagelink' alt=''>
			</div>
				<form class='editAlubms' action='edit.php' method='post' enctype='multipart/form-data'>
					<input class='item-title' type='text' name='title' value='$title'><br>
					<input class='imagelink' type='text' name='imagelink' value='$imagelink'><br>
					<input class='aid' type='text' name='aid' value='$aid'>
					<button type='button' name='submit' class='form-button alb-button'>Submit</button>
				</form>
			</td>";
	}
}

// Index page: displaying album titles
function albumRows($title, $dateCreate, $dateMod, $aid) {
	return "<tr>
			<td><a href='https://info2300.coecis.cornell.edu/users/ldp54sp16/www/P3/index.php?aid=$aid'>$title</a></td>
    		<td>$dateCreate</td>
    		<td>$dateMod</td>
    	</tr>";
}

// More information on a specfic photo
function singlePhoto($title, $imagelink, $caption, $pid) {
	return "<div class='single'>
				<div class='descrip'>
					<p class='head'>Title<p>
					<p>$title</p>
					<p class='head'>Caption<p>
					<p>$caption.</p> 
					<p class='head'>Image source<p> 
					<p>$imagelink</p>
				</div>
				<div class='left'>
				<img src='$imagelink' alt=''>
				</div>
			</div>";
}

// SQL query to get all photos that match a specific aID
function getPhotosFromAlbum($aID, $mysqli) {
	$sql = "SELECT * FROM Photos 
		INNER JOIN PhotosAlbums 
		ON PhotosAlbums.pID = Photos.pID
		INNER JOIN Albums
		ON PhotosAlbums.aID = Albums.aID
		WHERE Albums.aID = $aID";
	return $result = $mysqli->query($sql);
}

// Overall function to display photos from a specfic album
function displayPhotosFromAlbum($result) {
	$rowCount = 0;
	$total = 0;
	while ( $row = $result->fetch_assoc()) {
		$title = $row["pTitle"];
		$imagelink = $row["pURL"];
		$caption = $row["pCaption"];
		$pid = $row["pId"];
		echo tableRows($title, $imagelink, $caption, $rowCount, $total, $pid);

		if ($rowCount == 2) $rowCount = 0;
		else ($rowCount++);
		$total++;
	}
}

// Display a single photo (index page)
function displaySinglePhoto($result) {
	while ( $row = $result->fetch_assoc()) {
		$title = $row["pTitle"];
		$imagelink = $row["pURL"];
		$caption = $row["pCaption"];
		$pid = $row["pId"];
		echo singlePhoto($title, $imagelink, $caption, $pid);
	}
}

// Overall function to display albums on the edit page
function editDisplayAlbums($result) {
	$rowCount = 0;
	$total = 0;
	while ( $row = $result->fetch_assoc()) {
		$title = $row["aTitle"];
		$imagelink = $row["aCoverPhoto"];
		$aid = $row["aId"];
		echo editAlubms($title, $imagelink, $total, $rowCount, $aid);

		if ($rowCount == 2) $rowCount = 0;
		else ($rowCount++);
		$total++;
	}
}

// Overall function to display photos from a specfic album
function editPhotosFromAlubm($result) {
	$rowCount = 0;
	$total = 0;
	while ( $row = $result->fetch_assoc()) {
		$title = $row["pTitle"];
		$imagelink = $row["pURL"];
		$caption = $row["pCaption"];
		$pid = $row["pId"];
		echo editPictures($title, $imagelink, $caption, $rowCount, $total, $pid);

		if ($rowCount == 2) $rowCount = 0;
		else ($rowCount++);
		$total++;
	}
}

?>