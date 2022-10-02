<?php
header("Content-Type: application/json; charset=UTF-8");

include_once '../include/DbConnect.php';
include_once '../operations/DbOperations.php';

$response = array();
$db = new DbConnect();
$con = $db->connect();
$data = array();
$resultSet = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['fk_customer_id'])
    )

    $query = "SELECT booking_id,fk_id,user_type FROM `tbl_booking` WHERE tbl_booking.booking_id NOT IN (SELECT tbl_rate_feedback.rf_id 
    FROM tbl_rate_feedback) AND tbl_booking.booking_status='C' AND tbl_booking.fk_customer_id=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i',$_POST['fk_customer_id']);
    $stmt->execute();
    $stmt->store_result();
    $row = $stmt->num_rows() > 0;
    if ($row == 1) {
        $stmt->bind_result($booking_id,$fk_id, $user_type);
        while ($row = $stmt->fetch()) {
            $data = array(
                'booking_id' => $booking_id,
                'fk_id' => $fk_id,
                'user_type'=>$user_type
            );
            array_push($resultSet, $data);
        }
        $response['error'] = false;
        $response['code'] = 200;
        $response['message'] = "Rating For Bookings";
        $response['records'] = $resultSet;
    } else {
        $response['error'] = true;
        $response['code'] = 404;
        $response['message'] = "Something went wrong";
    }
} else {
    $response['error'] = true;
    $response['code'] = 500;
    $response['message'] = "Invalid Request Method";
}
echo json_encode($response);
