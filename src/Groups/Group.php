<?php

namespace Esyede\Wabot\Groups;

use Esyede\Wabot\Connections\Connection;
use Esyede\Wabot\Http\Request as HttpRequest;
use Esyede\Wabot\Helpers\Common;
use Closure;

class Group
{
    private $connection;
    private $request;

    public function __construct(Connection $connection, HttpRequest $request)
    {
        $this->connection = $connection;
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
            ->post('group/create', ['key' => $this->connection->getDeviceKey()]);
    }

    public function listAll(Closure $callback = null)
    {
        return $this->request
            ->withCallback($callback)
            ->get('group/listall', ['key' => $this->connection->getDeviceKey()]);
    }
}
