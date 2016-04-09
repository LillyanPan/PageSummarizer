<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>INFO / CS 2300 Section 3</title>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
        <link href='https://info2300.coecis.cornell.edu/users/_demosp16/www/section03/css/style.css' rel='stylesheet' type='text/css'>
    </head>
<body>

<?php
  $delimiter = '|'; //set this in one place, don't hardcode it everywhere!

  //1. Create a txt file called "votes.txt" and change the permissions so you can read and write to it.

  //2. Change "true" to check if a vote was cast and the name field is not empty.
  if(true){

    //3. open the votes.txt file for appending data. 
    //$file = ;     

    //Uncommented the following three lines when you finish 2.
    //if (!$file) {
    //  die("There was a problem opening the votes.txt file");
    //}
    
    //4. get the voter's name and candidate choice
    
    //5. write the voter's name and candidate choice to the file. Use the format "name supports candidate\n"

    //6. close the text file

  }
  
?>
	<p><a href = "http://bit.ly/1nYuQtp" target="_blank">http://bit.ly/1nYuQtp</a></p>
  <a href = "http://bit.ly/1nYuQtp" target="_blank"><img src = "https://info2300.coecis.cornell.edu/users/_demosp16/www/section03/img/donkey-and-elephant.jpg" alt="Donkey and Elephant" width="200px" style="float:right"></a> 
  <h1>Info 2300: Presidential Poll</h1>
  
  <h3>Cast your vote!</h3>
  
  <form action="poll.php" method="post">
    What is your name? <br> <input type="text" name="name" required /> <br>
    Select a candidate! <br>
    <select name="candidate">
    <?php 

    $candidates = $array = array(
        "Hillary Clinton" => "Democrat",
        "Bernie Sanders" => "Democrat",
        "Jeb Bush" => "Republican",
        "Ben Carson" => "Republican",
        "Chris Christie" => "Republican",
        "Ted Cruz" => "Republican",
        "Carly Fiorina" => "Republican",
        "Jim Gilmore" => "Republican",
        "John Kasich" => "Republican",
        "Marco Rubio" => "Republican",
        "Donald J. Trump" => "Republican",
    );

    foreach($candidates as $candidate => $party) {
      echo "<option value = \"$candidate\">$candidate</option>";
    }

    ?>
    </select><br><br>
    <input type="submit" name="submit" value="Save" />
  </form>
  
  <h3>Polling Results</h3>
  
  <ol>
  <?php
    //7. Get the contents of the text file as an array

    //8. Use for each to loop through each voter
   
    //9. Print the html for each voter as a <li>. There is two classes for <li> in the CSS in a separate folder..
    //Choose the 'democrat' class for Bernie Sanders or Hillary Clinton and the 'republican' class for the rest.
    //(Hint: use PHP explode() function as well as the trim() function to remove unwanted white space)

    //10. Bonus Activity
    //Think of a way to display the results of the poll to see which candidate in each party has collected the most votes.
  ?>
  </ol>
</body>
 
</html>
