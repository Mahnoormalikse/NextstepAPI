<?php
header("Content-Type: application/json; charset=UTF-8");

include_once '../operations/DbOperations.php';
include_once '../include/DbConnect.php';

$response  = array();
$db = new DbConnect();
$con = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (
        isset($_POST['chat_message'])
        && isset($_POST['sender_id'])
        && isset($_POST['receiver_id'])
        && isset($_POST['sender_type'])
    ) {
        $db = new DbOperations();
        $result = $db->SendMessage(($_POST['chat_message']),
            ($_POST['sender_id']),
            ($_POST['receiver_id']),
            $_POST['sender_type']
        );
        if ($result == 1) {
            $response['error'] = false;
            $response['code'] = 200;
            $response['message'] = "Message Sent";
        } else if ($result == 0) {
            $response['error'] = true;
            $response['code'] = 404;
            $response['message'] = "Message not sent";
        }
    } else {
        $response['error'] = true;
        $response['code'] = 404;
        $response['message'] = "Required Field Missing";
    }
} else {
    $response['error'] = true;
    $response['code'] = 404;
    $response['message'] = "Server Error";
}
echo json_encode($response);
