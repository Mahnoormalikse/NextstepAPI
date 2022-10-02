<?php
header("Content-Type: application/json; charset=UTF-8");
include_once '../include/DbConnect.php';
include_once '../operations/DbOperations.php';

$response = array();
$db = new DbConnect();
$con = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (
        isset($_POST['service_name'])
        && isset($_POST['service_price'])
        && isset($_POST['service_discount'])
        && isset($_POST['fk_id'])
        && isset($_POST['user_type'])
    ) {

        $db = new DbOperations();
        $result = $db->CreateService(
            $_POST['service_name'],
            $_POST['service_price'],
            $_POST['service_discount'],
            $_POST['fk_id'],
            $_POST['user_type']
        );
        if ($result == 1) {
            $response['error'] = false;
            $response['code'] = 200;
            $response['message'] = "Service Added successfully";
        } else {
            $response['error'] = true;
            $response['code'] = 404;
            $response['message'] = "Failed and Try Again";
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
