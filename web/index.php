<!DOCTYPE html>
<html lang="en">
<?php
require_once __DIR__.'/admin/lib/auth.inc.php';
?>
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

    <div class="d-inline">
        <div id="carouselExampleIndicators" class="carousel slide position-relative" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block w-100" src="media/slideshow1.jpeg" alt="First slide">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Assassin Creed</h5>
                        <p>10 years celebration sell!</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="media/slideshow2.jpeg" alt="Second slide">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Elden Ring</h5>
                        <p>Pre-order now!</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="media/slideshow3.jpeg" alt="Third slide">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>The Elder Scrolls V: Skyrim Special Edition</h5>
                        <p>-80% now!</p>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>

    <div class="container-fluid p-3">
        <div class="row">
            <div class="d-none d-lg-block row-lg-2">
                <ul class="nav flex-column">
                    <!-- <li class="nav-item">
                        <a class="nav-link active" href="action.html">Action</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="adventure.html">Adventure</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="fps.html">FPS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="rpg.html">RPG</a>
                    </li> -->
                    <?php
                        require_once __DIR__.'/admin/lib/db.inc.php';
                        $cat = ierg4210_cat_fetchall();
                        $category = '<li class="nav-item">';
                        foreach ($cat as $cat){
                            $category .= '<a class="nav-link" href="category.php?catid='.$cat['CATID'].'">'.$cat['NAME'].' </a></li>';
                        }
                        echo $category;
                    ?>
                </ul>
            </div>


            <div class="col-lg-10 ml-5">
                <div class="col">
                    <div class="album py-5 bg-light">
                        <div class="container">
                            <div class="row">
                                <!-- <div class="col-md-4">
                                    <div class="card mb-4 box-shadow">
                                        <a href="./dark souls III.html">
                                            <img class="card-img-top" src="media/darksoul3.jpg" alt="Card image cap">
                                        </a>
                                        <div class="card-body">
                                            <p>
                                                <a class="card-text" href="./dark souls III.html">Dark Souls III</a>
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="btn-group">
                                                    <a class="btn btn-sm btn-outline-secondary"
                                                        href="https://google.com" class="button">View</a>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary">Add
                                                        to cart</button>
                                                </div>
                                                <small class="text-muted">$10.0</small>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->


                                <?php
                                require_once __DIR__.'/admin/lib/db.inc.php';
                                $product = ierg4210_prod_fetchAll();
                                if(isset($_GET['page'])){
                                    $page = htmlspecialchars( $_GET['page'], ENT_QUOTES, 'UTF-8' );
                                    //$page = $_GET['page'];
                                }
                                else{
                                    $page = 1;
                                }
                                $totalItemEachPage=9;
                                $totalitem=0;
                                $itemLeft=$totalItemEachPage;
                                $skipItem=$totalItemEachPage*($page-1); // skip 9 item for each page
                                foreach ($product as $product) {
                                    $totalitem++;
                                    if($skipItem>0){  
                                        $skipItem--;
                                        continue;
                                    }
                                    if($itemLeft==0){
                                        continue;
                                    }
                                    //echo $cat['DESC'];   //no item to skip anymore, do below
                                    $item='<div class="col-md-4"><div class="card mb-4 box-shadow"><a href="./product.php?name=';
                                    $item .= ''.$product['NAME'].'"><img class="card-img-top" src="admin/lib/images/'.$product['NAME'].'.jpg" alt="Card image cap"></a>';
                                    $item .= '<div class="card-body"><p><a class="card-text" href="./product.php?name='.$product['NAME'].'">'.$product['NAME'].'</a></p>';
                                    $item .= '<div class="d-flex justify-content-between align-items-center"><div class="btn-group"><a class="btn btn-sm btn-outline-secondary"
                                    href="./product.php?name='.$product['NAME'].'" class="button">View</a>';
                                    $item .= '<button type="button" pid='.$product['PID'].' id="'.$product['NAME'].'btn" class="btn btn-sm btn-outline-secondary">Add to cart</button></div><small class="text-muted">$'.$product['PRICE'].'.0</small>';
                                    $item .= '</div></div></div></div>';
                                    echo $item;
                                    $itemLeft--; 
                                }
                                ?>

                            </div>
                            <?php
                                include 'pagination.php';
                            ?>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
    <script>
        $(function () {     //hover cart icon

            $('[data-toggle="modal"]').hover(function () {
                var modalId = $(this).data('target');
                $(modalId).modal('show');

            });

        });
    </script>


    <script src="cart.js"></script>

</body>

</html>
