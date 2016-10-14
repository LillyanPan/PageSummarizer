<!DOCTYPE html>
<html>

<head>

    <!-- This is the character encoding of my website -->
	<meta charset="UTF-8">
    
    <!-- This tells search engines what the website is about -->
	<meta name="description" content="Hello Google user! This is Natalie's HTML5 website">
	
    <!-- This is that tiny icon in the topleft of your browser tab -->
    <link rel="icon" href="favicon.ico">
    
    <!-- This is your external CSS file for making things pretty -->   
    <!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" href="css/style.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script> 
	
    <!-- This is the text that appears on the browser tab -->
	<title>BioInfo 4831 Site</title>

	<style type="text/css">
	header .intro-text .skills {
	  font-size: 1.25em;
	  font-weight: 300;
	}
	.navbar {
	  font-family: Helvetica, Arial, sans-serif;
	  text-transform: uppercase;
	  font-weight: 700;
	}
	.navbar a:focus {
	  outline: none;
	}
	.navbar .navbar-nav {
	  letter-spacing: 1px;
	}
	.navbar .navbar-nav li a:focus {
	  outline: none;
	}
	.navbar-default,
	.navbar-inverse {
	  border: none;
	}
	header {
	  text-align: center;
	  background: #18bc9c;
	  color: white;
	}
	header .container {
	  padding-top: 100px;
	  padding-bottom: 50px;
	}
	header img {
	  display: block;
	  margin: 0 auto 20px;
	}
	header .intro-text .name {
	  display: block;
	  font-family: "Montserrat", "Helvetica Neue", Helvetica, Arial, sans-serif;
	  text-transform: uppercase;
	  font-weight: 700;
	  font-size: 2em;
	}
	header .intro-text .skills {
	  font-size: 1.25em;
	  font-weight: 300;
	}
	</style>
    
</head>



<body>
	<!-- Global Navigation Menu Bar -->
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li class="page-scroll">
                        <a href="#home">Home</a>
                    </li>
                    <li class="page-scroll">
                        <a href="#about">About</a>
                    </li>
                    <li class="page-scroll">
                        <a href="#output">Output</a>
                    </li>
                </ul>
            </div>
        </div>
	</nav>

	<header>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <img class="img-responsive" src="" alt="">
                    <div class="intro-text">
                        <span class="name">BioInformatics Page</span>
                        <hr class="star-light">
                        <span class="skills">Web Developer - Graphic Artist - User Experience Designer</span>
                    </div>
                </div>
            </div>
        </div>
	</header>
 	<div class="form-content">
		<!-- <form action="functions.php" method="post"> -->
		<form action="#" method="post" enctype="multipart/form-data">
			Gene Name <br>
			<input type="text" name="title"> <br>
			??? <br>
			<textarea name="caption"></textarea><br>
			<button type="submit" name="submit" class="form-button">Submit</button>
		</form>
	
    
	<footer>
        <!-- Tell people that this is my oc website do not steal -->
        <div id="copyright-date">
            Copyright &copy; 2015 Natalie Diebold. All rights reserved.
        </div>
	</footer>
    
</body>

</html>