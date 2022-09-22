<?php
require_once __DIR__ . '/vendor/autoload.php';

use Norgul\Xmpp\Options;
use Norgul\Xmpp\XmppClient;

/**
 * Get message from ahmad -> hanafi
 */
$host = 'web.ejabberd.app';
$port = 5222;
$receiver = 'ahmad';
$sender = 'hanafi';

// Server options
$options = new Options();
$options
    ->setHost($host)
    ->setPort($port)
    ->setUsername($receiver)
    ->setPassword('123456');

$client = new XmppClient($options);
$times = date('Y-m-d H:i:s');

try {
    $client->connect();
    $message = "Message from " . $sender . " at " . $times;
    $client->message->send(
        $message,
        $receiver . '@' . $host
    );
    echo "Message was sent!" . PHP_EOL;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
