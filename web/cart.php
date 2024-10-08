<?php
    require_once __DIR__.'/admin/lib/db.inc.php';
    $product = ierg4210_prod_fetchAll();
    $totalPrice = 0;
    $totalQuantity = 0;

    if(isset($_GET["pid"])){ //prevent 1st time no pid parameters in URL and warnings pop out
        $keys= $_REQUEST["pid"];
        $keys = explode(',', $keys); //Ajax get will get ?pid=1,2,3,4 , so use ',' as delimiter
    }
    else{
        $keys =[];
    }
    if(isset($_GET["quantity"])){
        $values= $_REQUEST["quantity"];
        $values = explode(',', $values); 
    }
    else{
        $values =[];
    }
    for($i=0;$i<sizeof($keys);$i++){
        if(!is_numeric($keys[$i])){
            $keys[$i]=null;
        }
    }
    for($i=0;$i<sizeof($values);$i++){
        if(!is_numeric($values[$i])){
            $values[$i]=null;
        }
    }
    $keys=array_values(array_filter($keys));
    $values=array_values(array_filter($values));
    $prod = '';
    $prod .= '<table class="table table-light"><thead><tr>';
    $prod .= '<th scope="col" class="text-center">Items</th>';
    $prod .= '<th scope="col" class="text-center">Quantity</th>';
    $prod .= '<th scope="col" class="text-center">Price</th>';
    $prod .= '</tr></thead><tbody>';
    $count =1;
    $generate ='';
    foreach ($product as $product) {
        if(in_array($product['PID'],$keys)){ //if current product is in keys array, which means selected, then find position 
            $position = array_search($product['PID'], $keys);
            //print_r($keys[$position]);
            //print_r($values[$position]);
            $temp = $product['PRICE']*$values[$position];
            $prod .= '<tr>';
            $prod .= '<td>'.$product['NAME'].'</td>';
            $prod .= '<td><input type="number" name="'.$product['NAME'].'cart" value="'.$values[$position].'" id="'.$keys[$position].'quantity" min="0" data-bind="value:replyNumber" /></td>';
            $prod .= '<td>'.$temp.'</td>';
            $prod .= '</tr>';
            $totalQuantity += $values[$position];
            $totalPrice += $temp;
            // <input type="hidden" name="amount_1" value="12.00" />
            // <input type="hidden" name="item_name_1" value="ADCDE" />
            $generate .='<input type="hidden" name="item_name_'.$count.'" value="'.$product['NAME'].'" />';
            $generate .='<input type="hidden" name="item_number_'.$count.'" value="'.$product['PID'].'" />';
            $generate .='<input type="hidden" name="quantity_'.$count.'" value="'.$values[$position].'" />';
            $generate .='<input type="hidden" name="amount_'.$count.'" value="'.$product['PRICE'].'" />';
            $count++;
        }  
    //     //if($product['PID']==$catid)
    //     //$prod .= '<div class="row"><div class="col-lg-3">';
    }
    $prod .= '<th scope="row">Total</th>';
    $prod .= '<th scope="row">'.$totalQuantity.'</th>';
    $prod .= '<th scope="row">'.$totalPrice.'</th>';
    $prod .= '</tbody></table>';
    echo $prod;
    echo $generate;
    // print_r($keys);
?>