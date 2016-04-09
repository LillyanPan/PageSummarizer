<?php

function customFooter() {
	return "<footer>
        <div id='copyright-date'>
            Copyright &copy; 2016 Lillyan Pan. All rights reserved.
        </div>
	</footer>";
}

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
	                <li class='add'><a href='add.php'>Add Image</a></li>
	                <li class='addAlbum'><a href='addAlbum.php'>Add Album</a></li>
	                <li class='login'><a href='login.php'>Log In</a></li>
				</ul>
			</nav>
		</div>
	</header>";
}

function tableRows($title, $imagelink, $caption, $rowCount, $total) {
	if ($rowCount == 0) {
		return "<tr>
			<td id='$total'>
				<div class='item'>
					<div class='item-border'>
						<div class='slider'>
							<p>$title : $caption. Image source is $imagelink.</p>
						</div>
						<img id='img-$total' src='$imagelink' alt=''>
					</div>
					<div class='line'></div>
					<div class='item-title'>
						$title
					</div>
				</div>
			</td>";
	}
	else if ($rowCount == 2) {
		return "<td id='$total'>
				<div class='item'>
					<div class='item-border'>
						<div class='slider'>
							<p>$title : $caption. Image source is $imagelink.</p>
						</div>
						<img id='img-$total' src='$imagelink' alt=''>
					</div>
					<div class='line'></div>
					<div class='item-title'>
						$title
					</div>
				</div>
			</td>
		</tr>";
	}

	else {
		return "<td id='$total'>
				<div class='item'>
					<div class='item-border'>
						<div class='slider'>
							<p>$title : $caption. Image source is $imagelink.</p>
						</div>
						<img id='img-$total' src='$imagelink' alt=''>
					</div>
					<div class='line'></div>
					<div class='item-title'>
						$title
					</div>
				</div>
			</td>";
	}
}

function albumRows($title, $dateCreate, $dateMod, $aid) {
	return "<tr>
			<td><a href='https://info2300.coecis.cornell.edu/users/ldp54sp16/www/P3/index.php?aid=$aid'>$title</a></td>
    		<td>$dateCreate</td>
    		<td>$dateMod</td>
    	</tr>";
}

?>