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
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Transaction id</th>
      <th scope="col">Product Name</th>
      <th scope="col">Quantity</th>
      <th scope="col">Total Price</th>
    </tr>
  </thead>
  <tbody>
  <?php
    require_once __DIR__.'/admin/lib/auth.inc.php';
    require_once __DIR__.'/admin/lib/db.inc.php';
    require_once __DIR__.'/admin/lib/checkout.inc.php';
    $email=ierg4210_validateAuthToken();
        $user='';
        if($email==false){
            $user = 'guest';
            echo '<a href="login.php">Please login to see records!</a>';
        }
        else{
            $user =$email;
            }
    $records=getUserTransaction($user);
    $count=1;
    $output='';
    foreach($records as $records){
        if($count>5){
            break;
        }
        $productList=getTransactionProducts($records['TXNID']);
        $output.='<tr><th scope="row">'.$records['TXNID'].'</th>';
        foreach($productList as $productList){
            $output.='<td>'.$count.'</td>';
            $item=ierg4210_prod_fetchOnePid($productList['PID']);
            foreach($item as $item){
                $output.='<td>'.$item['NAME'].'</td>';
            }
            $output.='<td>'.$productList['QUANTITY'].'</td>';
            $output.='<td>'.$productList['PRICE'].'</td></tr>';
        }
        $count++;
    }
    echo $output;

    ?>
  </tbody>
</table>
</body>
</html>

