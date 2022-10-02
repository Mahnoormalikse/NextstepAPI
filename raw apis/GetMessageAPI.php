<?php
header("Content-Type: application/json; charset=UTF-8");

include_once '../operations/DbOperations.php';
include_once '../include/DbConnect.php';

$response  = array();
$db = new DbConnect();
$con = $db->connect();

$data = array();
$resultSet = array();
$img = array();

$data1 = array();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $query = "SELECT * FROM `tbl_chat`";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $stmt->store_result();
    $row = $stmt->num_rows() > 0;
    if ($row == 1) {
        $stmt->bind_result(
            $chat_id,
            $chat_message,
            $sender_id,
            $receiver_id,
            $sender_type,
            $chat_datetime
        );
        while ($row = $stmt->fetch()) {
            $data = array(
                'chat_id' => $chat_id,
                'chat_message' => $chat_message,
                'sender_id' => $sender_id,
                'receiver_id' => $receiver_id,
                'sender_type' => $sender_type,
                'chat_datetime' => $chat_datetime
            );
            array_push($resultSet, $data);
        }

        $response['error'] = false;
        $response['code'] = 200;
        $response['message'] = "record found";
        $response['records']   = $resultSet;
    } else if ($row == 0) {
        $response['error'] = true;
        $response['code'] = 404;
        $response['message'] = "Record not found";
    }
} else {
    $response['error'] = true;
    $response['code'] = 404;
    $response['message'] = "Server Error";
}
echo json_encode($response);
