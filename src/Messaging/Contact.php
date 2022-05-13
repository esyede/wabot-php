<?php

namespace Esyede\Wabot\Messaging;

use Esyede\Wabot\Http\Initiator as HttpInitiator;
use Esyede\Wabot\Http\Request as HttpRequest;
use Esyede\Wabot\Helpers\Common;
use Closure;
use stdClass;

class Contact
{
    private $initiator;
    private $request;
    private $phoneNumber;
    private $displayName;
    private $organizationName;

    public function __construct(HttpInitiator $initiator, HttpRequest $request)
    {
        $this->initiator = $initiator;
        $this->request = $request;
    }

    public function withPhoneNumber($phoneNumber)
    {
        $phoneNumber = Common::toIndonesianPhonePrefix($phoneNumber);
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function withFullName($fullName)
    {
        $fullName = Common::fixPersonName($fullName);
        $this->fullName = $fullName;
        return $this;
    }

    public function withDisplayName($displayName)
    {
        $displayName = Common::fixPersonName($displayName);
        $this->displayName = $displayName;
        return $this;
    }

    public function withOrganizationName($organizationName)
    {
        $organizationName = Common::fixPersonName($organizationName);
        $this->organizationName = $organizationName;
        return $this;
    }

    public function send(Closure $callback = null)
    {
        $vcard = [
            'phoneNumber' => $this->phoneNumber,
            'fullName' => $this->fullName,
            'displayName' => $this->displayName,
            'organization' => $this->organizationName,
        ];

        $payloads = new stdClass;
        $payloads->id = '';
        $payloads->vcard = (object) $vcard;
        $payloads = json_encode($payloads);

        return $this->request
            ->withRawBody($payloads)
            ->withCallback($callback)
            ->post('message/contact');
    }
}