<?php

require_once 'vendor/autoload.php';

//$logger = Elasticsearch\ClientBuilder::defaultLogger('app/this.log');

$hosts = ['user:qwerty123@myelk.duckdns.org:8080'];
$client = Elasticsearch\ClientBuilder::create()
                                     ->setHosts($hosts)
                                     ->setRetries(3)
//                                     ->setLogger($logger)
                                     ->build();

