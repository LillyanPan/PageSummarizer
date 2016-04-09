<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>INFO / CS 2300 Section 2</title>
        <style type="text/css">
            html, body {
                margin: 0;
                padding: 0;
            }
            html {
                background-color: black;
            }
            body {
                background-color: #ecce89;
                font-family: 'Segoe UI', Frutiger, 'Frutiger Linotype', 'Dejavu Sans', 'Helvetica Neue', Arial, sans-serif;
                margin: auto;
                padding: 10px;
                width: 80%;
            }

            #super-bowl-img {
                display: block;
                margin: 0 auto;
            }

            h1, #super-bowl-img-src {
                text-align: center;
            }
            .player-info {
                align-items: center;
                display: flex;
                height: 70px;
            }
            .team-logo {
                height: 70px;
                width: 70px;
            }
            a {
                color: deepskyblue;
            }
        </style>
    </head>
    <body>
        <h1>PHP SuperBowl</h1>
        <img src="https://mgtvwbtw.files.wordpress.com/2016/01/super-bowl-50-fs.png?w=650" id="super-bowl-img" />
        <div id="super-bowl-img-src">
            <a href="http://wbtw.com/2016/01/25/poll-who-will-win-super-bowl-50/">Image Source</a>
        </div>
        <p>Welcome to the greatest PHP review there ever was. In honor of this weekend's game, put the team on yo back and fill out the missing (or incomplete) PHP code to fix the form and display lists of the players on each team.</p>
        <!-- Self submitting form - no 'action' attribute needed -->
        <form method="POST">
            If you wish to add a player to the Panthers roster, select "Carolina Panthers" from the below menu.  If you wish to add a player to the Broncos roster, select "Denver Broncos".<br>
            <input type="text" name="newPlayer" />
            <select name="newTeam">
                <option value="Carolina Panthers">Carolina Panthers</option>
                <option value="Denver Broncos">Denver Broncos</option>
            </select>
            <input type="submit" name="submit" value="Add Player" />
        </form>

        <?php
            # The current associative array of players; don't edit
            $players = array(
                "Andre Caldwell" => "Denver Broncos",
                "Kawann Short" => "Carolina Panthers",
                "Peyton Manning" => "Denver Broncos",
                "Cam Newton" => "Carolina Panthers",
                "Demaryius Thomas" => "Denver Broncos",
                "Greg Olsen" => "Carolina Panthers"
            );
            # First down: Fix the following 'if' statement so that when submit
            # has been clicked, you get and store the POST values. What PHP
            # function do you know that does this?
            /*
            * ===============================================================================
            * EDIT BELOW THIS LINE ONLY; UNCOMMENT EACH SECTION SO THAT IT FUNCTIONS PROPERLY
            * ===============================================================================
            */
            
            if(isset($_POST['Submit'])) {
                # Uncomment when you have fixed if-statement above
                # Next week, we’ll start using something more secure than $_POST but OK for today
                $newPlayer = $_POST["newPlayer"];
                $newTeam = $_POST["newTeam"];
                # Second down: if newPlayer and newTeam are set and newPlayer matches the regular expression, then add it to the array to be printed
                if(isset($newPlayer) && isset($newTeam) && preg_match("[a-zA-Z ]", $newPlayer)) {
                    # For the last preg_match() function, allow only letters (uppercase or lowercase) or spaces
                    # Remember that there are two arguments for preg_match: the pattern and the string to test
                    # Uncomment the below line when ready, and add the new player to the $players array
                    $players[$newPlayer] = $newTeam;
                }
                else {
                    echo "<h3>Please enter a valid player name for it to be added!</h3>";
                }
            }
        ?>


        <h3>Print the list of players using a foreach loop</h3>

        <?php
            // Third and goal: Fill in the foreach parameters and the if statement, then uncomment this block
            foreach($players as $playerName => $playerTeam) {
                # Print out the key and value, as in "Peyton Manning plays for the Denver Broncos".
                # We've gotten you started by adding the div and appropriate class
                # Fix the if statement so that the appropriate logo prints.
                # (Looking at the next line you'll realize that the 'if' should check if the team is Denver.)
                echo "<div class='player-info'>";
                if ($playerTeam == "Denver Broncos") {
                    $img = "http://prod.static.broncos.clubs.nfl.com/nfl-assets/img/gbl-ico-team/DEN/logos/home/large.png";
                }
                else {
                    $img = "http://prod.static.panthers.clubs.nfl.com/nfl-assets/img/gbl-ico-team/CAR/logos/home/large.png";
                }
                echo "$playerName plays for the $playerTeam: <img src='$img' class='team-logo' alt='$playerTeam Logo' />";
                echo "</div>";
            }
            
            # Touchdown! If there's time left, go for the two-point conversion.
        ?>


        <h3>Recreate the above using a function</h3>

        <?php
            # Two point conversion: define function here -- there should be two arguments.
            function printName($playerName, $playerTeam){
                # Use the content for this function from the above foreach loop, but NOT the actual loop.
                # You will loop through each array element below, and execute the function for each array element
                # in the context of the foreach loop.
                echo "<div class='player-info'>";
                if ($playerTeam == "Denver Broncos") {
                    $img = "http://prod.static.broncos.clubs.nfl.com/nfl-assets/img/gbl-ico-team/DEN/logos/home/large.png";
                }
                else {
                    $img = "http://prod.static.panthers.clubs.nfl.com/nfl-assets/img/gbl-ico-team/CAR/logos/home/large.png";
                }
                echo "$playerName plays for the $playerTeam: <img src='$img' class='team-logo' alt='$playerTeam Logo' />";
                echo "</div>";
            }


            # Call the function using a foreach (similar setup to before) loop
            
            foreach ($players as $playerName => $playerTeam) {
                printName($playerName, $playerTeam);
            }
            
            # Great job! You scored a TD and punched the two-point conversion in. Enjoy the Superbowl this weekend!
        ?>
        <h1><a href="http://www.cc.com/video-clips/qs3r6w/the-colbert-report-logo-restrictions-for-the-super-bowl">Have fun at the Superb Owl!</a></h1>
    </body>
</html>