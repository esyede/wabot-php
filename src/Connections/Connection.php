<?php

namespace Esyede\Wabot\Connections;

use Esyede\Wabot\Http\Request as HttpRequest;
use Closure;

class Connection
{
    private $accessKey;
    private $request;

    public function __construct($accessKey, $baseUrl)
    {
        $this->accessKey = $accessKey;
        $this->request = new HttpRequest($accessKey, $baseUrl);
    }

    public function scanQr($sleep = 3, Closure $callback = null)
    {
        sleep((int) $sleep);

        return $this->request
            ->withCallback($callback)
            ->get('instance/qr');
    }

    public function getQrBase64($sleep = 3, Closure $callback = null)
    {
        sleep((int) $sleep);

        return $this->request
            ->withCallback($callback)
            ->get('instance/qrbase64');
    }

    public function getInfo(Closure $callback = null)
    {
        return $this->request
            ->withCallback($callback)
            ->get('instance/info');
    }

    public function restore(array $deviceKeys, Closure $callback = null)
    {
        $deviceKeys = array_values($deviceKeys);
        $deviceKeys = implode(',', $deviceKeys);

        return $this->request
            ->withCallback($callback)
            ->get('instance/restore?only_keys=' . $deviceKeys);
    }

    public function restoreAll(Closure $callback = null)
    {
        return $this->request
            ->withCallback($callback)
            ->get('instance/restore');
    }

    public function delete(Closure $callback = null)
    {
        return $this->request
            ->withCallback($callback)
            ->delete('instance/delete');
    }

    public function logout(Closure $callback = null)
    {
        return $this->request
            ->withCallback($callback)
            ->delete('instance/logout');
    }

    public function updateWebhook($webhookUrl, array $webhookEvents)
    {
        return $this->request
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
            ->withUrlEncodedBody([
                'webhook_url' => $webhookUrl,
                'webhook_events' => implode(',', array_values($webhookEvents)),
            ])
            ->post('/instance/webhook');
    }
}