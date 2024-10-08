<?php
    require_once __DIR__.'/admin/lib/auth.inc.php';
    require_once __DIR__.'/admin/csrf.php';
    $email=ierg4210_validateAuthToken();
    if($email==false){
        header('Location: ../login.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="login.css">
</head>
<body>

    <main class="login-form">
        <div class="cotainer">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="d-flex justify-content-center card-header">Change Password</div>
                            <div class="card-body">
                                <form method="POST" action="auth-process.php?action=changePw" enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <label for="email_address" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                                        <div class="col-md-6">
                                            <input type="email" readonly id="email" class="form-control-plaintext" name="email" <?php $text=''; $text.='value='.$email.''; echo $text; ?> >
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">Old Password</label>
                                        <div class="col-md-6">
                                            <input type="password" id="oldPassword" class="form-control" name="oldPassword" required autofocus>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">New Password</label>
                                        <div class="col-md-6">
                                            <input type="password" id="newPassword" class="form-control" name="newPassword" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-1 offset-md-4">
                                            <button type="submit" class="btn btn-danger">
                                                Confirm
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="nonce" value="<?php $action="changePw"; echo csrf_getNonce($action); ?>"/>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
</body>
</html>