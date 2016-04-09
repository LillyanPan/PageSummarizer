<!DOCTYPE html>
<!-- Source: Template from 2300 course materials -->
<html>

<head>

	<!-- This is the character encoding of my website -->
	<meta charset="UTF-8">

	<!-- This tells search engines what the website is about -->
	<meta name="Lillyan Pan" content="Lillyan Pan's website">
	
	<!-- This is that tiny icon in the topleft of your browser tab -->
	<link rel="icon" href="favicon.ico">

	<!-- This is your external CSS file for making things pretty -->    
	<link rel="stylesheet" href="css/style.css">

	<script type="text/javascript" src="js/jquery-1.12.0.min.js"></script>

	
	<!-- This is the text that appears on the browser tab -->
	<title>Contact</title>

</head>



<body>

	<header>
		<!-- Global Navigation Menu Bar -->
		<div id="header-wrapper">
			<div id="header" class="container">
				<div id="logo">
					<h1><a href="index.html">Lillyan Pan</a></h1>
				</div>
				<nav class="main-nav">
					<ul>
						<li><a href="index.html">Home</a></li>
						<li><a href="about.html">About</a></li>
						<li><a href="contact.php">Contact</a></li>
						<li><a href="new.php">Art</a></li>
                		<li><a href="fun.php">Fun</a></li>
					</ul>
				</nav>
			</div>
			<div id="banner" class="container"><span>Designing for anyone</span></div>
		</div>
	</header>

	
	<div id="welcome" class="container">
		<!-- Put all the content needed on this page here -->
		<div class="title">
			<h2>Contact</h2>
			<p>Chat with me!</p>
		</div>

<!-- Quick Validation adapted from Shea Belsky -->
		<div class="content">
			<form action="contact.php" method="post">
				Name: <br>
				<input type="text" name="name" placeholder="Type in your name" required value=""> <br>
				E-mail: <br>
				<input type="text" name="email" placeholder="name@email.com" value=""><br>
				Phone: <br>
				<input type="text" name="phone" placeholder="123-456-7890" ><br>
				Message: <br>
				<textarea name="message"></textarea><br>
				<button type="submit" name="submit" class="form-button">Submit Form</button>
			</form>
		</div>

		<?php 
		// For-loop checking if form is all filled
		$required = array('name', 'phone', 'email', 'message');
		$missing = false;
		foreach ($required as $field) {
			if (!isset($_POST[$field])) {
				$missing = true;
			}
		}
		if ($missing) {
			echo "<div class='contentSubmit'>";
			echo "Please fill out all required fields";
			echo "</div>";
		}
		// Validation using regex
		else if (isset($_POST['submit'])) {
			$name = $_POST['name'];
			$email = $_POST['email'];
			$phone = $_POST['phone'];
			$message = $_POST['message'];
			if (!preg_match("#[A-Z a-z-']+#",$name)) {
				echo "<div class='contentSubmit'>";
				echo "Please use letters, apostrophes, and dashes only for your name";
				echo "</div>";
			}
			else if (!preg_match("#[a-z-A-Z\d]+@[a-z.]+\.[a-z]+#",$email)) {
				echo "<div class='contentSubmit'>";
				echo "Please match example@email.com";
				echo "</div>";
			}
			else if (!preg_match("#[0-9]{3}-[0-9]{3}-[0-9]{4}#",$phone)) {
				echo "<div class='contentSubmit'>";
				echo "Please match 123-456-7890";
				echo "</div>";
			}
			else {
				echo "<div class='contentSubmit'>";
				echo "Thank you $name<br>
						Your email address is: $email<br>
						Your message was: $message<br>
						If you want to update your submission, <a href='mailto:panlillyan@gmail.com'>email me</a> the update";
				echo "</div>";
			}
		}
		?>
	</div>
	

	<footer>
		<!-- Tell people that this is my oc website do not steal -->
		<div id="copyright-date">
			Copyright &copy; 2016 Lillyan Pan. All rights reserved.
		</div>
	</footer>

	<!-- Load JS -->
	<script src="main.js"></script>
</body>

</html>