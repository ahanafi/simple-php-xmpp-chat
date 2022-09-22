<?php

use Norgul\Xmpp\Options;
use Norgul\Xmpp\XmppClient;

require_once __DIR__ . '/vendor/autoload.php';

// Helper
$isError = false;
$errorMessage = [];
$successMessage = '';

if (isset($_POST['submit'])) {

    $hostname = $_POST['hostname'];
    $sender = $_POST['sender'];
    $password = $_POST['password'];
    $recipient = $_POST['recipient'];
    $message = $_POST['message'];

    // Validate hostname
    if (empty(trim($hostname)) || $hostname === '') {
        $isError = true;
        $errorMessage[] = 'Hostname is required';
    }

    // Validate sender
    if (empty(trim($sender)) || $sender === '') {
        $isError = true;
        $errorMessage[] = 'Sender is not valid!';
    }

    // Validate password
    if (empty(trim($password)) || $password === '') {
        $isError = true;
        $errorMessage[] = 'Password is required';
    }

    // Validate recipient
    if (empty(trim($recipient)) || $recipient === '') {
        $isError = true;
        $errorMessage[] = 'Recipient is not valid!';
    }

    // Validate Message
    if (empty(trim($message)) || $message === '') {
        $isError = true;
        $errorMessage[] = 'Message is required';
    }

    if (!$isError && count($errorMessage) === 0 ) {

        // Server options
        $options = new Options();
        $options
            ->setHost($hostname)
            ->setUsername($sender)
            ->setPassword($password);

        $client = new XmppClient($options);

        $recipient = $recipient . '@' . $hostname;
        $times = date('Y-m-d H:i:s');

        try {

            $client->connect();
            $client->message->send($message, $recipient);
            $successMessage = 'Message was sent!';

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

}




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <!-- Optional JavaScript -->

    <title>Message Forwarder using XMPP</title>
</head>
<style>
    .container {
        margin-top: 3%;
    }
    textarea{
        resize: none;
    }
    .row {
        margin-bottom: 10px;
    }
</style>
<body>
    <div class="container">
        <h4>Message Forwarder using XMPP</h4>
        <hr>
        <?php if (count($errorMessage) > 0 && $isError): ?>
            <ul>
            <?php foreach ($errorMessage as $errMsg): ?>
                <li><span class="text-danger"><?php echo $errMsg ?></span></li>
            <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <span class="text-success"><?php echo $successMessage ?></span> <br>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="row">
                <label for="hostname" class="col-sm-2">IP Ejabberd Server (Hostname)</label>
                <div class="col-sm-5">
                    <input type="text" id="hostname" name="hostname" required class="form-control" placeholder="Example: IP / Ejabberd Server hostname">
                </div>
            </div>

            <div class="row">
                <label for="sender" class="col-sm-2">Sender</label>
                <div class="col-sm-5">
                    <input type="text" id="sender" name="sender" required class="form-control" placeholder="Example: john">
                </div>
            </div>

            <div class="row">
                <label for="password" class="col-sm-2">Sender Password</label>
                <div class="col-sm-5">
                    <input type="password" id="password" name="password" required class="form-control" placeholder="Example: 123456">
                </div>
            </div>

            <div class="row">
                <label for="recipient" class="col-sm-2">Recipient</label>
                <div class="col-sm-5">
                    <input type="text" id="recipient" name="recipient" required class="form-control" placeholder="Example: doe">
                </div>
            </div>

            <div class="row">
                <label for="message" class="col-sm-2">Message</label>
                <div class="col-sm-5">
                    <textarea name="message" required placeholder="Type your message here..." id="message" rows="5" class="form-control"></textarea>
                    <div class="mt-2">
                        <button type="submit" name="submit" class="btn btn-primary">Submit Message</button>
                    </div>
                </div>
            </div>

        </form>
    </div>

</body>

</html>