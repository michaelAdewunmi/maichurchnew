<?php
/**
 * Description File to send an sms but to be queried via javascript ajax
 *
 * @category Cashier_Token_Generator_And_Sender
 * @package  Surulere_Finance_Project
 * @author   Suruler_DevTeam <surulere_devteam@gmail.com>
 * @license  MIT https://github/tunjiup/mainchurch
 * @link     https://github/tunjiup/mainchurch
 */
require_once 'send_sms.php';

if (!isset($_SESSION['username']) OR !isset($_SESSION['user_logged_in'])
    OR $_SESSION['user_logged_in'] != true
) {
    header("Location: login.php");
}

$db = getDbInstance();
$db->where("user_name", $_SESSION['username']);
$row = $db->get('admin_accounts');


if ($db->count >= 1) {
    $admin_name = $row[0]["firstname"];
}

if(isset($_POST['generate_token'])) {
    $db = getDbInstance();
    $db->where("created_for", $_SESSION['username']);
    $row = $db->get('cashiers_login_tokens');

    $token = substr(random_int(100000, 999999), 1);
    $time= time();
    $pretty_time = date('Y-m-d H:i:s');
    $time = time();
    $_SESSION['time'] = $time;
    if ($db->count>=1) {

        $data = Array (
            "token"            => $token,
            "date_created_raw"              => $time,
            "date_created_pretty"           => $pretty_time,
            "created_for"       => $_SESSION['username']
        );

        $db = getDbInstance();
        $db->where("created_for", $_SESSION['username']);
        $result = $db->update("cashiers_login_tokens", $data);
        send_token_to_phone_and_email();
    } else {
        $data = Array (
            "token"                 => $token,
            "date_created_raw"      => $time,
            "date_created_pretty"   => $pretty_time,
            "created_for"           => $_SESSION['username']
        );

        $db = getDbInstance();
        $result = $db->insert("cashiers_login_tokens", $data);
        send_token_to_phone_and_email();
    }
}

function send_token_to_phone_and_email() {
    $db = getDbInstance();
    $db->where("created_for", $_SESSION['username']);
    $row = $db->get('cashiers_login_tokens');
    if ($db->count>=1) {
        $the_token = $row[0]['token'];
        $db = getDbInstance();
        $db->where("user_name", $_SESSION['username']);
        $row = $db->get('admin_accounts');

        $the_message = 'Hello ' . $row[0]['firstname'] . ", \n\n" . 'Your Login Token is ' . $the_token;

        if ($db->count>=1) {

            $the_user = $row[0];
            // send_sms_to_phone('cashier_token', $the_user['phone'], $the_message);
            // mail(
            //     $the_user['email'],
            //     'Surulere Treasury Verification Token!',
            //     $the_message
            // );
            $_SESSION['dtkn'] = $the_token;
            if (isset($_SESSION['dtkn'])) {
                echo $_SESSION['dtkn'];
            }
        }
        $_SESSION['token_generated'] = true;
        $_SESSION['token_btn'] = "hide"; // Hide the generate-token button
    }
}

if (isset($_POST['retry'])) {
    $_SESSION['token_btn'] = "hide";
}

if (isset($_POST['user-token']) && isset($_POST['submit']) && trim($_POST['user-token']) !=="") {

    $db = getDbInstance();
    $db->where("created_for", $_SESSION['username']);
    $db->where("token", (string)$_POST['user-token']);
    $row = $db->get('cashiers_login_tokens');


    if ($db->count>=1) {
        $token_created = $row[0]['token'];
        $time_created = $row[0]['date_created_raw'];
        $validity_period = time() - (int)$time_created;

        if ($validity_period<120) {
            $_SESSION['token_val_info'] = "Yeah! Token is Valid";
            $_SESSION['verified'] = true;
            //echo BASE_PATH;
            include_once BASE_PATH."/day_start.php";
        } else {
            $_SESSION['token_val_info'] = "Sorry! Token Expired. Please Generate a different Token!";
            $_SESSION['token_btn'] = "show";
        }

    } else {
        $_SESSION['token_val_info'] = "Sorry! Token is an Invalid Token!";
        $_SESSION['token_btn'] = "show";
    }
}

function setSessionAndGoToIndexPage($day_ended=false) {
    $db = getDbInstance();
    $db->where("day", date('Y-m-d'));
    $db->where("day_started", true);
    $db->where("day_started_for", $_SESSION['username']);
    $row = $db->get('start_and_end_day_controller');
    $_SESSION['present_day_ongoing'] = !$day_ended ? true : "true(But Ended)";
    $_SESSION['day_id'] = !$day_ended ? $row[0]['day_id'] : $row[0]['day_id']."(Ended)";
    $day_ended==false ? header("Location: index.php") : header("Location: index.php?dayended=true");
}

function saveInfoToStartDayController($the_day_id) {
    $db = getDbInstance();
    $data = Array (
        "day"               => date('Y-m-d'),
        "day_started"       => true,
        "day_id"            => $the_day_id,
        "time_day_started"  => date('Y-m-d H:i:s'),
        "day_started_for"   => $_SESSION["username"],
        "day_ended"         => "NOT YET",
        "day_ended_for"     => "NOT ENDED YET",
        "time_day_ended"    => "NULL",
    );
    $result = $db->insert("start_and_end_day_controller", $data);
    if ($result) {
        return $result;
    } else {
        return null;
    }
}
?>