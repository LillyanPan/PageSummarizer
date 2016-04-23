<?php 
		// For-loop checking if form is all filled
		$required = array('name', 'phone', 'email', 'message');
		$missing = false;
		foreach ($required as $field) {
			if (isset($_POST[$field])) {
				$missing = true;
			}
		}
		if ($missing) {
			echo "<div class='container'>";
			echo "Please fill out all required fields";
			echo "</div>";
		}
		// Validation using regex
		else if (isset($_POST['submit'])) {
			$name = $_POST['name'];
			$email = $_POST['email'];
			$phone = $_POST['phone'];
			if (!preg_match("[A-Z a-z-']*",$name)) {
				echo "<div class='container'>";
				echo "Please use letters, apostrophes, and dashes only for your name";
				echo "</div>";
			}
			else if (!preg_match("[a-z-A-Z\d]+@[a-z.]+\.[a-z]+",$email)) {
				echo "<div class='container'>";
				echo "Please match example@email.com";
				echo "</div>";
			}
			else if (!preg_match("[0-9]{3}-[0-9]{3}-[0-9]{4}",$phone)) {
				echo "<div class='container'>";
				echo "Please match 123-456-7890";
				echo "</div>";
			}
		}
		if (isset($_POST['submit'])) {
			echo "div class='contentSubmit'>
			Thank you $name;<br>
			Your email address is: $email;<br>
			Your message was: $message;<br>
			If you want to update your submission, <a href='mailto:panlillyan@gmail.com'>email me</a> the update.
			</div>";
		}
		?>