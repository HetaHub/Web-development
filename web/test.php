<?php
$resp='mc_gross=0.10&protection_eligibility=Eligible&address_status=confirmed&item_number1=1&payer_id=XP6XA4X7M8JWG&address_street=71566176 Sky E137 S&payment_date=07:33:39 Apr 11, 2022 PDT&payment_status=Completed&charset=windows-1252&address_zip=W185744&first_name=John&mc_fee=0.10&address_country_code=HK&address_name=Doe John&notify_version=3.9&custom=1649687598,$2y$10$epUAZOyNHzwyx7x0efSLm.NCYyMGAZlTrP9p8LDt/kI8CQ38NciBK,guest1@gmail.com&payer_status=verified&business=sb-vcgoz15238775@business.example.com&address_country=Hong Kong&num_cart_items=1&address_city=Posta&verify_sign=AOtczX45znSBSTbrGm5n07LUfHKVAwprxS6aFR6Wys1IYeydWHC.IXz9&payer_email=sb-bmyaa15618655@personal.example.com&txn_id=3ET39178S1542444Y&payment_type=instant&last_name=Doe&item_name1=Dark Souls III&address_state=Libisia&receiver_email=sb-vcgoz15238775@business.example.com&payment_fee=&shipping_discount=0.00&quantity1=1&insurance_amount=0.00&receiver_id=LSA57EQZL4QSA&txn_type=cart&discount=0.00&mc_gross_1=0.10&mc_currency=HKD&residence_country=HK&test_ipn=1&shipping_method=Default&transaction_subject=&payment_gross=&ipn_track_id=57e80773dc1e';
$fp = fsockopen("localhost", 80, $errno, $errstr, 30);


fwrite($fp, "POST http://localhost/web/ipn.php HTTP/1.1\r\n");
fwrite($fp, "Host: localhost \r\n");
fwrite($fp, "Content-Type: application/x-www-form-urlencoded\r\n");
fwrite($fp, "Content-Length: ".strlen($resp)."\r\n");
fwrite($fp, "Connection: close\r\n");
fwrite($fp, "\r\n");

fwrite($fp, $resp);
header('Content-type: text/plain');
while (!feof($fp)) {
    echo fgets($fp, 1024);
}
// header("Location: http://localhost/web/ipn.php");
// exit();
?>