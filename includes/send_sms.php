<?php
/**
 * Description File to send an sms but to be queried via javascript ajax
 *
 * @category Cashier_Token_Sms_Sender
 * @package  Surulere_Finance_Project
 * @author   Suruler_DevTeam <surulere_devteam@gmail.com>
 * @license  MIT https://github/tunjiup/mainchurch
 * @link     https://github/tunjiup/mainchurch
 */
define('__ROOT__', dirname(dirname(__FILE__)));
require_once __ROOT__ . '/lib/unirest-php/src/Unirest.php';
use Unirest\Request\Body;

//Allows unirest work when using localhost (i.e without ssl certificate)
Unirest\Request::verifyPeer(false);


function send_sms_to_phone($with, $sendto, $message) {
    /* Variables with the values to be sent. */
    $owneremail="shogzytol@aol.com";
    $sessionid= $with==='cashier_token' ? '05bd26bf-88ce-44ad-ad32-c204e51820a7' : '0f9c8e43-5ddc-495d-8713-389ad03bf3b6';
    $subacct= $with==='cashier_token' ? "TreasuryToken" : 'MainChurchApp';
    $subacctpwd= $with==='cashier_token' ? "TreasuryToken" : 'MainChurchApp';
    $sender= $with==='cashier_token' ? "TreasToken" : 'ChurchTreas';   /* sender id */


    $url = "http://www.smslive247.com/http/index.aspx?"
    . "cmd=sendmsg"
    . "&sessionid=" . UrlEncode($sessionid)
    . "&message=" . UrlEncode($message)
    . "&sender=" . UrlEncode($sender)
    . "&sendto=" . UrlEncode($sendto)
    . "&msgtype=0";

    $headers = array('Content-Type' => 'application/json');

    //Make `GET` request to smslive247 and handle response with unirest
    if ( $sessionid !== '') {
        $response = Unirest\Request::get($url, $headers);
    }

    $_SESSION['sms-resp'] = $response;
}


?>