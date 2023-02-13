<?php

require __DIR__ . '/vendor/autoload.php';

use Esyede\Wabot\Http\Request;
use Esyede\Wabot\Connections\Connection;
use Esyede\Wabot\Helpers\Log;
use Esyede\Wabot\Messaging\Message;

$log = new Log(__DIR__);
$baseUrl = 'http://localhost:3333';

$key = '11111';
$phone = '085330684679';

// Buat instance koneksi dan request

$request = new Request($baseUrl);
$connection = new Connection($key, $baseUrl);

$connection->init();

// Get base64 qrCode
$sleep = 3;
$base64qrCode = $connection->getQrBase64([], $sleep);
$base64qrCode = $connection->getInfo([])['raw'];

print_r($base64qrCode); die;

// Get html qrCode
$sleep = 0;
$htmlQrCode = $connection->scanQr([], $sleep, function ($response) use ($log) {
    $log->write(json_encode($response));
});

print_r($htmlQrCode); die;


$message = new Message($connection, $request);


$result = $message->text($phone, 'Test text message', function ($response) use ($log) {
    $log->write(json_encode($response));
});

print_r($result); die;


$file = __DIR__ . '/test.png';
$result = $message->image($phone, $file, 'Test image message', function ($response) use ($log) {
    $log->write(json_encode($response));
});

print_r($result); die;


$file = __DIR__ . '/test.mp4';
$result = $message->video($phone, $file, 'Test video message', function ($response) use ($log) {
    $log->write(json_encode($response));
});

print_r($result); die;


$file = __DIR__ . '/test.mp3';
$result = $message->audio($phone, $file, function ($response) use ($log) {
    $log->write(json_encode($response));
});

print_r($result); die;


$file = __DIR__ . '/test.pdf';
$result = $message->document($phone, $file, function ($response) use ($log) {
    $log->write(json_encode($response));
});

print_r($result); die;


$file = 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/FullMoon2010.jpg/800px-FullMoon2010.jpg';
$result = $message->mediaUrl($phone, $file, 'Test mediaUrl', function ($response) use ($log) {
    $log->write(json_encode($response));
});

print_r($result); die;
