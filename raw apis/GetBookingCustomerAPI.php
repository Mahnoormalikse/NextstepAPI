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
        && isset($_POST['booking_status'])
    )

    $query = "SELECT tbl_mechanic.mechanic_id,tbl_mechanic.mechanic_name,tbl_mechanic.mehanic_contact,
    tbl_mechanic.mechanic_profile_img,
    booking_id,booking_num,booking_datetime,
    booking_fee,booking_description FROM `tbl_booking` 
    JOIN tbl_mechanic ON tbl_mechanic.mechanic_id=tbl_booking.fk_id
    WHERE tbl_booking.fk_customer_id=? AND booking_status=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('is',$_POST['fk_customer_id'],$_POST['booking_status']);
    $stmt->execute();
    $stmt->store_result();
    $row = $stmt->num_rows() > 0;
    if ($row == 1) {
        $stmt->bind_result($mechanic_id,$mechanic_name, $mehanic_contact, $mechanic_profile_img, 
        $booking_id, $booking_num, $booking_datetime,$booking_fee,$booking_description);
        while ($row = $stmt->fetch()) {
            $data = array(
                'mechanic_id' => $mechanic_id,
                'mechanic_name' => $mechanic_name,
                'mehanic_contact'=>$mehanic_contact,
                'mechanic_profile_img'=>$mechanic_profile_img,
                'booking_id'=>$booking_id,
                'booking_num'=>$booking_num,
                'booking_datetime'=>$booking_datetime,
                'booking_fee'=>$booking_fee,
                'booking_description' => $booking_description
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
