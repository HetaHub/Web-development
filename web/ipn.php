<?php
require_once __DIR__.'/admin/lib/db.inc.php';
require_once __DIR__.'/admin/lib/checkout.inc.php';
require_once 'checkout-process.php';// not sure needed or not
//
// STEP 1 - be polite and acknowledge PayPal's notification
//
header('HTTP/1.1 200 OK');

//
// STEP 2 - create the response we need to send back to PayPal for them to confirm that it's legit
//

$resp = 'cmd=_notify-validate';
foreach ($_POST as $parm => $var) 
	{
	$var = urlencode(stripslashes($var));
	$resp .= "&$parm=$var";
	}
	
// STEP 3 - Extract the data PayPal IPN has sent us, into local variables 

  $payment_status   = $_POST['payment_status'];
  $payment_amount   = $_POST['mc_gross'];
  $txn_id           = $_POST['txn_id'];
  $receiver_email   = $_POST['receiver_email'];
  $custom	 		= $_POST['custom'];

  $business         = $_POST['business'];
  $payment_currency = $_POST['mc_currency'];
  $txn_type = $_POST['txn_type'];

  $number_of_items=0;
  for($i=1;$i<10000;$i++){ //at most 10000 items here in a list
	$item_name_post='item_name'.$i.'';
	$item_number_post='item_number'.$i.'';
	$quantity_post='quantity'.$i.'';
	$mc_gross__post='mc_gross_'.$i.'';
	if(isset($_POST[$item_name_post])){
        ${$item_name_post} =$_POST[$item_name_post];
		${$item_number_post} =$_POST[$item_number_post];
		${$quantity_post} =$_POST[$quantity_post];
		${$mc_gross__post} =$_POST[$mc_gross__post];
		$number_of_items=$i;
    }
	else{
		break;
	}
  }

  

  // Right.. we've pre-pended "cmd=_notify-validate" to the same data that PayPal sent us (I've just shown some of the data PayPal gives us. A complete list
// is on their developer site.  Now we need to send it back to PayPal via HTTP.  To do that, we create a file with the right HTTP headers followed by 
// the data block we just createdand then send the whole bally lot back to PayPal using fsockopen


// STEP 4 - Get the HTTP header into a variable and send back the data we received so that PayPal can confirm it's genuine

// $httphead = "POST /cgi-bin/webscr HTTP/1.0\r\n";
// $httphead .= "Content-Type: application/x-www-form-urlencoded\r\n";
// $httphead .= "Content-Length: " . strlen($resp) . "\r\n\r\n";
 
//  // Now create a ="file handle" for writing to a URL to paypal.com on Port 443 (the IPN port)

// $errno ='';
// $errstr='';

// // $fh = fsockopen("localhost", 80, $errno, $errstr, 30);
// // header('Content-type: text/plain');
// // fputs ($fh, $httphead . $resp);
// // while (!feof($fh)) {
// //     echo fgets($fh, 1024);
// // }
// // fclose ($fh);

// $fh = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);
// if (!$fh) {
// 	//connection failed
// }
// else{
// 	fputs ($fh, $httphead . $resp);
// 	$textcontent='';
// 	while (!feof($fh)){
// 		$readresp = fgets ($fh, 1024);
// 		$textcontent.=$readresp;
// 		if (strcmp ($readresp, "VERIFIED") == 0) {
			
// 			validate_And_Insert_data();
// 		}
// 		else if (strcmp ($readresp, "INVALID") == 0) { //failed, something wrong on verification page
			
			
// 		}
// 	}
// 	file_put_contents("sites.txt",$textcontent);
// 	fclose ($fh);
// }

$fh = curl_init('https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');
curl_setopt($fh, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($fh, CURLOPT_POST, 1);
curl_setopt($fh, CURLOPT_RETURNTRANSFER,1);
curl_setopt($fh, CURLOPT_POSTFIELDS, $resp);
curl_setopt($fh, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($fh, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($fh, CURLOPT_FORBID_REUSE, 1);
curl_setopt($fh, CURLOPT_HTTPHEADER, array('Connection: Close'));

if( !($respond = curl_exec($fh)) ) {
    // error_log("Got " . curl_error($ch) . " when processing IPN data");
    curl_close($fh);
    exit;
}
//file_put_contents("debug.txt",curl_getinfo($fh));
curl_close($fh);
if (strcmp ($respond, "INVALID") == 0){
	file_put_contents("sites.txt",$respond);
}else{
	validate_And_Insert_data();
}

//refer to checkout-process.php
function validate_And_Insert_data(){
	global $payment_status,$payment_amount,$txn_id,$receiver_email,$custom,$business,$payment_currency,$txn_type,$number_of_items;
	if($payment_status=='Completed' && $txn_type=='cart' && $receiver_email=='sb-vcgoz15238775@business.example.com' 
		&& $business=='sb-vcgoz15238775@business.example.com' && $payment_currency=='HKD'){
			$stack=array($payment_currency,$receiver_email);
			$totalPrice=0;
			for($i=1;$i<=$number_of_items;$i++){ //at most 10000 items here in a list
				$item_name_var='item_name'.$i.'';
				$item_number_var='item_number'.$i.'';
				$quantity_var='quantity'.$i.'';
				$mc_gross__var='mc_gross_'.$i.'';
				global ${$item_name_var},${$item_number_var},${$quantity_var},${$mc_gross__var};
				// ${$item_name_var};
				// ${$item_number_var};
				// ${$quantity_var};
				// ${$mc_gross__var};
				array_push($stack,${$item_number_var},${$quantity_var});
				$totalPrice += ${$mc_gross__var};
			}
			$totalPrice=number_format((float)$totalPrice, 1, '.', ''); //format to 1 decimal place
			array_push($stack,$totalPrice);
			$data=json_encode($stack);
			$validate = explode(",", $custom);
			$userid=$validate[2];
			if(!empty(txnidExist($txn_id))){
				return false;
			}
			if(!password_verify($data,$validate[1])){// 0 is lastInsertId (time()), 1 is digest
				return false;
			}
			if(empty(validate_digest_lastInsertID($validate[0],$validate[1]))){
				return false;
			}
			insertTransaction($validate[0],$userid,$validate[1],$txn_id);
			for($i=1;$i<=$number_of_items;$i++){ //at most 10000 items here in a list
				$item_name_var='item_name'.$i.'';
				$item_number_var='item_number'.$i.'';
				$quantity_var='quantity'.$i.'';
				$mc_gross__var='mc_gross_'.$i.'';
				global ${$item_name_var},${$item_number_var},${$quantity_var},${$mc_gross__var};
				// ${$item_name_var};
				// ${$item_number_var};
				// ${$quantity_var};
				// ${$mc_gross__var};
				echo insertProducts($txn_id,${$item_number_var},${$quantity_var},${$mc_gross__var});
			}
			return true;
		}
		else{
			return false;
		}
	
}

?>
