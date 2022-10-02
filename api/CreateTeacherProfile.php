<?php
header("Content-Type: application/json; charset=UTF-8");

$upload_dir='profilepicture/';

include_once '../include/DbConnect.php';
include_once '../operations/DbOperations.php';

$response=array();
$db=new DbConnect();
$con=$db->connect();

if($_SERVER['REQUEST_METHOD']=='POST'){

    if(isset($_POST['id']) 
    && isset($_POST['name'])
        && isset($_POST['qualification'])
        && isset($_POST['gender'])
      
    ){

        $db=new DbOperations();
        $result=$db->CreateTeacherProfile($_POST['id'], $_POST['name'],  $_POST['qualification'], $_POST['gender']);
         if($result==1){
            $response['error']=true;
            $response['code']=404;
            $response['message']="Profile created Successfully";

    }else{
        $response['error']=true;
        $response['code']=404;
        $response['message']="Required fields Missing";
    }

}else {
    $response['error']=true;
    $response['code']=500;
    $response['message']="Invalid Request Method";
}
echo json_encode($response);}