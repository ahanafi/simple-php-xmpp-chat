<?php
require_once __DIR__ . '/vendor/autoload.php';

use Fabiang\Xmpp\Options;
use Fabiang\Xmpp\Client;
use Fabiang\Xmpp\Protocol\Message;

/**
 * Send message from hanafi -> ahmad
 */

$address = 'tcp://web.ejabberd.app:5222';

$sender = 'hanafi';
$receiver = 'ahmad';

// Server options
$options = new Options($address);
$options->setUsername($sender)
        ->setPassword('123456');

$client = new Client($options);
$times = date('Y-m-d H:i:s');

try {
    $client->connect();
    $message = new Message();
    $message->setMessage('Message sent to ' . $receiver . ' at ' . $times)
        ->setTo(  $receiver. '@web.ejabberd.app');
    $client->send($message);
    echo "Sent!" . PHP_EOL;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}