<?php
require_once __DIR__.'/admin/lib/auth.inc.php';
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
    <link rel="stylesheet" href="main.css">

</head>

<body>
    <?php
    include 'navbar.php';
    ?>
    <?php
    include 'breadcrumb.php';
    ?>

    <div class="container bg-light">

        <div class="row">
            <div class="col">
            <?php
            require_once __DIR__.'/admin/lib/db.inc.php';
            if(isset($_GET['name'])){
                $name = htmlspecialchars( $_GET['name'], ENT_QUOTES, 'UTF-8' );
                //$name = $_GET['name'];
             }
             $desc = ierg4210_prod_fetchOne("$name");
             $description ='';
             foreach ($desc as $desc) {
                //echo $desc['DESC'];   
                   $description .= '<img src="./admin/lib/images/'.$desc['NAME'].'.jpg">';
                   echo $description;
             }
            ?>
            </div>
            
            <div class="col">
                <br>
                <?php
                //require __DIR__.'/admin/lib/db.inc.php';
                if(isset($_GET['name'])){
                    $name = htmlspecialchars( $_GET['name'], ENT_QUOTES, 'UTF-8' );
                    //$name = $_GET['name'];
                }
                $desc = ierg4210_prod_fetchOne("$name");
                $description ='';
                 foreach ($desc as $desc) {
                    //echo $desc['DESC'];   
                    $description .= '<h1>'.$desc['NAME'].'</h1>';
                    echo $description;
                 }
            ?>
                <br>
                    <div class="row-lg-12 justify-content-between">
                         <h5>
                            <?php
                             //require __DIR__.'/admin/lib/db.inc.php';
                             if(isset($_GET['name'])){
                                $name = htmlspecialchars( $_GET['name'], ENT_QUOTES, 'UTF-8' );
                                //$name = $_GET['name'];
                             }
                             $desc = ierg4210_prod_fetchOne("$name");
                             $description ='';
                                foreach ($desc as $desc) {
                                 //echo $desc['DESC'];   
                                    $description .= ''.$desc['DESC'].'';
                                }
                                echo $description;
                            ?>
                        </h5>
                        <br>   
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-lg-12 left-aligned">
                            <?php
                             //require __DIR__.'/admin/lib/db.inc.php';
                             if(isset($_GET['name'])){
                                $name = htmlspecialchars( $_GET['name'], ENT_QUOTES, 'UTF-8' );
                                //$name = $_GET['name'];
                             }
                             $desc = ierg4210_prod_fetchOne("$name");
                             $description ='';
                                foreach ($desc as $desc) {
                                 //echo $desc['DESC'];   
                                    $description .= '<a>'.$desc['inventory'].' items remains</a><br>';
                                }
                                echo $description;
                                $buttonName ='';
                                $product=ierg4210_prod_fetchOne(htmlspecialchars( $_GET['name'], ENT_QUOTES, 'UTF-8' ));
                                foreach ($product as $product){
                                    $buttonName .= '<button type="button" pid="'.$product['PID'].'" id="'.$name.'btn" class="btn btn-primary">ADD TO CART</button>';
                                }
                                echo $buttonName;
                            ?>
                                <!-- <button type="button" class="btn btn-primary">ADD TO CART</button> -->
                        </div>
                    </div>
            </div>
        </div>
    </div>




    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
    <script>
        $(function () {

            $('[data-toggle="modal"]').hover(function () {
                var modalId = $(this).data('target');
                $(modalId).modal('show');

            });

        });
    </script>
    <script src="cart.js"></script>
</body>

</html>