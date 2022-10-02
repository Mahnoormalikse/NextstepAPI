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

    $query = "SELECT 	wmechanic_id,wmechanic_name,wmechanic_address,wmechanic_email,wmechanic_lng,
    wmechanic_lat,wmechanic_profile_img,wmechanic_contact,wmechanic_status,wmechanic_datetime,wmechanic_cnic,vehicle_type
    FROM `tb_workshop`";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $stmt->store_result();
    $row = $stmt->num_rows() > 0;
    if ($row == 1) {
        $stmt->bind_result($id,$name, $address, $email, $lng, $lat,$profile_pic,$contact,$status,$datetime,$cnic,$vtype);
        while ($row = $stmt->fetch()) {
            $data = array(
                'wmechanic_id' => $id,
                'wmechanic_name' => $name,
                'wmechanic_address'=>$address,
                'wmechanic_email'=>$email,
                'wmechanic_lng'=>$lng,
                'wmechanic_lat'=>$lat,
                'wmechanic_profile_img'=>$profile_pic,
                'wmechanic_contact'=>$contact,
                'wmechanic_status' => $status,
                'wmechanic_datetime'=>$datetime,
                'wmechanic_cnic'=>$cnic,
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