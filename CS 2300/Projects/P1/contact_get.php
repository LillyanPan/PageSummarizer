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
			<p>Talk to me!</p>
		</div>

		<div class="contentSubmit">
			Thank you <?php echo $_GET["name"]; ?><br>
			Your email address is: <?php echo $_GET["email"]; ?><br>
			Your message was: <?php echo $_GET["message"]; ?><br>
			If you want to update your submission, <a href="mailto:panlillyan@gmail.com">email me</a> the update.
		</div>
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