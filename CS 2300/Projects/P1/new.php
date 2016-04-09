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
    <title>Lillyan Pan</title>
    
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
        <div class="title">
            <h2>Art</h2>
            <p>Some art trivia <br>
            <cite>Images from: jackson-pollock.org, static1.squarespace.com, artofcollage.files.wordpress.com,
            and s-media-cache-ak0.pinimg.com <br> Facts from: mysterybuild.com</cite> </p>
        </div>
        
        <div class="container">
            Art History Trivia! Select below which art history fact to look at.<br>
            <form method="POST">
                <select name="artType">
                    <option value="Select">--Select--</option>
                    <option value="Jackson Pollock">Jackson Pollock</option>
                    <option value="Leonardo Da Vinci">Leonardo Da Vinci</option>
                    <option value="Henri Matisse">Henri Matisse</option>
                    <option value="Pablo Picasso">Pablo Picasso</option>
                </select>
                <input type="submit" name="submit" value="Submit" />
            </form>
        </div>

        <?php
            $art = array(
                "Jackson Pollock" => "The artist once had a job cleaning statues for the 
                            Emergency Relief Bureau. He also worked as a janitor with his 
                            brother at a children's school.",
                "Leonardo Da Vinci" => "Da Vinci was left handed and his personal notes were written 
                                        from the right side of the page to the left.",
                "Henri Matisse" => "On December 3rd, 1961, Henri Matisse's painting \"Le Bateau\" was put 
                            right-side up after hanging upside-down for 46 days without anyone 
                            noticing at the Museum of Modern Art in New York.",
                "Pablo Picasso" => "Picasso was considered a suspect in the theft of Mona Lisa in 1911. He was arrested
                             and questioned, but later cleared and released."
            );

            function printName($artType, $artFact){
                # Use the content for this function from the above foreach loop, but NOT the actual loop.
                # You will loop through each array element below, and execute the function for each array element
                # in the context of the foreach loop.
                echo "<div class='art-container'>";
                if ($artType == "Jackson Pollock") {
                    $img = "assets/JP.jpg";
                }
                else if ($artType == "Leonardo Da Vinci") {
                    $img = "assets/LDV.jpg";
                }
                else if ($artType == "Henri Matisse") {
                    $img = "assets/HM.jpg";
                }
                else if ($artType == "Pablo Picasso") {
                    $img = "assets/PP.jpg";
                }
                else {
                    echo "<h3>Please select a fact!</h3>";
                }
                echo "$artType's fact is: $artFact <img src='$img' class='image' alt='$artFact Logo' />";
                echo "</div>";
            }

            // Print out specific art fact
            if (isset($_POST['submit']) && $_POST['submit'] != "Select") {
                $artType = $_POST["artType"];
                if(isset($artType)) {
                    $artFact = $art[$artType];
                    printName($artType, $artFact);
                }
                else {
                    echo "<h3>Please select a fact!</h3>";
                }
            }
        ?>
        </div>
        <div class="container">
            Do you want to see all of the trivia?<br>
            <form method="POST">
                <select name="Y/N">
                    <option value="Select">--Select--</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <input type="submit" name="submit2" value="Submit" />
            </form>

        </div>

        <?php

            if (isset($_POST['submit2']) && $_POST['submit2'] != "Select") {
                $yes_no_selector = $_POST["Y/N"];
                if ($yes_no_selector == "Yes") {
                    foreach ($art as $artType => $artFact) {
                        printName($artType, $artFact);
                    }
                }
            }  
        ?>

    <footer>
        <!-- Tell people that this is my oc website do not steal -->
        <div id="copyright-date">
            Copyright &copy; 2016 Lillyan Pan. All rights reserved.
        </div>
    </footer>
    <script src="js/main.js"></script>
    </body>
</html>