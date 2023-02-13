<?php

namespace Esyede\Wabot\Messaging;

use Esyede\Wabot\Connections\Connection;
use Esyede\Wabot\Http\Request as HttpRequest;
use Esyede\Wabot\Helpers\Common;
use Closure;
use stdClass;

class Template
{
    private $connection;
    private $request;
    private $headerTextCaption;
    private $buttons = [];
    private $footerTextCaption;

    public function __construct(Connection $connection, HttpRequest $request)
    {
        $this->connection = $connection;
        $this->request = $request;
    }

    public function withHeaderText($textCaption)
    {
        $this->headerTextCaption = $textCaption;
        return $this;
    }

    public function withReplyButton($buttonCaption)
    {
        $this->buttons[] = (object) [
            'type' => 'replyButton',
            'title' => $buttonCaption,
        ];

        return $this;
    }

    public function withUrlButton($buttonCaption, $targetUrl)
    {
        $this->buttons[] = (object) [
            'type' => 'urlButton',
            'title' => $buttonCaption,
            'payload' => $targetUrl,
        ];

        return $this;
    }

    public function withCallButton($buttonCaption, $phoneNumber)
    {
        $phoneNumber = Common::toIndonesianPhonePrefix($phoneNumber);
        $this->buttons[] = (object) [
            'type' => 'callButton',
            'title' => $buttonCaption,
            'payload' => $phoneNumber,
        ];

        return $this;
    }

    public function withFooterText($textCaption)
    {
        $this->footerTextCaption = $textCaption;
        return $this;
    }

    public function send(Closure $callback = null)
    {
        $payloads = new stdClass;
        $payloads->id = '';
        $payloads->btndata->text = $this->headerTextCaption;
        $payloads->btndata->buttons = $this->buttons;
        $payloads->btndata->footerText = $this->footerTextCaption;

        return $this->request
            ->withHeader('Content-Type', 'application/json')
            ->withJsonBody($payloads)
            ->withCallback($callback)
            ->post('message/button', ['key' => $this->connection->getDeviceKey()]);
    }
}
