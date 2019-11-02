<?php

//If User is logged in the session['user_logged_in'] will be set to true

//if user is Not Logged in, redirect to login.php page.
if (!isset($_SESSION['user_logged_in'])) {
	header('Location:login.php');
}else if(isset($_SESSION['user_logged_in'])) {
    if (!isset($_SESSION['verified']) OR $_SESSION['verified']!= true) {
        header('Location:verify_user_with_token.php');
    }

    if ($_SESSION['admin_type'] !== 'super' && $_SESSION['admin_type'] !== 'supercashr'
	) {
        $db = getDbInstance();
        $db->where("day", date('Y-m-d'));
        $db->where("day_ended", date(true));

        $row = $db->get('start_and_end_day_controller');
        if ($db->count >=1) {
            echo $db->count;
            header('location:day_not_started.php');
        }
    }


}





