<?php

namespace Esyede\Wabot\Connections;

use Esyede\Wabot\Http\Request as HttpRequest;
use Closure;

class Connection
{
    private $deviceKey;
    private $request;

    public function __construct($deviceKey, $baseUrl)
    {
        $this->deviceKey = $deviceKey;
        $this->request = new HttpRequest($baseUrl);
    }


    public function init(array $queryString = [], $sleep = 3, Closure $callback = null)
    {
        sleep((int) $sleep);

        $queryString['key'] = $this->deviceKey;
        return $this->request
            ->withCallback($callback)
            ->get('instance/init', $queryString);
    }

    public function scanQr(array $queryString = [], $sleep = 3, Closure $callback = null)
    {
        sleep((int) $sleep);

        $queryString['key'] = $this->getDeviceKey();
        return $this->request
            ->withCallback($callback)
            ->get('instance/qr', $queryString);
    }

    public function getQrBase64(array $queryString = [], $sleep = 3, Closure $callback = null)
    {
        sleep((int) $sleep);

        $queryString['key'] = $this->getDeviceKey();
        return $this->request
            ->withCallback($callback)
            ->get('instance/qrbase64', $queryString);
    }

    public function getInfo(array $queryString = [], Closure $callback = null)
    {
        $queryString['key'] = $this->getDeviceKey();
        return $this->request
            ->withCallback($callback)
            ->get('instance/info', $queryString);
    }

    public function restore(array $queryString = [], Closure $callback = null)
    {
        $queryString['key'] = $this->getDeviceKey();
        return $this->request
            ->withCallback($callback)
            ->get('instance/restore', $queryString);
    }

    public function restoreAll(Closure $callback = null)
    {
        $queryString['key'] = $this->getDeviceKey();
        return $this->request
            ->withCallback($callback)
            ->get('instance/restore');
    }

    public function delete(array $queryString = [], Closure $callback = null)
    {
        $queryString['key'] = $this->getDeviceKey();
        return $this->request
            ->withCallback($callback)
            ->delete('instance/delete', $queryString);
    }

    public function logout(array $queryString =[], Closure $callback = null)
    {
        $queryString['key'] = $this->getDeviceKey();
        return $this->request
            ->withCallback($callback)
            ->delete('instance/logout', $queryString);
    }

    public function getDeviceKey()
    {
        return $this->deviceKey;
    }
}
