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

    if(isset($_POST['fk_id'])
        && isset($_POST['user_type'])
        && isset($_POST['booking_status'])
    )

    $query = "SELECT tbl_customer.customer_id,tbl_customer.customer_name,tbl_customer.customer_contact,
            tbl_customer.customer_profile_img,
            booking_id,booking_num,booking_datetime,
            booking_fee,booking_description,booking_status FROM `tbl_booking` 
            JOIN tbl_customer ON tbl_customer.customer_id=tbl_booking.fk_customer_id
            WHERE tbl_booking.fk_id=? AND user_type=? AND booking_status=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('iss',$_POST['fk_id'],$_POST['user_type'],$_POST['booking_status']);
    $stmt->execute();
    $stmt->store_result();
    $row = $stmt->num_rows() > 0;
    if ($row == 1) {
        $stmt->bind_result($customer_id,$customer_name, $customer_contact, $customer_profile_img, 
        $booking_id, $booking_num, $booking_datetime,$booking_fee,$booking_description,$booking_status);
        while ($row = $stmt->fetch()) {
            $data = array(
                'customer_id' => $customer_id,
                'customer_name' => $customer_name,
                'customer_contact'=>$customer_contact,
                'customer_profile_img'=>$customer_profile_img,
                'booking_id'=>$booking_id,
                'booking_num'=>$booking_num,
                'booking_datetime'=>$booking_datetime,
                'booking_fee'=>$booking_fee,
                'booking_description' => $booking_description,
                'booking_status' => $booking_status
            );
            array_push($resultSet, $data);
        }
        $response['error'] = false;
        $response['code'] = 200;
        $response['message'] = "Booking Data Successfully";
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
