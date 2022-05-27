<?php

namespace Esyede\Wabot\Messaging;

use Esyede\Wabot\Http\Initiator as HttpInitiator;
use Esyede\Wabot\Http\Request as HttpRequest;
use Esyede\Wabot\Helpers\Common;
use Closure;
use stdClass;

class MessageList
{
    private $initiator;
    private $request;
    private $buttonText;
    private $text;
    private $title;
    private $description;
    private $sections = [];

    public function __construct(HttpInitiator $initiator, HttpRequest $request)
    {
        $this->initiator = $initiator;
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
        $this->sections = $sections->toArray();
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
            ->post('message/list');
    }
}