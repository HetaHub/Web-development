<?php
session_start();
$admin="1155127396@link.cuhk.edu.hk";
function loginDB() {
	// connect to the database
	// TODO: change the following path if needed
	// Warning: NEVER put your db in a publicly accessible location
	$db = new PDO('sqlite:/var/www/login.db');
    //$db = new PDO('sqlite:C:\xampp\htdocs\login.db'); //hide this line and use above line when put to server
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

function ierg4210_login(){
	if (empty($_POST['email']) || empty($_POST['password'])
		|| !preg_match("/^[\w=+\-\/][\w='+\-\/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$/", $_POST['email'])
		|| !preg_match("/^[\w@#$%\^\&\*\-]+$/", $_POST['password']))
		throw new Exception('Wrong Credentials');

	// Implement the login logic here
	//$login_success=loginProcess($_POST['email'],$_POST['pw']); //login process is in auth.php
    $login_success=verify($_POST['email'],$_POST['password']);
	if ($login_success){
            //prevent session fixation
            session_regenerate_id(true);
        // 	// redirect to admin page
        // if($login_success==2)
        // 	header('Location: admin.php', true, 302);
        // else {
        // header('Location: index.php', true, 302);
        // }
        // 	exit();
        global $admin;
        if($admin==$_POST['email']){ //equal admin
            if(isset($_COOKIE['auth'])==false){
                $expiry=time()+(3*24*60*60);
                $value=array( 'email'=>$_POST['email'],'expiry'=>$expiry ,'key'=>password_hash($_POST['password'],PASSWORD_DEFAULT, array('cost' => 9)));
                setcookie('auth', json_encode($value), $expiry,'','',true,true);
                //setcookie("auth", json_encode($value), $expiry,'','',false,true); //hide this line and use above line when put to server
                $_SESSION['auth']=$value;
            }
            else{
                $value=json_decode($_COOKIE['auth'],true);
                $expiry=time()+(3*24*60*60);
                $value['expiry']=$expiry;
                setcookie('auth', json_encode($value), $expiry,'','',true,true);
                //setcookie("auth", json_encode($value), $expiry,'','',false,true); //hide this line and use above line when put to server
                $_SESSION['auth']=$value;
            }
            header('Content-Type: text/html; charset=utf-8');
            echo '<script>window.location.replace("admin/admin.php");</script>';
        }
        else{
            if(isset($_COOKIE['auth'])==false){
                $expiry=time()+(3*24*60*60);
                $value=array( 'email'=>$_POST['email'],'expiry'=>$expiry ,'key'=>password_hash($_POST['password'],PASSWORD_DEFAULT, array('cost' => 9)));
                setcookie('auth', json_encode($value), $expiry,'','',true,true);
                //setcookie("auth", json_encode($value), $expiry,'','',false,true); //hide this line and use above line when put to server
                $_SESSION['auth']=$value;
            }
            else{
                $value=json_decode($_COOKIE['auth'],true);
                $expiry=time()+(3*24*60*60);
                $value['expiry']=$expiry;
                setcookie('auth', json_encode($value), $expiry,'','',true,true);
                //setcookie("auth", json_encode($value), $expiry,'','',false,true); //hide this line and use above line when put to server
                $_SESSION['auth']=$value;
            }
            header('Content-Type: text/html; charset=utf-8');
            echo '<script>window.location.replace("index.php");</script>';
        }
	} else{
        header('Content-Type: text/html; charset=utf-8');
        echo 'Wrong username or password. <br/><a href="javascript:history.back();">Back to login page.</a>';
        throw new Exception('User name invalid or user password invalid');
    }
}

function verify($email, $password){
    global $db;
    $db = loginDB();
    $q = $db->prepare("SELECT * FROM accounts WHERE email = ?;");
    $q->execute(array($email));
    $result = $q->fetch();
    $hash_variable_salt=$result['PASSWORD'];
    $iscorrect=0;
    if($result!=false){ //has record
        $iscorrect=password_verify($password,$hash_variable_salt);
        
    }
    return $iscorrect;
}

function ierg4210_signUp(){
    if (empty($_POST['email']) || empty($_POST['password'])
		|| !preg_match("/^[\w=+\-\/][\w='+\-\/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$/", $_POST['email'])
		|| !preg_match("/^[\w@#$%\^\&\*\-]+$/", $_POST['password']))
		throw new Exception('Wrong format of email');
    global $db;
    $db = loginDB();
    $q = $db->prepare("SELECT * FROM accounts WHERE email = ?;");
    $q->execute(array($_POST['email']));
    $result = $q->fetch();
    if($result!=false){  //email already exist
        header('Content-Type: text/html; charset=utf-8');
        echo 'Email already exist. <br/><a href="javascript:history.back();">Back to sign up page.</a>';
        exit();
    }
    else{
        $q = $db->prepare("SELECT * FROM accounts WHERE userid = ?;");
        $userid=mt_rand();
        $q->execute(array($userid));
        $result = $q->fetch();
        while($result!=false){  //userid collision
            $userid=mt_rand();
            $q->execute(array($userid));
            $result = $q->fetch();
        }
        $q = $db->prepare("INSERT INTO ACCOUNTS (userid,email,password) VALUES (?,?,?);");
        $q->bindParam(1, $userid);
        $q->bindParam(2, $_POST['email']);
        $hash_variable_salt= password_hash($_POST['password'],PASSWORD_DEFAULT, array('cost' => 9));
        $q->bindParam(3, $hash_variable_salt);
        if($q->execute()){
            header('Content-Type: text/html; charset=utf-8');
            echo 'Successful. <br/><a href="index.php">Back to main page.</a>';
            exit();
        }
    }
}

function ierg4210_logout(){
	// clear the cookies and session
	setcookie("auth",'',time()-1);
	unset($_COOKIE['auth']);
	$_SESSION['auth']=null;
    
	echo 'You logout successfully';
	// redirect to login page after logout
	header('Location: login.php');
	exit();
}

function getUserID($email){
    global $db;
    $db = loginDB();
    $q = $db->prepare("SELECT * FROM accounts WHERE email = ?;");
    $q->execute(array($email));
    $result = $q->fetch();
    return $result['USERID'];;
}

function ierg4210_validateAuthToken(){
    if(!empty($_SESSION['auth'])){
        return $_SESSION['auth']['email'];
    }
    else{ //session empty, check cookie
        if(!empty($_COOKIE['auth'])){
            $value=json_decode($_COOKIE['auth'],true);
            if(time()<$value['expiry']){ //current time less than expiry date
                $db=loginDB();
                $q = $db->prepare("SELECT * FROM accounts WHERE email = ?;");
                $q->execute(array($value['email']));
                $result = $q->fetch();
                $iscorrect=0;
                if($result!=false){ //has record
                    $iscorrect=password_verify($result['PASSWORD'], $value['key']);    
                    if($iscorrect){ //password is correct in cookie
                        return $result['EMAIL'];
                    }
                    else{
                        return false;
                    }                
                }
                else{
                    return false;
                }
            }
            else{
                return false;
            }
        }
        else{
            return false; // cookie and session not set
        }
    }
}

function ierg4210_changePw(){
    $email=ierg4210_validateAuthToken();
    if (empty($_POST['oldPassword']) || !preg_match("/^[\w@#$%\^\&\*\-]+$/", $_POST['oldPassword']))
		throw new Exception('Wrong Credentials');
    $iscorrect=verify($email,$_POST['oldPassword']);
    if($iscorrect){
        global $db;
        $db = loginDB();
        $q = $db->prepare("UPDATE ACCOUNTS SET PASSWORD = (?) WHERE EMAIL=(?);");
        $q->bindParam(1, password_hash($_POST['newPassword'],PASSWORD_DEFAULT, array('cost' => 9)));
        $q->bindParam(2, $email);
        $q->execute();
        header('Location: logout.php');
        exit();
    }
    else{
        header('Content-Type: text/html; charset=utf-8');
        echo 'Old password is not correct. <br/><a href="javascript:history.back();">Back to previous page.</a>';
    }

}
?>