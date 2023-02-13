<?php

namespace Esyede\Wabot\Messaging;

use Esyede\Wabot\Connections\Connection;
use Esyede\Wabot\Http\Request as HttpRequest;
use Esyede\Wabot\Messaging\MessageLists\Section;
use Closure;
use stdClass;

class MessageList
{
    private $connection;
    private $request;
    private $buttonText;
    private $text;
    private $title;
    private $description;
    private $sections = [];

    public function __construct(Connection $connection, HttpRequest $request)
    {
        $this->connection = $connection;
        $this->request = $request;
    }

    public function withButtonText($text)
    {
        $this->buttonText = $text;
        return $this;
    }

    public function withText($text)
    {
        $this->text = $text;
        return $this;
    }

    public function withTitle($text)
    {
        $this->title = $text;
        return $this;
    }

    public function withDescription($text)
    {
        $this->description = $text;
        return $this;
    }

    public function withSections(Section $sections)
    {
        $this->sections = $sections->all();
        return $this;
    }

    public function send(Closure $callback = null)
    {
        $msgData = new stdClass;
        $msgData->buttonText = $this->buttonText;
        $msgData->text = $this->text;
        $msgData->title = $this->title;
        $msgData->description = $this->description;
        $msgData->sections = $this->sections;
        $msgData->listType = 0;

        $payloads = new stdClass;
        $payloads->id = '';
        $payloads->msgdata = $msgData;
        $payloads->listType = 0;

        return $this->request
            ->withRawBody(json_encode($payloads))
            ->withCallback($callback)
            ->post('message/list', ['key' => $this->connection->getDeviceKey()]);
    }
}
