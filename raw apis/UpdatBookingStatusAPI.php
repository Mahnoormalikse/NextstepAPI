<?php
header("Content-Type: application/json; charset=UTF-8");
include_once '../include/DbConnect.php';
include_once '../operations/DbOperations.php';

$response = array();
$db = new DbConnect();
$con = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (
        isset($_POST['booking_id'])
        && isset($_POST['booking_status'])
        && isset($_POST['payment_amount'])
    ) {

        $sql = "UPDATE tbl_booking SET booking_status=? WHERE tbl_booking.booking_id=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('si', $_POST['booking_status'], $_POST['booking_id']);
        if ($stmt->execute()) {
            $sql1 = "INSERT INTO tbl_payment (fk_booking_id,payment_amount) 
                    VALUES (?,?)";
            $stmt = $con->prepare($sql1);
            $stmt->bind_param('ii', $_POST['booking_id'], $_POST['payment_amount']);
            if ($stmt->execute()) {
                $response['error'] = false;
                $response['code'] = 200;
                $response['message'] = "Booking Status Updated";
            } else {
                $response['error'] = true;
                $response['code'] = 404;
                $response['message'] = "Something went wrong.. Try Again";
            }
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
