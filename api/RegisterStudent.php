<?php
header("Content-Type: application/json; charset=UTF-8");

$upload_dir='profilepicture/';

include_once '../include/DbConnect.php';
include_once '../operations/DbOperations.php';

$response=array();
$db=new DbConnect();
$con=$db->connect();

if($_SERVER['REQUEST_METHOD']=='POST'){

    if(isset($_POST['t_id']) 
    && isset($_POST['name'])
        && isset($_POST['email'])
        && isset($_POST['password'])
      
    ){

        $db=new DbOperations();
        $result=$db->RegisterStudent($_POST['t_id'], $_POST['name'],  $_POST['email'], $_POST['password']);
         if($result==1){
            $response['error']=true;
            $response['code']=404;
            $response['message']="Student registered Successfully";

    }else{
        $response['error']=true;
        $response['code']=404;
        $response['message']="Required fields Missing";
    }

}} else {
    $response['error']=true;
    $response['code']=500;
    $response['message']="Invalid Request Method";
}
echo json_encode($response);