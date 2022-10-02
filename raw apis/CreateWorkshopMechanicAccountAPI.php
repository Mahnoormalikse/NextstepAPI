<?php
header("Content-Type: application/json; charset=UTF-8");

$upload_dir = 'profilepicture/';

include_once '../include/DbConnect.php';
include_once '../operations/DbOperations.php';

$response = array();
$db = new DbConnect();
$con = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (
        isset($_POST['wmechanic_name'])
        && isset($_POST['wmechanic_email'])
        && isset($_POST['wmechanic_password'])
        && isset($_POST['wmechanic_address'])
        && isset($_POST['wmechanic_contact'])
        && isset($_POST['wmechanic_lat'])
        && isset($_POST['wmechanic_lng'])
        && isset($_POST['wmechanic_cnic'])
        && isset($_POST['vehicle_type'])
        && $_FILES['wmechanic_profile_img']

    ) {
        $db = new DbOperations();
        $result = $db->CreateWorkshopMechanicAccount(
            $_POST['wmechanic_name'],
            $_POST['wmechanic_password'],
            $_POST['wmechanic_address'],
            $_POST['wmechanic_email'],
            $_POST['wmechanic_lng'],
            $_POST['wmechanic_lat'],
            $_POST['wmechanic_contact'],
            $_POST['wmechanic_cnic'],
            $_POST['vehicle_type']
        );
        if ($result == 0) {
            $response['error'] = true;
            $response['code'] = 404;
            $response['message'] = "Email Already Exists";
        } else if ($result == 1) {
            $avatarname = $_FILES["wmechanic_profile_img"]["name"];
            $avatartempname = $_FILES["wmechanic_profile_img"]["tmp_name"];
            $error = $_FILES["wmechanic_profile_img"]["error"];

            if ($error > 0) {
                $response = array(
                    "status" => "error",
                    "error" => true,
                    "message" => "Uploading Failed"
                );
            } else {
                $randomname = rand(1000, 1000000) . "-" . $avatarname;
                $uploadname = $upload_dir . strtolower($randomname);
                $uploadname = preg_replace('/\s+/', '-', $uploadname);

                if (move_uploaded_file($avatartempname, $uploadname)) {
                    $url = "/" . $uploadname;

                    $sql = "UPDATE tb_workshop SET wmechanic_profile_img=? WHERE wmechanic_email=?";
                    $stmt = $con->prepare($sql);
                    $stmt->bind_param('ss', $url, $_POST['wmechanic_email']);
                    if ($stmt->execute()) {
                        $response['error'] = true;
                        $response['code'] = 200;
                        $response['message'] = "Account Created Successfully";
                    }
                }
            }
        } else {
            $response['error'] = true;
            $response['code'] = 404;
            $response['message'] = "Account Not Created Successfully";
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
