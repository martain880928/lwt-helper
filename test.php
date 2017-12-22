<?php

require_once __DIR__ . '/src/Helper/Function.php';
require_once __DIR__ . '/src/init.php';

//use LwtHelper\Curl\Curl;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use LwtHelper\Redis\ARedisList;

$config = array(
    "hostname"   => "127.0.0.1",
    "port"       => 6379,
    "prefix"     => "",
    "database"   => 0,
    "serializer" => false,
);

$redis = new ARedisList('testList', $config);
$redis->add(json_encode(array(1,2,3)));
$data  = $redis->getData();

ee($data);
