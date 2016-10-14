<?php 
	session_start(); 
	if (isset($_SESSION['logged_user'])) {
		$olduser = $_SESSION['logged_user'];
	} 
	else {
		$olduser = false;
	}
?>

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
	<title>Login</title>
    <style>
		.login {
			text-decoration: underline;
		}
	</style>
</head>

<!-- 
Login: Users can login to their account
 -->

<body>
	<canvas id="canvas" resize></canvas>
	<?php 
	include("includes/phpfile.php"); 
	if (isset($_SESSION['logged_user']) && $_SESSION['logged_user'] == 'ldp54') {
		echo customHeaderAdmin("Login", "Login to your account");
	}
	else {
		echo customHeader("Login", "Login to your account");
	}

	require_once 'includes/config.php';
	$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
	?>
	<!-- ldp54, mypassword -->
	<div class="content">
		<div class="inner-border">
			<?
			if ( $olduser ) {
				print( "<p class='contentSubmit'>You're still logged in, $olduser!</p>");
				print( "<p class='contentSubmit'>Don't forget to logout!</p>");
				echo "<form action='login.php' method='post'><button type='submit' name='logout' class='form-button'>Log Out</button></form>";
				if (isset($_POST['logout'])) {
					$olduser = $_SESSION['logged_user'];
					unset( $_SESSION['logged_user'] );
					echo "<p class='contentSubmit'>You have been logged out.<p>";
				}
			} 
			else {
				echo	"<div class='form-content'>
					<form action='login.php' method='post'>
						Username <br>
						<input type='text' name='username'> <br>
						Password <br>
						<input type='text' name='password' placeholder='Password'><br>
						<button type='submit' name='submit' class='form-button'>Login</button>
					</form>
				</div>";
				if (isset($_POST['submit'])) {
					$username = filter_input( INPUT_POST, 'username', FILTER_SANITIZE_STRING );
					$password = filter_input( INPUT_POST, 'password', FILTER_SANITIZE_STRING );

					$hashPassword = password_hash( $password, PASSWORD_DEFAULT);

					$query = "SELECT * FROM users WHERE username = '$username'; ";
					$result = $mysqli->query($query);

					if ($result && $result->num_rows == 1) {
						$row = $result->fetch_assoc();
						$db_hash_password = $row[ 'hashpassword' ];
						if( password_verify( $password, $db_hash_password )) {
							echo "<p class='contentSubmit'>Congratulations, $username. You are logged in.<p>";
							$_SESSION['logged_user'] = $_POST['username'];
						}
						else {
						echo "<p class='contentSubmit'>Incorrect Password.</p>";
						echo "<p class='contentSubmit'>Please <a href='login.php'>login</a> again.</p>";
					}
					}
					else {
						echo "<p class='contentSubmit'>Wrong username.</p>";
						echo "<p class='contentSubmit'>Please <a href='login.php'>login</a> again.</p>";
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