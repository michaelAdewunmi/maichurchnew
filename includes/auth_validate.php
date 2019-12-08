<?php

//If User is logged in the session['user_logged_in'] will be set to true

//if user is Not Logged in, redirect to login.php page.
if (!isset($_SESSION['user_logged_in'])) {
	header('Location:login.php');
}else if(isset($_SESSION['user_logged_in'])) {
    if (!isset($_SESSION['verified']) OR $_SESSION['verified']!= true) {
        header('Location:verify_user_with_token.php');
    }
}





