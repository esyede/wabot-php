<?php

namespace Esyede\Wabot\Groups;

use Esyede\Wabot\Http\Initiator as HttpInitiator;
use Esyede\Wabot\Http\Request as HttpRequest;
use Esyede\Wabot\Helpers\Common;
use Closure;

class Group
{
    private $initiator;
    private $request;

    public function __construct(HttpInitiator $initiator, HttpRequest $request)
    {
        $this->initiator = $initiator;
        $this->request = $request;
    }

    public function create($groupName, array $participantPhoneNumbers, Closure $callback = null)
    {
        $participantPhoneNumbers = array_map(function ($phoneNumber) {
            return Common::toIndonesianPhonePrefix($phoneNumber);
        }, array_values($participantPhoneNumbers));

        $payloads = (object) [
            'name' => $groupName,
            'users' => $participantPhoneNumbers,
        ];

        return $this->request
            ->withHeader('Content-Type', 'application/json')
            ->withJsonBody($payloads)
            ->withCallback($callback)
            ->post('group/create');
    }

    public function listAll(Closure $callback = null)
    {
        return $this->request
            ->withCallback($callback)
            ->get('group/listall');
    }
}
