<?php

namespace Esyede\Wabot\Messaging;

use Esyede\Wabot\Http\Initiator as HttpInitiator;
use Esyede\Wabot\Http\Request as HttpRequest;
use Esyede\Wabot\Helpers\Common;
use Closure;
use stdClass;

class MediaWithButton
{
    private $initiator;
    private $request;
    private $headerTextCaption;
    private $buttons = [];
    private $footerTextCaption;
    private $imageUrl;

    public function __construct(HttpInitiator $initiator, HttpRequest $request)
    {
        $this->initiator = $initiator;
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

    public function withImage($imageUrl)
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    public function send(Closure $callback = null)
    {
        $payloads = new stdClass;
        $payloads->id = '';
        $payloads->btndata->text = $this->headerTextCaption;
        $payloads->btndata->buttons = $this->buttons;
        $payloads->btndata->footerText = $this->footerTextCaption;
        $payloads->btndata->image = $this->imageUrl;
        $payloads->btndata->mediaType = 'image';
        $payloads->btndata->mimeType = 'application/octet-stream';

        return $this->request
            ->withHeader('Content-Type', 'application/json')
            ->withJsonBody($payloads)
            ->withCallback($callback)
            ->post('message/MediaButton');
    }
}