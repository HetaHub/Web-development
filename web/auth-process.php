<?php
include_once('admin/lib/auth.inc.php');
include_once('admin/csrf.php');

header('Content-Type: application/json');

// input validation
if (empty($_REQUEST['action']) || !preg_match('/^\w+$/', $_REQUEST['action'])) {
	echo json_encode(array('failed'=>'undefined'));
	exit();
}

if ($_REQUEST['action']) { //csrf attack check
	if ($_REQUEST['action']!="login"){  // if action==login, don't check $_SESSION['auth']==null because need to get the value after login
		csrf_verifyNonce($_REQUEST['action'], $_REQUEST['nonce']);
	}
	else{
		if (isset($_REQUEST['nonce']) && $_SESSION['csrf_nonce'][$_REQUEST['action']] != $_REQUEST['nonce']) {
			throw new Exception('csrf-attack');
		}
	}

}
// The following calls the appropriate function based to the request parameter $_REQUEST['action'],
//   (e.g. When $_REQUEST['action'] is 'cat_insert', the function ierg4210_cat_insert() is called)
// the return values of the functions are then encoded in JSON format and used as output
try {

	if (($returnVal = call_user_func('ierg4210_' . $_REQUEST['action'])) === false) {
		if ($db && $db->errorCode()) 
			error_log(print_r($db->errorInfo(), true));
		echo json_encode(array('failed'=>'1'));
	}
	echo 'while(1);' . json_encode(array('success' => $returnVal));
} catch(PDOException $e) {
	error_log($e->getMessage());
	echo json_encode(array('failed'=>'error-db'));
} catch(Exception $e) {
	echo 'while(1);' . json_encode(array('failed' => $e->getMessage()));
}
?>
