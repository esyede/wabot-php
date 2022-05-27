<?php

namespace Esyede\Wabot\Http;

use Closure;

class Initiator
{
    private $baseUrl;
    private $callback;
    private $webhookUrl;
    private $webhookEvents = [];

    public function __construct($baseUrl)
    {
        $baseUrl = rtrim($baseUrl, '/');
        $this->baseUrl = $baseUrl;
    }

    public function withWebhookUrl($webhookUrl)
    {
        $this->webhookUrl = $webhookUrl;
        return $this;
    }

    public function withAllowedWebhookEvents(array $events)
    {
        $this->webhookEvents = $events;
        return $this;
    }

    public function withCallback(Closure $callback)
    {
        $this->callback = $callback;
        return $this;
    }

    public function getKey()
    {
        // https://foo.com/instance/init?webhook_url=https://bar.com/webhooks&webhook_events=chat.upsert,group.create
        $endpoint = $this->baseUrl . '/instance/init';

        $endpoint .= $this->webhookUrl ? '?webhook_url=' . $this->webhookUrl : '';
        $endpoint .= empty($this->webhookEvents) ? '' : (false === strpos($endpoint, '?') ? '?' : '&')
            . 'webhook_events=' . (implode(',', $this->webhookEvents));

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $endpoint,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; TripayBot/1.0; +https://tripay.co.id/developer)',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ]);

        $raw = curl_exec($ch);
        $error = curl_error($ch);

        curl_close($ch);

        $data = json_decode($raw);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        if ($this->callback) {
            $callback = $this->callback;
            $callback(isset($data->key) ? $data->key : null);
        }

        return isset($data->key) ? $data->key : null;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }
}