<?php
function ierg4210_DB() {
	// connect to the database
	// TODO: change the following path if needed
	// Warning: NEVER put your db in a publicly accessible location
	$db = new PDO('sqlite:/var/www/cart.db');
    //$db = new PDO('sqlite:C:\xampp\htdocs\cart.db'); //hide this line and use above line when put to server
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

function ierg4210_cat_fetchall() {
    // DB manipulation
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM categories LIMIT 100;");
    if ($q->execute())
        return $q->fetchAll();
}

// Since this form will take file upload, we use the tranditional (simpler) rather than AJAX form submission.
// Therefore, after handling the request (DB insert and file copy), this function then redirects back to admin.html
function ierg4210_prod_insert() {
    // input validation or sanitization

    // DB manipulation
    global $db;
    $db = ierg4210_DB();

    // TODO: complete the rest of the INSERT command
    if (!preg_match('/^\d*$/', $_POST['catid']))
        throw new Exception("invalid-catid");
    $_POST['catid'] = (int) $_POST['catid'];
    if (!preg_match('/^[\w\- ]+$/', $_POST['name']))
        throw new Exception("invalid-name");
    if (!preg_match('/^[\d\.]+$/', $_POST['price']))
        throw new Exception("invalid-price");
    if (!preg_match('/^[\w\- ]+$/', $_POST['description']))
        throw new Exception("invalid-textt");

    $sql="INSERT INTO products (catid, name, price, desc) VALUES (?, ?, ?, ?)";
    $q = $db->prepare($sql);

    // Copy the uploaded file to a folder which can be publicly accessible at incl/img/[pid].jpg
    if ($_FILES["file"]["error"] == 0
        && $_FILES["file"]["type"] == "image/jpeg"
        && mime_content_type($_FILES["file"]["tmp_name"]) == "image/jpeg"
        && $_FILES["file"]["size"] < 5000000) {


        $catid = $_POST["catid"];
        $name = $_POST["name"];
        $price = $_POST["price"];
        $desc = $_POST["description"];
        $sql="INSERT INTO products (catid, name, price, desc) VALUES (?, ?, ?, ?);";
        $q = $db->prepare($sql);
        $q->bindParam(1, $catid);
        $q->bindParam(2, $name);
        $q->bindParam(3, $price);
        $q->bindParam(4, $desc);
        $q->execute();
        $lastId = $db->lastInsertId();

        // Note: Take care of the permission of destination folder (hints: current user is apache)
        //if (move_uploaded_file($_FILES["file"]["tmp_name"], "/var/www/html/admin/lib/images/" . $lastId . ".jpg")) {
          if (move_uploaded_file($_FILES["file"]["tmp_name"], "lib/images/" . $name.".jpg")) {
            // redirect back to original page; you may comment it during debug
            header('Location: admin.php');
            exit();
        }
    }
    // Only an invalid file will result in the execution below
    // To replace the content-type header which was json and output an error message
    header('Content-Type: text/html; charset=utf-8');
    echo 'Invalid file detected. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
    print_r($_FILES);
    var_dump(mime_content_type($_FILES["file"]["tmp_name"]));
    exit();
}

// TODO: add other functions here to make the whole application complete
function ierg4210_cat_insert() {
    if (!preg_match('/^[\w\- ]+$/', $_POST['name']))
        throw new Exception("invalid-name");

    $name = $_POST["name"];
    // DB manipulation
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("INSERT INTO CATEGORIES (catid,name) VALUES (?,?);");
    $q->bindParam(1, $catid);
    $q->bindParam(2, $name);
	return $q->execute();

}
function ierg4210_cat_edit(){ //this function not yet completed
    // if (!preg_match('/^[\w\- ]+$/', $_POST['name']))
    //     throw new Exception("invalid-name");

    // $name = $_POST["name"];
    // // DB manipulation
    // global $db;
    // $db = ierg4210_DB();
    // $q = $db->prepare("UPDATE CATEGORIES SET NAME=name WHERE NAME='test');
    // $q->bindParam(1, $catid);
    // $q->bindParam(2, $name);
	// return $q->execute();
}
function ierg4210_cat_delete(){
    if (!preg_match('/^[\w\- ]+$/', $_POST['name']))
        throw new Exception("invalid-name");
    $name = $_POST["name"];
    // DB manipulation
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("DELETE FROM CATEGORIES WHERE name = ?;");
    $q->bindParam(1, $name);
	return $q->execute();
    
}
function ierg4210_prod_delete_by_catid(){
    
}
function ierg4210_prod_fetchAll(){
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM products ;");
    if ($q->execute()){
        return $q->fetchAll();
    }
}
function ierg4210_prod_fetchOne($name){
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM products WHERE name = ?;");
    $q->bindParam(1, $name);
	if ($q->execute()){
        return $q->fetchAll();
    }
}

function ierg4210_prod_fetchOnePid($pid){
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM products WHERE PID = ?;");
    $q->bindParam(1, $pid);
	if ($q->execute()){
        return $q->fetchAll();
    }
}
function ierg4210_prod_edit(){}
function ierg4210_prod_delete(){
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("DELETE FROM products WHERE PID= ?;");
    $pid = $_POST["pid"];
    $q->bindParam(1, $pid);
    return $q->execute();
}

function ierg4210_update_quantity(){
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("UPDATE products SET inventory=? WHERE NAME=?;");
    $name = $_POST["name"];
    $quantity = $_POST["quantity"];
    $q->bindParam(1, $quantity);
    $q->bindParam(2, $name);
    var_dump($name);
    var_dump($quantity);
    return $q->execute();
}
