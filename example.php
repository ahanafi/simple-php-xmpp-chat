<?php
require_once __DIR__ . '/vendor/autoload.php';
error_reporting(1);

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Fabiang\Xmpp\Options;
use Fabiang\Xmpp\Client;

use Fabiang\Xmpp\Protocol\Message;
use Norgul\Xmpp\Buffers\Response;

$logger = new Logger('xmpp');
$logger->pushHandler(new StreamHandler('php://stdout', Logger::ERROR));

$hostname       = 'web.ejabberd.app';
$port           = 5222;
$connectionType = 'tcp';
$address        = "$connectionType://$hostname:$port";

$username = 'ahmad';
$password = '123456';

$options = new Options($address);
$options->setLogger($logger)
    ->setUsername($username)
    ->setPassword($password);

$client = new Client($options);

$message = new Message('Hallo this is message sent at : ' . time(), 'hanafi@' . $hostname);
try {
    $client->connect();
    // $client->send($message);
    do {
        $response = new Response();
        echo $response->read();
    } while ($options->getConnection() !== null);

} catch (Exception $exception) {
    die("Error: " . $exception->getMessage());
}

