<?php
require_once __DIR__.'/admin/lib/auth.inc.php';
require_once __DIR__.'/admin/csrf.php';
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="media/icon.png" class="d-inline-block align-top" alt="icon" width="30" height="30">
                GamerStore
            </a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="col-md-12">
                    <ul class="navbar-nav offset-md-2">
                        <div class="col-md-8">

                            <input class="form-control" type="search" placeholder="Search for anything..."
                                aria-label="Search">

                        </div>
                        <li class="nav-item">
                            <button class="btn btn-outline-primary" type="submit">Search!</button>
                        </li>
                        <div class="collapse navbar-collapse col-md-4">
                            <li class="nav item offset-md-1">
                                <a id="checkCart" class="btn" href="#" data-toggle="modal" data-target="#cart">
                                    <img src="media/cart.png" alt="icon" width="25" height="25">
                                </a>
                            </li>
                            <li class="nav item offset-md-1">
                                <!-- <a class="navbar-brand" href="login.php">
                                    Login
                                </a> -->
                                <?php
                                $email=ierg4210_validateAuthToken();
                                $user='';
                                if($email==false){
                                    $user .= '<a class="navbar-brand" href="login.php">Login</a>';
                                }
                                else{
                                    $user .= '<a class="navbar-brand" href="logout.php">Logout</a>';
                                }
                                echo $user;
                                ?>
                            </li>
                            <li class="nav item">
                                <a class="navbar-brand" href="record.php">
                                    Records
                                </a>
                            </li>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="cart" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <!-- <form target="print_popup" id="checkOut" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post"> -->
                                <form target="paypal" id="checkOut" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" >
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Cart</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div id="cart-body" class="modal-body">
                                        
                                        <!-- <table class="table table-light">
                                            <thead>
                                                <tr>
                                                <th scope="col" class="text-center">Items</th>
                                                <th scope="col" class="text-center">Quantity</th>
                                                <th scope="col" class="text-center">Price</th>
                                                </tr>
                                            </thead>
                                            <tbody id="cart-body">
                                                <tr>
                                                <td>Jacob</td>
                                                <td><input type="number" id="replyNumber" min="0" data-bind="value:replyNumber" /></td>
                                                <td>@fat</td>
                                                </tr>
                                            </tbody>
                                        </table> -->
                                        <?php
                                            require_once __DIR__.'/admin/lib/db.inc.php';
                                            include "cart.php";

                                            $payment_currency = 'HKD';
                                            $item_name        = 'HKD';
                                            $item_number      = 1;
                                            $quantity     	  = 1;

                                            if(isset($_GET["pid"])){ //prevent 1st time no pid parameters in URL and warnings pop out
                                                $keys= $_REQUEST["pid"];
                                                $keys = explode(',', $keys); //Ajax get will get ?pid=1,2,3,4 , so use ',' as delimiter
                                            }
                                            else{
                                                var_dump($totalPrice);
                                            }
                                        
                                            
                                        ?>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-info"
                                            id="updateCart">Update</button>
                                        <!-- <button id="checkOut" type="button" class="btn btn-success">Proceed to Checkout</button> -->
                                        <input type="hidden" name="cmd" value="_cart">
                                        <input type="hidden" name="business" value="LSA57EQZL4QSA">
                                        <input type="hidden" name="lc" value="HK">
                                        <input type="hidden" name="item_name" value="My Shopping Cart">


                                        <input type="hidden" name="currency_code" value="HKD" />
                                        <input type="hidden" name="upload" value="1" />
                                        
                                        <input type="hidden" name="custom" value="custom data" >
                                        <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest">
                                        <!-- <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt=""> -->
                                        <input id="checkOutBtn" type="submit" class="btn btn-success"  border="0" name="submit" value="Proceed to Checkout">
                                        <input type="hidden" name="return" value="https://www.mystore.com/success">
                                        <input type="hidden" name="cancel_return" value="index.php">
                                        <img alt="" border="0" src="https://www.sandbox.paypal.com/zh_HK/i/scr/pixel.gif" width="1" height="1">
                                    </div>

                                </form>








                                </div>
                            </div>
                        </div>

                    </ul>
                </div>
            </div>

        </div>
    </nav>
<!-- <script>  //pop up a small window
    var myForm = document.getElementById('checkOut');
    myForm.onsubmit = function() {
    var w = window.open('about:blank','Popup_Window','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=400,height=50%,left = 312,top = 234');
    this.target = 'Popup_Window';
    };
</script> -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=AYdBaWNNVs6O_ZayjLiQ2REO0ZRel26zgvtkXvLEJ5qD7qFbZrdj9VaUKfruta_lS-SepTnGKQxHdH3C&currency=USD&disable-funding=credit,card" data-sdk-integration-source="button-factory"></script>
    <script src="checkout.js"></script>
