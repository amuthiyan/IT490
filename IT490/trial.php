<?php
require_once__DIR__ . '/vendor/autoload.php';
use PHPAmqpLib\Connection\AMQPStreamConnection;
use PHPAmqpLib\Message\AMQPMessage;

$conn = new AMQPStreamConnection('localhost',5762, 'test', 'test');
$channel = $conn->channel();

?>
