<?php
require __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Start the connection and the channel
 */
$connection = new AMQPStreamConnection(getenv('RMQ_HOST'), getenv('RMQ_PORT'), getenv('RMQ_USER'), getenv('RMQ_PASS'));
$channel = $connection->channel();
$queue = 'embedding';
$channel->queue_declare($queue);

/**
 * Set up the data to be published
 */
$data = json_decode(file_get_contents(__DIR__ . '/../storage/recipes_full.json'), true);
$data = array_slice($data, 0, 3);


foreach ($data as $item) {
    $messageBody = json_encode($item);
    $channel->basic_publish(new AMQPMessage($messageBody, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]), '', $queue);
    echo " [x] Sent ", substr($messageBody,0, 150), "\n";
}

$channel->close();
$connection->close();
