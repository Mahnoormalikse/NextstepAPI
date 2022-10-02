<?php
header("Content-Type: application/json; charset=UTF-8");
include_once '../include/DbConnect.php';
include_once '../operations/DbOperations.php';

$response = array();
$db = new DbConnect();
$con = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (
        isset($_POST['customer_email'])
        && isset($_POST['customer_password'])
    ) {

        $db = new DbOperations();
        $result = $db->CheckLogin($_POST['customer_email'], $_POST['customer_password']);
        if ($result == 1) {
            $query = "SELECT customer_id,customer_name,customer_email,customer_contact,customer_profile_img,
            customer_status 
            FROM `tbl_customer` WHERE customer_email=? ";
            $stmt = $con->prepare($query);
            $stmt->bind_param('s', $_POST['customer_email']);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result(
                $customer_id,
                $customer_name,
                $customer_email,
                $customer_contact,
                $customer_profile_img,
                $customer_status
            );
            while ($row = $stmt->fetch()) {
                $response['customer_id'] =  $customer_id;
                $response['customer_name'] =  $customer_name;
                $response['customer_email'] =  $customer_email;
                $response['customer_contact'] =  $customer_contact;
                $response['customer_profile_img']= $customer_profile_img;
                $response['customer_status'] =$customer_status;
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
