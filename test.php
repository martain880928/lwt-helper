<?php
require_once __DIR__ . '/vendor/autoload.php'; 
require_once __DIR__ . '/src/helper/Function.php';

use LwtHelper\curl\Curl;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$url = "http://www.huomaotv.com/api?a=live_list&type=live&l=200";

$data = Curl::sendGet($url);

ee($data);
