<?php
header("Content-Type: application/json; charset=UTF-8");
include_once '../include/DbConnect.php';
include_once '../operations/DbOperations.php';

$response = array();
$db = new DbConnect();
$con = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (
        isset($_POST['mechanic_email'])
        && isset($_POST['mechanic_password'])
       
    ) {

        $db = new DbOperations();
        $result = $db->CheckLoginMechanic($_POST['mechanic_email'], $_POST['mechanic_password']);
        if ($result == 1) {
            $query = "SELECT mechanic_id,mechanic_name,mechanic_profile_img,mehanic_contact,
            mechanic_status,
            mechanic_cnic 
            FROM `tbl_mechanic` WHERE mechanic_email=? ";
            $stmt = $con->prepare($query);
            $stmt->bind_param('s', $_POST['mechanic_email']);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result(
                $mechanic_id,
                $mechanic_name,
                $mechanic_profile_img,
                $mehanic_contact,
                $mechanic_status,
                $mechanic_cnic
            );
            while ($row = $stmt->fetch()) {
                $response['mechanic_id'] =  $mechanic_id;
                $response['mechanic_name'] =  $mechanic_name;
                $response['mechanic_profile_img'] =  $mechanic_profile_img;
                $response['mehanic_contact'] =  $mehanic_contact;
                $response['mechanic_status']= $mechanic_status;
                $response['mechanic_cnic'] =$mechanic_cnic;
            }

            $response['error'] = false;
            $response['code'] = 200;
            $response['message'] = "Login Successfull";
        } else {
            $response['error'] = true;
            $response['code'] = 404;
            $response['message'] = "Login Failed";
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
