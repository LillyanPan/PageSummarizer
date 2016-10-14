<!DOCTYPE html>
<html>

<head>

    <!-- This is the character encoding of my website -->
	<meta charset="<?php print($encoding); ?>">
    
    <!-- This tells search engines what the website is about -->
	<meta name="description" content="<?php print($desc); ?>">
	
    <!-- This is that tiny icon in the topleft of your browser tab -->
    <link rel="icon" href="<?php print($favicon); ?>">
    
    <!-- These are your CSS files for making things pretty -->
    <?php
        foreach($css_files as $file) {
            print("<link rel=\"stylesheet\" href=\"{$file}\">");
        }
    ?>
	
    <!-- This is the text that appears on the browser tab -->
	<title><?php print($title); ?></title>
    
</head>


<body>

	<header>
		<!-- Global Navigation Menu Bar -->
		<nav>
			<ul>
                <?php
                    foreach($nav_links as $name => $link) {
                        print("<li><a href=\"{$link}\">{$name}</a></li>");
                    }
                ?>
			</ul>
		</nav>
	</header>
    
	
	<div id="page-content">
        <!-- Put all the content needed on this page here -->
        <?php print($page_content); ?>
    </div>
	
    
	<footer>
        <!-- Tell people that this is my oc website do not steal -->
        <div id="copyright-date">
            Copyright &copy; <?php print($copy_date." ".$author); ?>. All rights reserved.
        </div>
	</footer>
    
</body>

</html>