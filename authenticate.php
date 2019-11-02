<?php
require_once './config/config.php';
session_start();

//Check if its an ajax request
function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !is_ajax()) {
	$username = filter_input(INPUT_POST, 'username');
	$passwd = filter_input(INPUT_POST, 'passwd');
	$remember = filter_input(INPUT_POST, 'remember');

	//Get DB instance.
	$db = getDbInstance();

	$db->where("user_name", $username);

	$row = $db->get('admin_accounts');

	if ($db->count >= 1) {

		$db_password = $row[0]['passwd'];
		$user_id = $row[0]['id'];

		if (password_verify($passwd, $db_password)) {

			$_SESSION['user_logged_in'] = TRUE;
			$_SESSION['admin_type'] = $row[0]['admin_type'];
			$_SESSION['username']	= $row[0]['user_name'];
			$_SESSION['id']	= $user_id;

			if ($remember) {

				$series_id = randomString(16);
				$remember_token = getSecureRandomToken(20);
				$encryted_remember_token = password_hash($remember_token,PASSWORD_DEFAULT);

				//$expiry_time = date('Y-m-d H:i:s', strtotime(' + 30 days'));
				$expiry_time = date('Y-m-d H:i:s', strtotime(' + 1 day'));

				$expires = strtotime($expiry_time);

				setcookie('series_id', $series_id, $expires, "/");
				setcookie('remember_token', $remember_token, $expires, "/");

				$db = getDbInstance();
				$db->where ('id',$user_id);

				$update_remember = array(
					'series_id'=> $series_id,
					'remember_token' => $encryted_remember_token,
					'expires' =>$expiry_time
				);
				$db->update("admin_accounts", $update_remember);
			}
			//Authentication successful, wrtte user login activity and store in admin_activity db table
			$_SESSION['login_activity'] = $row[0]['surname'] . ' ' . $row[0]['firstname'] . ' logged in as ' . $row[0]['user_name'] . ' on ' . date('D, M, d, Y h:i:s: A');

			$user_data = array(
				'admin_id'		=> $row[0]['id'],
				'session_id'	=> session_id(),
				'date'			=> date('Y-m-d H:i:s'),
				'activity'		=> $_SESSION['login_activity'],

			);
			$returned_id = $db->insert('admin_activity', $user_data);

			//Store the activity information in a log file
			$file = 'logs/log-general.txt';
			if($handle = fopen($file, 'a')) {
				fwrite($handle,
					"\n" .
					$_SESSION['login_activity'] .
					"\n==========================================================================================================" .
					"\n=========================================================================================================="
				);
				fclose($handle);
			}

			// Redirect user
			header('Location:verify_user_with_token.php');



		} else {
			$_SESSION['login_failure'] = "Invalid user name or password";
			header('Location:login.php');
		}

		exit;
	} else {
		$_SESSION['login_failure'] = "Invalid user name or password";
		header('Location:login.php');
		exit;
	}

} else if (is_ajax()) {
	$username = filter_input(INPUT_POST, 'username');
	$passwd = filter_input(INPUT_POST, 'password');

	//Get DB instance.
	$db = getDbInstance();

	$db->where("user_name", $username);

	$row = $db->get('admin_accounts');

    if ($db->count >= 1) {
		$db_password = $row[0]['passwd'];
		$user_id = $row[0]['id'];

		if (!password_verify($passwd, $db_password)) {
			session_destroy();
			if(isset($_COOKIE['series_id']) && isset($_COOKIE['remember_token'])){
				clearAuthCookie();
			}
			header('Location:login.php');
        }
    } else {
        session_destroy();
		if(isset($_COOKIE['series_id']) && isset($_COOKIE['remember_token'])){
			clearAuthCookie();
		}
		header('Location:login.php');
	}


	if ($_SESSION['admin_type'] !== 'super' && $_SESSION['admin_type'] !== 'supercashr'
	) {
		$db = getDbInstance();
		$db->where("day", date('Y-m-d'));
		$db->where("day_ended", date(true));
		$row = $db->get('start_and_end_day_controller');
		if ($db->count >=1) {
			echo $db->count;
			session_destroy();
			if(isset($_COOKIE['series_id']) && isset($_COOKIE['remember_token'])){
				clearAuthCookie();
			}
			header('Location:login.php');
			exit;
		}
	}

} else {
    die('Method Not allowed');
}