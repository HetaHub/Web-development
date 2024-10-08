<?php
require_once __DIR__.'/admin/lib/checkout.inc.php';
require_once __DIR__.'/admin/lib/auth.inc.php';
require_once __DIR__.'/admin/lib/db.inc.php';
header('Content-Type: application/json');
// //Make sure that it is a POST request.
// if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0){
//     throw new Exception('Request method must be POST!');
// }

// //Make sure that the content type of the POST request has been set to application/json
// $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
// if(strcasecmp($contentType, 'application/json') != 0){
//     return new Exception('Content type must be: application/json');
// }

//Receive the RAW post data.
$content = trim(file_get_contents("php://input"));

//Attempt to decode the incoming RAW post data from JSON.
$decoded = json_decode($content, true);

//If json_decode failed, the JSON is invalid.
if(!is_array($decoded)){
    return new Exception('Received content contained invalid JSON!');
}
$payment_currency = 'HKD';
$receiver_email   = 'sb-vcgoz15238775@business.example.com';
$totalPrice       =  0;
$totalQuantity    =  0;
$stack            =  array($payment_currency,$receiver_email);
foreach($decoded as $pid =>$quantity){
    $pid=preg_replace('/quantity/i',"",$pid);
    //echo $pid;
    //echo $quantity;
    array_push($stack,$pid,$quantity);
    $item=ierg4210_prod_fetchOnePid($pid);
    foreach ($item as $item) {
        $temp = $item['PRICE']*$quantity;
        $totalQuantity += $quantity;
        $totalPrice += $temp;
    }
}
$totalPrice=number_format((float)$totalPrice, 1, '.', ''); //format to 1 decimal place
array_push($stack,$totalPrice);
$data=json_encode($stack);
file_put_contents('blablabla.txt', $data);
$digest=password_hash($data, PASSWORD_DEFAULT);
$lastInsertId=insert_digest_lastInsertID($digest); // lastInsertId is get from the returned function, it is time();

$returnValue=array($lastInsertId);
array_push($returnValue,$digest);
//$returnValue=json_encode($returnValue);
$email=ierg4210_validateAuthToken();
$userid='';
if($email==false){
    $userid ='guest';
}
else{
    $userid =$email;
}
array_push($returnValue,$userid);
$returnValue=json_encode($returnValue);
echo $returnValue;

     


//Process the JSON.
?>
