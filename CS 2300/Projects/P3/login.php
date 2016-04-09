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
	<title>Log In</title>
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

	<?php 
	include("includes/phpfile.php"); 
	echo customHeader("Log In", "Login to your account");
	?>
	
	<div class="content">
		<div class="inner-border">
			<div class="form-content">
				<!-- <form action="functions.php" method="post"> -->
				<form action="search.php" method="post">
					Username <br>
					<input type="text" name="username"> <br>
					Password <br>
					<input type="text" name="password" placeholder="Password"><br>
					<button type="submit" name="submit" class="form-button">Login</button>
				</form>
			</div>
	<table>
	</table>
		</div>
	</div>

	<?php echo customFooter(); ?>
    
    <script src="js/main.js"></script>
</body>

</html>