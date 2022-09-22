<?php

use Norgul\Xmpp\Options;
use Norgul\Xmpp\XmppClient;

require_once __DIR__ . '/vendor/autoload.php';

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
try {
    $client->connect();
    do {
        $response = $client->message->receive();
        if ($response) {
            print_r($response);
        }
    } while(true);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}