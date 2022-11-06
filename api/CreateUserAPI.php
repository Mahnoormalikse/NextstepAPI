<?php
header("Content-Type: application/json; charset=UTF-8");
include_once '../include/DbConnect.php';
include_once '../operations/DbOperations.php';

$response=array();
$db=new DbConnect();
$con=$db->connect();

if($_SERVER['REQUEST_METHOD']=='POST'){

    if(isset($_POST['name'])
        && isset($_POST['email'])
        && isset($_POST['password'])
    ){

        $db=new DbOperations();
        $result=$db->CreateUser($_POST['name'],$_POST['email'],$_POST['password']);


        // $response['error']='Testing';
        // $response['message']=$result;

        if($result[0]==0){
            $response['error']=true;
            $response['code']=404;
            $response['message']="Email Already Exists";    
        }else if($result[0]==1){
            $user = $db->getCurrentUser($_POST['email']);
            $response['error']=false;
            $response['code']=200;
            $response['id']=$result[1];
            $response['message']="Account Created Successfully";
        }else{
            $response['error']=true;
            $response['code']=404;
            $response['message']="Account Not Created Successfully";

        }
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
echo json_encode($response);