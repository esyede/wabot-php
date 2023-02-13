<?php

namespace Esyede\Wabot\Others;

use Esyede\Wabot\Connections\Connection;
use Esyede\Wabot\Http\Request as HttpRequest;
use Esyede\Wabot\Helpers\Common;
use Closure;

class Other
{
    private $connection;
    private $request;

    public function __construct(Connection $connection, HttpRequest $request)
    {
        $this->connection = $connection;
        $this->request = $request;
    }

    public function isOnWhatsapp($phoneNumber, Closure $callback = null)
    {
        $phoneNumber = Common::toIndonesianPhonePrefix($phoneNumber);

        return $this->request
            ->withCallback($callback)
            ->get('misc/onwhatsapp', [
                'id' => $phoneNumber,
                'key' => $this->connection->getDeviceKey(),
            ]);
    }

    public function downloadProfilePicture($phoneNumber, Closure $callback = null)
    {
        $phoneNumber = Common::toIndonesianPhonePrefix($phoneNumber);

        return $this->request
            ->withCallback($callback)
            ->get('misc/downProfile', [
                'id' => $phoneNumber,
                'key' => $this->connection->getDeviceKey(),
            ]);
    }

    public function getUserStatus($phoneNumber, Closure $callback = null)
    {
        $phoneNumber = Common::toIndonesianPhonePrefix($phoneNumber);

        return $this->request
            ->withCallback($callback)
            ->get('misc/getStatus', [
                'id' => $phoneNumber,
                'key' => $this->connection->getDeviceKey(),
            ]);
    }

    public function blockUser($phoneNumber, Closure $callback = null)
    {
        $phoneNumber = Common::toIndonesianPhonePrefix($phoneNumber);

        return $this->request
            ->withCallback($callback)
            ->get('misc/blockUser', [
                'id' => $phoneNumber,
                'key' => $this->connection->getDeviceKey(),
            ]);
    }
}
