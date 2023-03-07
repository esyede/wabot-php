<?php

namespace Esyede\Wabot\Http;

use Closure;

class Request
{
    private $baseUrl;
    private $headers = [];
    private $payloads = [];
    private $callback;
    private $userAgent;

    public function __construct($baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->userAgent = 'Mozilla/5.0 (compatible; TripayBot/1.0; +https://tripay.co.id/developer)';
    }

    public function withHeader($key, $value)
    {
        $this->headers[] = $key . ': ' . $value;
        return $this;
    }

    public function withRawBody($payloads)
    {
        $this->payloads = $payloads;
        return $this;
    }

    public function withJsonBody($payloads)
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

    public function get($endpoint, array $queryString = [])
    {
        return $this->request('get', $endpoint, $queryString);
    }

    public function post($endpoint, array $queryString = [])
    {
        return $this->request('post', $endpoint, $queryString);
    }

    public function delete($endpoint, array $queryString = [])
    {
        return $this->request('delete', $endpoint, $queryString);
    }

    public function request($method, $endpoint, array $queryString = [])
    {
        $endpoint = rtrim(trim($endpoint, '/'), '&');
        $endpoint = $this->baseUrl . '/' . $endpoint . '?' . http_build_query($queryString);
        $method = strtoupper($method);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->userAgent,
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
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->payloads);
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
            'data' => null,
            'raw' => $raw,
        ];

        if ($this->callback) {
            $callback = $this->callback;
            $callback($results);
        }

        return $results;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }
}
