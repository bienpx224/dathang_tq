<?php
//ini_set('date.timezone','UTC');//中国时区也可以
define('TOKEN',cadd($payr['paykey']));//测试:'4847fed22494dc22b1b1a478b34e374e0b429608f31adf289704b4ea093e60a8'
//define("API_URL", "https://apitest.nihaopay.com/v1.2");//测试用
define("API_URL", "https://api.nihaopay.com/v1.1");//正式用

$siteurl	= str_ireplace('http://','https://',cadd($siteurl));//本接口需要SSL
define("RETURN_URL", "{$siteurl}api/pay/NihaoPay/notify.php");//服务器返回post方式
define("CALLBACK_URL", "{$siteurl}api/pay/NihaoPay/notify.php?ret=get");//浏览器返回get方式
