<?php

namespace Esyede\Wabot\Messaging;

use Esyede\Wabot\Connections\Connection;
use Esyede\Wabot\Http\Request as HttpRequest;
use Esyede\Wabot\Helpers\Common;
use Closure;
use stdClass;

class Contact
{
    private $connection;
    private $request;
    private $fullName;
    private $phoneNumber;
    private $displayName;
    private $organizationName;

    public function __construct(Connection $connection, HttpRequest $request)
    {
        $this->connection = $connection;
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
        $vcard = new stdClass;

        $vcard->phoneNumber = $this->phoneNumber;
        $vcard->fullName = $this->fullName;
        $vcard->displayName = $this->displayName;
        $vcard->organization = $this->organizationName;

        $payloads = new stdClass;

        $payloads->id = '';
        $payloads->vcard = $vcard;

        return $this->request
            ->withRawBody(json_encode($payloads))
            ->withCallback($callback)
            ->post('message/contact', ['key' => $this->connection->getDeviceKey()]);
    }
}
