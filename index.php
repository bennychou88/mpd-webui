<?php
require __DIR__.'/vendor/autoload.php';

use Dinusha\Mpd\MpdClient;

$client = new MpdClient('192.168.0.5', '6600');
$client->connect();
//$resp = $client->send(MpdClient::STATUS);
//print_r($resp);