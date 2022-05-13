<?php

namespace Esyede\Wabot\Messaging;

use Esyede\Wabot\Http\Initiator as HttpInitiator;
use Esyede\Wabot\Http\Request as HttpRequest;
use Esyede\Wabot\Helpers\Common;
use Closure;
use CURLFile;

class Message
{
    private $initiator;
    private $request;

    public function __construct(HttpInitiator $initiator, HttpRequest $request)
    {
        $this->initiator = $initiator;
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
            ->post('message/text');
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
            ->post('message/image');
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
            ->post('message/video');
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
            ->post('message/audio');
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
            ->post('message/doc');
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
            ->post('message/mediaurl');
    }
}