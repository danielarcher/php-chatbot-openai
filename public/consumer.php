<?php
require __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * This is a simple consumer that will receive messages from the queue
 */
$connection = new AMQPStreamConnection(getenv('RMQ_HOST'), getenv('RMQ_PORT'), getenv('RMQ_USER'), getenv('RMQ_PASS'));
$channel = $connection->channel();
$queue = 'embedding';
$channel->queue_declare(queue: $queue);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

/**
 * This is the callback that will be executed when a message is received
 */
$callback = function ($msg) {
    echo " [x] Received ", substr($msg->body,0,50), "\n";
};

/**
 * This is the command that will consume the messages from the queue
 */
$channel->basic_consume(queue: $queue, callback: $callback);
while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$connection->close();