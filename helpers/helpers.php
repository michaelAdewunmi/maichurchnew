<?php
/**
 * Function to generate random string.
 */
function randomString($n) {

	$generated_string = "";

	$domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";

	$len = strlen($domain);

	// Loop to create random string
	for ($i = 0; $i < $n; $i++) {
		// Generate a random index to pick characters
		$index = rand(0, $len - 1);

		// Concatenating the character
		// in resultant string
		$generated_string = $generated_string . $domain[$index];
	}

	return $generated_string;
}


/**
 *
 */
function getSecureRandomToken() {
	$token = bin2hex(openssl_random_pseudo_bytes(16));
	return $token;
}

/**
 * Clear Auth Cookie
 */
function clearAuthCookie() {
	unset($_COOKIE['series_id']);
	unset($_COOKIE['remember_token']);
	setcookie('series_id', null, -1, '/');
	setcookie('remember_token', null, -1, '/');
}
/**
 *
 */
function clean_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

/**
 * Write admin activity to log files
 * (activities include adding & deleting of admin and members)
 */
function save_general_admin_activity_to_log(
    $data_to_store, $user_type, $activity_type, $deleted_member=null,
    $deleted_admin=null, $edited_admin=null, $edited_member=null
) {

    //reset db instance
    $db = getDbInstance();
    $db->where('id', $_SESSION['id']);
    $active_admin_user = $db->getOne("admin_accounts");


    if ($data_to_store['admin_type']==='super') {
        $newUserRole = 'Super Admin';
    } else if ($data_to_store['admin_type']==='supercashr') {
        $newUserRole = 'Super Cashier';
    } else {
        $newUserRole = ucfirst($data_to_store['admin_type']);
    }

    $active_admin_names = $active_admin_user['surname'] . ' ' . $active_admin_user['firstname']
                            . '(' . $active_admin_user['user_name'] . ')';


    if ($user_type==="admin") {
        $new_user_names = $data_to_store['surname'] . ' ' . $data_to_store['firstname']
                            . '(' . $data_to_store['user_name'] . ')';

        if ($activity_type==="add") {

            $activity_to_save = $active_admin_names . ' added a New Person - ' . $new_user_names
                                . ' as ' . $newUserRole . ' on ' . date('D, M, d, Y h:i:s: A');

        } else if ($activity_type==="delete") {

            $deleted_admin_info = $deleted_admin['surname'] . ' ' . $deleted_admin['firstname'];

            $a_or_an = $deleted_admin['admin_type']==="admin" ? 'an ' : 'a ';

            $activity_to_save = $active_admin_names . ' deleted ' . $a_or_an . ucfirst($deleted_admin['admin_type']) .
                                ' (' . $deleted_admin_info . ') with the username ' . $deleted_admin['user_name'] .
                                ' on ' . date('D, M, d, Y h:i:s: A');
        } else if ($activity_type==="edit") {

            $edited_admin_names = $edited_admin['surname'] . ' ' . $edited_admin['firstname'];

            $activity_to_save = "The user " . $edited_admin_names . ' with username - ' . $edited_admin['user_name']
                                . ', was edited by ' . $active_admin_names . '  on ' . date('D, M, d, Y h:i:s: A');
        }
    } else if ($user_type==="member") {
        $new_user_names = $data_to_store['surname'] . ' ' . $data_to_store['othernames'];
        if ($activity_type==="add") {

            $activity_to_save = $active_admin_names . ' added a New Member (' . $new_user_names . ') on ' .
                                date('D, M, d, Y h:i:s: A');

        } else if ($activity_type==="delete") {

            $deleted_member_names = $deleted_member['surname'] . ' ' . $deleted_member['othernames'];

            $activity_to_save = $active_admin_names . ' deleted a Member (' . $deleted_member_names .
                                ')  with the email ' . $deleted_member['email'] .  ' on ' . date('D, M, d, Y h:i:s: A');

        } else if ($activity_type==="edit") {
            $edited_member_names = $edited_member['surname'] . ' ' . $edited_member['othernames'];

            $activity_to_save = "The Member - " . $edited_member_names . ' with email - ' . $edited_member['email']
                                . ', was edited by ' . $active_admin_names . ' on ' . date('D, M, d, Y h:i:s: A');
        }
    }



    $user_data = array(
        'admin_id'     => $_SESSION['id'],
        'session_id'   => session_id(),
        'date'         => date('Y-m-d H:i:s'),
        'activity'     => $activity_to_save
    );
    $returned_id = $db->insert('admin_activity', $user_data);

    //Store the activity information in a log file
    $file = 'logs/log-general.txt';
    if($handle = fopen($file, 'a')) {
        fwrite(
            $handle,
            "\n" .
            $activity_to_save .
            "\n==========================================================================================================" .
            "\n=========================================================================================================="
        );
        fclose($handle);
    }
}

/**
 * Write Tithe activity and information to Log File
 */
function add_posted_tithe_info_to_log($tithe_data, $reversed=false) {
    //reset db instance
    $db = getDbInstance();
    $db->where('id', $_SESSION['id']);
    $active_admin_user = $db->getOne("admin_accounts");

    if ($active_admin_user['admin_type']==='super') {
        $newUserRole = 'Super Admin';
    } else if ($active_admin_user['admin_type']==='supercashr') {
        $newUserRole = 'Super Cashier';
    } else {
        $newUserRole = ucfirst($active_admin_user['admin_type']);
    }



    $active_admin_names = $active_admin_user['surname'] . ' ' . $active_admin_user['firstname']
                            . '(' . $active_admin_user['user_name'] . ')';

    if($reversed===false) {
        $tithe_activity_to_save = "Tithe was COLLECTED from " . $tithe_data['Name_member'] .
        " and posted by " . $active_admin_user["admin_type"] . ", " .
        $active_admin_names . "\n". "The details are shown below:" .
        "\nInvoice Number: " . $tithe_data['invoicenum'] .
        "\nAmount Paid: " . $tithe_data['Amount_Paid'] .
        "\nCollected in: " . $tithe_data['payment_mode'] .
        "\nCollected on: "  . date('D, M, d, Y h:i:s: A');
    } else if (is_array($tithe_data)===true && $reversed==="Reversal Approved") {
        $tithe_activity_to_save  = "Tithe transaction REVERSAL was Approved by ".
        $active_admin_user["admin_type"] . ", " .
        $active_admin_names . "\nThe details are shown below:" .
        "\n*****************".
        "\n OLD DETAILS" .
        "\n*****************".
        "\n Old Tithe Payer Name: " . $tithe_data[0]['Name_member'] .
        "\n Old Invoice Number: " . $tithe_data[0]['invoicenum'] .
        "\n Old Transaction ID: " . $tithe_data[0]['trans_id'] .
        "\n Old Description: " . $tithe_data[0]['payment_description'] .
        "\n Old Amount: " . abs($tithe_data[0]['Amount_Paid']) .
        "\n Paid on: " . $tithe_data[0]['date_received'] .
        "\n*****************".
        "\n NEW DETAILS".
        "\n*****************".
        "\n New Tithe Payer Name: " . $tithe_data[1]['Name_member'] .
        "\n New Invoice Number: " . $tithe_data[1]['invoicenum'] .
        "\n New Transaction ID: " . $tithe_data[1]['trans_id'] .
        "\n New Description: " . $tithe_data[1]['payment_description'] .
        "\n New Amount: " . $tithe_data[1]['Amount_Paid'];
    } else if ($reversed===true) {
        $db = getDbInstance();
        $db->where('invoicenum', $tithe_data);
        $db->where('recusername', $_SESSION['username']);
        $db->where('reversal_status', '1');
        $row = $db->get('tb_payment');

        if ($db->count>=1) {
            $tithe_activity_to_save  = "Tithe transaction REVERSAL was INITIATED by ".
            $active_admin_user["admin_type"] . ", " .
            $active_admin_names . "\nThe details are shown below:" .
            "\nTithe Payer Name: " . $row[0]['Name_member'] .
            "\nInvoice Number: " . $row[0]['invoicenum'] .
            "\nTransaction ID: " . $row[0]['trans_id'] .
            "\nDescription: " . $row[0]['payment_description'] .
            "\nAmount: " . $row[0]['Amount_Paid'] .
            "\nPaid on: " . $row[0]['date_received'] .
            "\nReversed on: "  . date('D, M, d, Y h:i:s: A');
        }
    }

    $file = 'logs/log-tithes.txt';
    if($handle = fopen($file, 'a')) {
        fwrite(
            $handle,
            "\n" .
            $tithe_activity_to_save .
            "\n==========================================================================================================" .
            "\n=========================================================================================================="
        );
        fclose($handle);
    }
}