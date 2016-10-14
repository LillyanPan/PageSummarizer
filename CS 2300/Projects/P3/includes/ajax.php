<?php
	require_once 'config.php';
	$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

	$request_type = filter_input(INPUT_POST, "requestType", FILTER_SANITIZE_STRING);

	if ($mysqli->connect_errno) {
		// print('Inside errno');
		print($mysqli->connect_error);
		die();
	}
	switch ( $request_type ) {

		case "editImage":
			$title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING);
			$imagelink = filter_input(INPUT_POST, "imagelink", FILTER_VALIDATE_URL);
			$caption = filter_input(INPUT_POST, "caption", FILTER_SANITIZE_STRING);
			$pid = filter_input(INPUT_POST, "pid", FILTER_SANITIZE_STRING);

			$query = "UPDATE Photos SET pTitle='$title', pCaption='$caption', pURL='$imagelink' WHERE pId= $pid";
			$result = $mysqli->query($query);
			if( !$result ) {
				echo 'Query error';
				die();
			} 
			die();
			break;


		case "editAlbum":
			$title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING);
			$imagelink = filter_input(INPUT_POST, "imagelink", FILTER_VALIDATE_URL);
			$aid = filter_input(INPUT_POST, "aid", FILTER_SANITIZE_STRING);

			$query = "UPDATE Albums SET aTitle='$title', aCoverPhoto='$imagelink', aDateMod=NOW() WHERE aId= $aid";
			$result = $mysqli->query($query);
			if( !$result ) {
				echo 'Query error';
				die();
			} 
			die();
			break;

	}




?>