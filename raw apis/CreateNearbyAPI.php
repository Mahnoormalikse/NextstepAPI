<?php
header("Content-Type: application/json; charset=UTF-8");
include_once '../include/DbConnect.php';
include_once '../operations/DbOperations.php';

$response = array();
$db = new DbConnect();
$con = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (
        isset($_POST['na_title'])
       
    ) {

        $db = new DbOperations();
        $result = $db->CreateNearby($_POST['na_title']);
        if ($result == 1) {
            $response['error'] = false;
            $response['code'] = 200;
            $response['message'] = "nearby area added successfully";
        } else {
            $response['error'] = true;
            $response['code'] = 404;
            $response['message'] = "Failed or already added";
        }
    } else {
        $response['error'] = true;
        $response['code'] = 404;
        $response['message'] = "Required fields Missing";
    }
} else {
    $response['error'] = true;
    $response['code'] = 500;
    $response['message'] = "Invalid Request Method";
}
echo json_encode($response);
