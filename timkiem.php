<?php 
use Elasticsearch\ClientBuilder;

$hosts = ['http://localhost:9200'];

$client = ClientBuilder::create()->setHosts($hosts)->build();

?>