<?php
//$product = ierg4210_prod_fetchAll();
function orderDB() {
	// connect to the database
	// TODO: change the following path if needed
	// Warning: NEVER put your db in a publicly accessible location
	$db = new PDO('sqlite:/var/www/order.db');
    //$db = new PDO('sqlite:C:\xampp\htdocs\order.db'); //hide this line and use above line when put to server
	// enable foreign key support
	$db->query('PRAGMA foreign_keys = ON;');

	// FETCH_ASSOC:
	// Specifies that the fetch method shall return each row as an
	// array indexed by column name as returned in the corresponding
	// result set. If the result set contains multiple columns with
	// the same name, PDO::FETCH_ASSOC returns only a single value
	// per column name.
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

	return $db;
}

function insert_digest_lastInsertID($digest) {  //this function return lastInsertID
    // DB manipulation
    global $db;
    $db = orderDB();
    $q = $db->prepare("INSERT INTO 'TRANSACTION' (status,create_time,digest) VALUES (?,?,?);");
    $create_time=time();
    $status='PENDING';
    $q->bindParam(1, $status);
    $q->bindParam(2, $create_time);
    $q->bindParam(3, $digest);
    $q->execute();
	return $create_time;

}

function validate_digest_lastInsertID($lastInsertId,$digest) {  //return the array if exist, else return empty array
    // DB manipulation
    global $db;
    $db = orderDB();
    $q = $db->prepare("SELECT * FROM 'TRANSACTION' WHERE (STATUS='PENDING') and (CREATE_TIME=?) and (DIGEST=?);");
    $q->bindParam(1, $lastInsertId);
    $q->bindParam(2, $digest);
	if ($q->execute()){
        return $q->fetchAll();
     }
}

function txnidExist($txnid) {  //return the array if exist, else return empty array
    // DB manipulation
    global $db;
    $db = orderDB();
    $q = $db->prepare("SELECT * FROM 'TRANSACTION' WHERE TXNID=?;");
    $q->bindParam(1, $txnid);
    if ($q->execute()){
       return $q->fetchAll();
    }
}

function insertTransaction($lastInsertId,$userid,$digest,$txnid) {  //return 1 if exist, else return 0
    // DB manipulation
    global $db;
    $db = orderDB();
    //$q = $db->prepare("UPDATE 'TRANSACTION' SET txnid=? , userid=? , status='COMPLETE' WHERE (STATUS='PENDING') and (CREATE_TIME=?) and (DIGEST=?);");
    $q = $db->prepare("UPDATE 'TRANSACTION' SET txnid=? , userid=? , status='COMPLETE' WHERE (CREATE_TIME=?) and (DIGEST=?);");
    $q->bindParam(1, $txnid);
    $q->bindParam(2, $userid);
    $q->bindParam(3, $lastInsertId);
    $q->bindParam(4, $digest);
	return $q->execute();
}

function insertProducts($txnid,$pid,$quantity,$totalPrice){
    // DB manipulation
    global $db;
    $db = orderDB();
    $q = $db->prepare("INSERT INTO products (txnid, pid, quantity, price) VALUES (?, ?, ?, ?);");
    $q->bindParam(1, $txnid);
    $q->bindParam(2, $pid);
    $q->bindParam(3, $quantity);
    $q->bindParam(4, $totalPrice);
	return $q->execute();
}
//These function below is used in record.php
function getUserTransaction($userid) {  //this function return lastInsertID
    // DB manipulation
    global $db;
    $db = orderDB();
    $q = $db->prepare("SELECT * FROM 'TRANSACTION' WHERE USERID=? ORDER BY CREATE_TIME DESC;");
    $q->bindParam(1, $userid);
    if ($q->execute()){
        return $q->fetchAll();
    }

}

function getTransactionProducts($txnid){
    // DB manipulation
    global $db;
    $db = orderDB();
    $q = $db->prepare("SELECT * FROM PRODUCTS WHERE txnid = ?;");
    $q->bindParam(1, $txnid);
    if ($q->execute()){
        return $q->fetchAll();
    }
}
//These function below is used in admin.php
function getAllTransaction(){
    // DB manipulation
    global $db;
    $db = orderDB();
    $q = $db->prepare("SELECT * FROM 'TRANSACTION' ORDER BY CREATE_TIME DESC;;");
    if ($q->execute()){
        return $q->fetchAll();
    }
}

function getAllProducts(){
    // DB manipulation
    global $db;
    $db = orderDB();
    $q = $db->prepare("SELECT * FROM PRODUCTS;");
    if ($q->execute()){
        return array_reverse($q->fetchAll(),true);
    }
}
?>