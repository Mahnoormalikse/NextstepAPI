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

    $query = "SELECT mechanic_id,mechanic_name,mechanic_address,mechanic_email,
    mechanic_lng,mechanic_lat,mechanic_profile_img,mehanic_contact,mechanic_status,mechanic_datetime,mechanic_cnic,vehicle_type 
    FROM `tbl_mechanic`";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $stmt->store_result();
    $row = $stmt->num_rows() > 0;
    if ($row == 1) {
        $stmt->bind_result($id,$name, $address, $email, $lat, $lng, $address,$profile_pic,$status,$datetime,$cnic,$vtype);
        while ($row = $stmt->fetch()) {
            $data = array(
                'mechanic_id' => $id,
                'mechanic_name' => $name,
                'mechanic_address'=>$address,
                'mechanic_email'=>$email,
                'mechanic_lng'=>$lat,
                'mechanic_lat'=>$lng,
                'mechanic_profile_img'=>$address,
                'mehanic_contact'=>$profile_pic,
                'mechanic_status' => $status,
                'mechanic_datetime'=>$datetime,
                'mechanic_cnic'=>$cnic,
                'vehicle_type'=>$vtype
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