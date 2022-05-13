<?php

namespace Esyede\Wabot\Http;

use Closure;

class Request
{
    private $accessKey;
    private $baseUrl;
    private $headers = [];
    private $bodyType;
    private $payloads;
    private $callback;

    public function __construct($accessKey, $baseUrl)
    {
        $this->accessKey = $accessKey;
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    public function withHeader($key, $value)
    {
        $this->headers[] = $key . ': ' . $value;
        return $this;
    }

    public function withRawBody(array $payloads)
    {
        $this->payloads = $payloads;
        return $this;
    }

    public function withJsonBody(array $payloads)
    {
        $this->payloads = json_encode($payloads);
        return $this;
    }

    public function withUrlEncodedBody(array $payloads)
    {
        $this->payloads = http_build_query($payloads);
        return $this;
    }

    public function withCallback(Closure $callback = null)
    {
        $this->callback = $callback;
        return $this;
    }

    public function get($endpoint)
    {
        return $this->request('get', $endpoint);
    }

    public function post($endpoint)
    {
        return $this->request('post', $endpoint);
    }

    public function delete($endpoint)
    {
        return $this->request('delete', $endpoint, $payloads);
    }

    public function request($method, $endpoint)
    {
        $endpoint = rtrim(trim($endpoint, '/'), '&');
        $endpoint = $this->baseUrl . '/' . $endpoint;
        // https://foo.com/bar?key=xxx, https://foo.com/bar?baz=qux&key=xxx
        $endpoint .= ((false !== strpos($endpoint, '?')) ? '&' : '?') . 'key=' . $this->accessKey;
        $method = strtoupper($method);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => $this->headers,
        ]);

        switch ($method) {
            case 'GET':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                break;

            case 'POST':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->payloads);
                break;

            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;

            default:
                curl_close($ch);
                throw new \Exception('Only GET, POST and DELETE are supported.');
        }

        $headers = curl_getinfo($ch);
        $raw = curl_exec($ch);
        $error = curl_error($ch);

        if ($error) {
            curl_close($ch);

            $results = [
                'endpoint' => $endpoint,
                'error' => true,
                'message' => $error,
                'data' => null,
                'raw' => $raw,
            ];

            if ($this->callback instanceof Closure) {
                $callback = $this->callback;
                $callback($results);
            }

            return $results;
        }

        if ($headers['content_type'] !== 'application/json') {
            $results = [
                'endpoint' => $endpoint,
                'error' => true,
                'message' => 'Non json response',
                'data' => null,
                'raw' => $raw,
            ];

            if ($this->callback) {
                $callback = $this->callback;
                $callback($results);
            }

            return $results;
        }

        if (json_last_error() !== JSON_ERROR_NONE) {
            curl_close($ch);

            $results = [
                'endpoint' => $endpoint,
                'error' => true,
                'message' => 'Unable to decode json data',
                'data' => null,
                'raw' => $raw,
            ];

            if ($this->callback) {
                $callback = $this->callback;
                $callback($results);
            }

            return $results;
        }

        curl_close($ch);

        $results = [
            'endpoint' => $endpoint,
            'error' => false,
            'message' => 'Request successful',
            'data' => $data,
            'raw' => $raw,
        ];

        if ($this->callback) {
            $callback = $this->callback;
            $callback($results);
        }

        return $results;
    }
}