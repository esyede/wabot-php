<?php

require __DIR__ . '/vendor/autoload.php';

// Setup
use Esyede\Wabot\Http\Initiator;
use Esyede\Wabot\Helpers\Log;

$log = new Log(__DIR__);

$initiator = new Initiator('https://my-wa-node.com');
$initiator
    ->withWebhookUrl('https://my-wa-site/webhook.php')
    ->withAllowedWebhookEvents([
        'device:qr',
        'device:connecting',
        'device:reconnecting',
        'device:connected',
        'device:disconnected',
        'message:new',
    ])
    ->withCallback(function ($response) use ($log) {
        $log->write($response);
    });

$key = null;

// Ambil dari storage jika key sudah tersimpan di storage. Ambil dari api jika belum
if (is_file($file = __DIR__ . '/key.txt') && strlen(file_get_contents($file)) > 25) {
    $key = json_decode(file_get_contents($file));
} else {
    $key = $initiator->getKey();
}

$baseUrl = $initiator->getBaseUrl();


// Buat instance koneksi dan request
use Esyede\Wabot\Http\Request;
use Esyede\Wabot\Connections\Connection;

$connection = new Connection($key, $baseUrl);
$request = new Request($key, $baseUrl);

// print_r($connection->restore('ggh7y838-8347-hjbcvj-dhb')); die;
// print_r($connection->restoreAll()); die;

// Test update webhook
print_r($connection->updateWebhook(
    'https://esyede.my.id/wa.php',
    [
        'device:qr',
        'device:connecting',
        'device:reconnecting',
        'device:connected',
        'device:disconnected',
        'message:new',
    ]
)); die;

// Get base64 qrCode
$base64qrCode = $connection->getQrBase64(function ($response) use ($log) {
    $log->write(json_encode($response));
});

print_r($base64qrCode); die;

// Get html qrCode
$htmlQrCode = $connection->scanQr(function ($response) use ($log) {
    $log->write(json_encode($response));
});

print_r($htmlQrCode); die;

use Esyede\Wabot\Messaging\Message;

$message = new Message($initiator, $request);


$result = $message->text('085707839650', 'Test text message', function ($response) use ($log) {
    $log->write(json_encode($response));
});

print_r($result); die;


$file = __DIR__ . '/test.png';
$result = $message->image('085707839650', $file, 'Test image message', function ($response) use ($log) {
    $log->write(json_encode($response));
});

print_r($result); die;


$file = __DIR__ . '/test.mp4';
$result = $message->video('085707839650', $file, 'Test video message', function ($response) use ($log) {
    $log->write(json_encode($response));
});

print_r($result); die;


$file = __DIR__ . '/test.mp3';
$result = $message->audio('085707839650', $file, function ($response) use ($log) {
    $log->write(json_encode($response));
});

print_r($result); die;


$file = __DIR__ . '/test.pdf';
$result = $message->document('085707839650', $file, function ($response) use ($log) {
    $log->write(json_encode($response));
});

print_r($result); die;


$file = 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/FullMoon2010.jpg/800px-FullMoon2010.jpg';
$result = $message->mediaUrl('085707839650', $file, 'Test mediaUrl', function ($response) use ($log) {
    $log->write(json_encode($response));
});

print_r($result); die;