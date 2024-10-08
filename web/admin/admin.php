<!DOCTYPE html>
<html lang="en">
<?php
    require_once __DIR__.'/lib/auth.inc.php';
    require_once('csrf.php');
	$admin="1155127396@link.cuhk.edu.hk";
    $email=ierg4210_validateAuthToken();
    if($email==false){
        header('Location: ../login.php');
    }
    else{
        if($email!=$admin){
            header('Location: ../index.php');
        }
    }
?>
<!-- ?php
    require __DIR__.'/lib/db.inc.php';
    $cat = ierg4210_prod_fetchOne("Dark Souls III");
    $stuff = array('name' => 'Joe', 'email' => 'joe@example.com');
    var_dump($cat);
     foreach ($cat as $cat) {
        echo $cat['DESC'];   
    }
    
 ? -->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="admin.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <div class="container-fluid px-0">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                    <div>
                        <h1 class="display-4">
                            <p class="text-center bg-light">
                                Admin Page
                            </p>
                        </h1>
                    </div>
                    <div class="bg-light">
                        
                        <form method="POST" action="admin-process.php?action=prod_insert" enctype="multipart/form-data">
                            <p class="text-center">New Product</p>
                            <div class="grid">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <a>Category id:</a>
                                        <div>
                                         <select name="catid" id="selectid" class="form-select" aria-label="Default select example">
                                             <!-- <option value="1" selected>Action</option>
                                            <option value="2">Adventure</option>
                                            <option value="3">FPS</option>
                                            <option value="4">RPG</option> -->
                                            <?php
                                                require_once __DIR__.'/lib/db.inc.php';
                                                $res = ierg4210_cat_fetchall();
                                                $options = '';


                                                foreach ($res as $value){
                                                    $options .= '<option value="'.$value["CATID"].'">'.$value["NAME"].'</option>';
                                                }
                                                echo $options;
                                            ?>
                                         </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="sr-only" for="Name"></label>
                                            <div class="input-group">
                                              <div class="input-group-addon">Name:</div>
                                              <input name="name" type="text" class="form-control" placeholder="Input product name" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="sr-only" for="exampleInputAmount">Amount (in Swiss Francs)</label>
                                            <div class="input-group">
                                              <div class="input-group-addon">HKD</div>
                                              <input name="price" type="number" min="0.00" step="1.00" value="1.00" class="form-control" placeholder="Price">
                                            </div>
                                        </div>
    
                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="exampleFormControlTextarea1">Product Description</label>
                                            <textarea name="description" class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Input here..." required></textarea>
                                          </div>
                                    </div>
                                    <div class="col-lg-6">
                                            <label>Upload image:</label>
                                            <input name="file" type="file" accept="image/*" onchange="loadFile(event)">
                                            <img class="img-thumbnail" id="output" alt="Preview here"/>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">Submit</button>
                            <input type="hidden" name="nonce" value="<?php $action="prod_insert"; echo csrf_getNonce($action); ?>"/>
                        </form>
                    </div>
                    <p></p>
                    <div class="bg-light">
                        
                        <form method="POST" action="admin-process.php?action=cat_insert" enctype="multipart/form-data">
                            <p class="text-center">New Category</p>
                            <div class="grid">
                                <div class="row">
                                    <div class="col-lg-12 ml-auto mr-auto">
                                        <div class="form-group">
                                            <label class="sr-only" for="Name"></label>
                                            <div class="input-group">
                                              <div class="input-group-addon">Category name:</div>
                                              <input name="name" type="text" class="form-control" placeholder="Input category name" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">Submit</button>
                            <input type="hidden" name="nonce" value="<?php $action="cat_insert"; echo csrf_getNonce($action); ?>"/>
                        </form>
                    </div>
                    <p></p>
                    <div class="bg-light">
                        
                        <form method="POST" action="admin-process.php?action=update_quantity" enctype="multipart/form-data">
                            <p class="text-center">Edit Inventory</p>
                            <div class="grid">
                                <div class="row">
                                    
                                <div class="col-lg-8">
                                    <div>
                                        <a>Product Name:</a>
                                        <select name="name" id="selectid" class="form-select col-lg-12" aria-label="Default select example">
                                            <!-- <option value="1" selected>Action</option>
                                            <option value="2">Adventure</option>
                                            <option value="3">FPS</option>
                                            <option value="4">RPG</option> -->
                                            <?php
                                                require_once __DIR__.'/lib/db.inc.php';
                                                $product = ierg4210_prod_fetchAll();
                                                $options = '';


                                                foreach ($product as $product){
                                                    $options .= '<option value="'.$product["NAME"].'">'.$product["NAME"].'</option>';
                                                }
                                                echo $options;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group col-lg-6">
                                        <label class="sr-only" for="exampleInputAmount">Amount (in Swiss Francs)</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">Quantity</div>
                                            <input name="quantity" type="number" min="0" step="1" value="1" class="form-control" placeholder="quantity">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">Submit</button>
                            <input type="hidden" name="nonce" value="<?php $action="update_quantity"; echo csrf_getNonce($action); ?>"/>
                        </form>
                </div>
                    
            </div>
        </div>
    </div>

    <div class="container-fluid px-0">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <p></p>
                    <div class="bg-light">
                    <h1 class="display-5">
                        <p class="text-center">
                            Transaction list
                        </p>
                    </h1>
                    <table class="table bg-light">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Transaction id</th>
                            <th scope="col">Status</th>
                            <th scope="col">Create Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                            </tr>
                            <tr>
                            <th scope="row">2</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                            </tr> -->
                            <?php
                            require_once __DIR__.'/lib/checkout.inc.php';
                            $transactions=getAllTransaction();
                            $output='';
                            $count=1;
                            foreach($transactions as $transactions){
                                $output.='<tr><th scope="row">'. $count.'</th>';
                                $output.='<td>'. $transactions['TXNID'].'</td>';
                                $output.='<td>'. $transactions['STATUS'].'</td>';
                                $output.='<td>'. $transactions['CREATE_TIME'].'</td></tr>';
                                $count++;
                            }
                            echo $output;
                            ?>
                        </tbody>
                    </table>
                    </div>
            </div>

            <div class="col-lg-11">
                <p></p>
                    <div class="bg-light">
                    <h1 class="display-5">
                        <p class="text-center">
                            Product list
                        </p>
                    </h1>
                    <table class="table bg-light">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Transaction id</th>
                            <th scope="col">PID</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                            </tr>
                            <tr>
                            <th scope="row">2</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                            </tr> -->
                            <?php
                            require_once __DIR__.'/lib/checkout.inc.php';
                            $products=getAllProducts();
                            $output='';
                            $count=1;
                            foreach($products as $products){
                                $output.='<tr><th scope="row">'. $count.'</th>';
                                $output.='<td>'. $products['TXNID'].'</td>';
                                $output.='<td>'. $products['PID'].'</td>';
                                $output.='<td>'. $products['QUANTITY'].'</td>';
                                $output.='<td>'. $products['PRICE'].'</td></tr>';
                                $count++;
                            }
                            echo $output;
                            ?>
                        </tbody>
                    </table>
                    </div>
            </div>
        </div>
    </div>
    <script src="admin.js"></script>
    <script>
        $(document).ready(function () {
        selectElement = document.querySelector('#selectid');
        output = selectElement.value;
        $('input[name="catid"]').val(output);
        });
    </script>
    <script>
        var loadFile = function(event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
            }
        };
    </script>
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
    </div>
</body>
</html>

// ?php
// include("admin-process.php");
// ?>
// ?php
// if($_SERVER["REQUEST_METHOD"] == "POST")
// {
// $name = $_POST['name'];
// if(empty($name))
// {
    
// }
// else
// {
//     ierg4210_prod_insert();
// }
// }
// ?



