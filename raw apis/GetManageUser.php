<?php
header("Content-Type: application/json; charset=UTF-8");

include_once '../include/DbConnect.php';
include_once '../operations/DbOperations.php';

$response = array();
$db = new DbConnect();
$con = $db->connect();
$data = array();
$resultSet = array();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $query = "SELECT customer_id,customer_name,customer_email,customer_contact,
    customer_lat,customer_lng,customer_address,customer_profile_img,customer_status,created_datetime 
    FROM `tbl_customer`";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $stmt->store_result();
    $row = $stmt->num_rows() > 0;
    if ($row == 1) {
        $stmt->bind_result($id,$name, $email, $contact, $lat, $lng, $address,$profile_pic,$customer_status,$created_datetime);
        while ($row = $stmt->fetch()) {
            $data = array(
                'customer_id' => $id,
                'customer_name' => $name,
                'customer_email'=>$email,
                'customer_contact'=>$contact,
                'customer_lat'=>$lat,
                'customer_lng'=>$lng,
                'customer_address'=>$address,
                'customer_profile_img'=>$profile_pic,
                'customer_status' => $customer_status,
                'created_datetime'=>$created_datetime
            );
            array_push($resultSet, $data);
        }
        $response['error'] = false;
        $response['code'] = 200;
        $response['message'] = "Data Fetch Successfully";
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