<?php
header("Content-Type: application/json; charset=UTF-8");
include_once '../include/DbConnect.php';
include_once '../operations/DbOperations.php';

$response = array();
$db = new DbConnect();
$con = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (
        isset($_POST['fk_id'])
        && isset($_POST['fk_customer_id'])
        && isset($_POST['user_type'])
        && isset($_POST['booking_fee'])
        && isset($_POST['booking_description'])
    ) {

        $db = new DbOperations();
        $result = $db->CreateBooking(
            $_POST['fk_id'],
            $_POST['fk_customer_id'],
            $_POST['user_type'],
            $_POST['booking_fee'],
            $_POST['booking_description']
        );
        if ($result == 1) {
            $response['error'] = false;
            $response['code'] = 200;
            $response['message'] = "Booking Request send  successfully";
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
