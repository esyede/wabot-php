<?php

namespace Esyede\Wabot\Messaging;

use Esyede\Wabot\Connections\Connection;
use Esyede\Wabot\Http\Request as HttpRequest;
use Esyede\Wabot\Helpers\Common;
use Closure;
use CURLFile;

class Message
{
    private $connection;
    private $request;

    public function __construct(Connection $connection, HttpRequest $request)
    {
        $this->connection = $connection;
        $this->request = $request;
    }

    public function text($phoneNumber, $message, Closure $callback = null)
    {
        $phoneNumber = Common::toIndonesianPhonePrefix($phoneNumber);
        $payloads = ['id' => $phoneNumber, 'message' => $message];

        return $this->request
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
            ->withUrlEncodedBody($payloads)
            ->withCallback($callback)
            ->post('message/text', ['key' => $this->connection->getDeviceKey()]);
    }

    public function image($phoneNumber, $imagePathAbsolute, $captionText, Closure $callback = null)
    {
        $phoneNumber = Common::toIndonesianPhonePrefix($phoneNumber);
        $payloads = [
            'id' => $phoneNumber,
            'file' => new CURLFile($imagePathAbsolute),
            'caption' => $captionText,
        ];

        return $this->request
            ->withRawBody($payloads)
            ->withCallback($callback)
            ->post('message/image', ['key' => $this->connection->getDeviceKey()]);
    }

    public function video($phoneNumber, $videoPath, $captionText, Closure $callback = null)
    {
        $phoneNumber = Common::toIndonesianPhonePrefix($phoneNumber);
        $payloads = [
            'id' => $phoneNumber,
            'file' => new CURLFile($videoPath),
            'caption' => $captionText,
        ];

        return $this->request
            ->withRawBody($payloads)
            ->withCallback($callback)
            ->post('message/video', ['key' => $this->connection->getDeviceKey()]);
    }

    public function audio($phoneNumber, $audioPath, Closure $callback = null)
    {
        $phoneNumber = Common::toIndonesianPhonePrefix($phoneNumber);
        $payloads = [
            'id' => $phoneNumber,
            'file' => new CURLFile($audioPath),
        ];

        return $this->request
            ->withRawBody($payloads)
            ->withCallback($callback)
            ->post('message/audio', ['key' => $this->connection->getDeviceKey()]);
    }

    public function document($phoneNumber, $documentPath, Closure $callback = null)
    {
        $phoneNumber = Common::toIndonesianPhonePrefix($phoneNumber);
        $payloads = [
            'id' => $phoneNumber,
            'file' => new CURLFile($documentPath),
        ];

        return $this->request
            ->withRawBody($payloads)
            ->withCallback($callback)
            ->post('message/doc', ['key' => $this->connection->getDeviceKey()]);
    }

    public function mediaUrl($phoneNumber, $fileUrl, $captionText, Closure $callback = null)
    {
        $phoneNumber = Common::toIndonesianPhonePrefix($phoneNumber);
        $payloads = [
            'id' => $phoneNumber,
            'url' => $fileUrl,
            'type' => 'document',
            'mimetype' => 'application/octet-stream',
            'caption' => $captionText,
        ];

        return $this->request
            ->withRawBody($payloads)
            ->withCallback($callback)
            ->post('message/mediaurl', ['key' => $this->connection->getDeviceKey()]);
    }
}
