<?php
require_once __DIR__ . '/vendor/autoload.php';

use BirknerAlex\XMPPHP\XMPP;


$conn = new XMPP(
    'web.ejabberd.app',
    5222,
    'ahmad',
    '123456',
    'xmpphp',
    'altamides-local',
);
$conn->autoSubscribe();
$vcard_request = array();
try {
    $conn->connect();
    while (!$conn->isDisconnected()) {
        $payloads = $conn->processUntil(array(
            'message',
            'presence',
            'end_stream',
            'session_start',
            'vcard'
        ));
        foreach($payloads as $event) {
            $pl = $event[1];
            switch($event[0]) {
                case 'message':
                    $msgBody = $pl['body'];
                    print "-------------------------------------------------\n";
                    print "Message from: {$pl['from']}\n";
                    if ($pl['subject']) print "Subject: {$pl['subject']}\n";
                    print $msgBody . "\n";
                    print "-------------------------------------------------\n";
                    $conn->message(
                        $pl['from'],
                        $body = "Thanks for sending me $msgBody",
                        $type=$pl['type']
                    );
                    $cmd = explode(' ', $msgBody);
                    if ($cmd[0] === 'quit') $conn->disconnect();
                    if ($cmd[0] === 'break') $conn->send("</end>");
                    if ($cmd[0] === 'vcard') {
                        if(!($cmd[1])) $cmd[1] = $conn->user . '@' . $conn->server;
                        $vcard_request[$pl['from']] = $cmd[1];
                        $conn->getVCard($cmd[1]);
                    }
                    break;
                case 'presence':
                    print "Presence: {$pl['from']} [{$pl['show']}] {$pl['status']}\n";
                    break;
                case 'session_start':
                    print "Session Start\n";
                    $conn->getRoster();
                    $conn->presence($status = "Woo!");
                    break;
                case 'vcard':
                    // Check to see who requested this vcard
                    $deliver = array_keys($vcard_request, $pl['from']);
                    // Work through the array to generate a message
                    print_r($pl);
                    $msg = '';
                    foreach($pl as $key => $item) {
                        $msg .= "$key: ";
                        if (is_array($item)) {
                            $msg .= "\n";
                            foreach ($item as $subkey => $subitem) {
                                $msg .= "  $subkey: $subitem\n";
                            }
                        } else {
                            $msg .= "$item\n";
                        }
                    }
                    // Deliver the vcard msg to everyone that requested that vcard
                    foreach ($deliver as $sendjid) {
                        // Remove the note on requests as we send out the message
                        unset($vcard_request[$sendjid]);
                        $conn->message($sendjid, $msg, 'chat');
                    }
                    break;

            }
        }
    }
} catch(\BirknerAlex\XMPPHP\Exception $e) {
    die("Error: " . $e->getMessage());
}