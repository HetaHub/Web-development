<?php
$db = new PDO('sqlite:C:\xampp\htdocs\cart.db');
$statement= $db->query("SELECT * FROM PRODUCTS");
    $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
    var_dump($rows);
?>