<?php

namespace Pebble\ES;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Utils;

/**
 * Query
 *
 * @author mathieu
 */
class Request
{
    protected Client $guzzle;
    protected array $last_request = [];

    // -------------------------------------------------------------------------
    // Constructors
    // -------------------------------------------------------------------------

    /**
     * @param Client $guzzle
     */
    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * @param Client $guzzle
     * @return static
     */
    public static function make(Client $guzzle): static
    {
        return new static($guzzle);
    }

    // -------------------------------------------------------------------------
    // Requests
    // -------------------------------------------------------------------------

    /**
     * @param string $method
     * @param string $url
     * @param array $data
     * @return object
     */
    public function request(string $method, string $url, array $data = []): ?object
    {
        $params = [RequestOptions::HTTP_ERRORS => false];

        if ($data) {
            $params[RequestOptions::JSON] = $data;
            $params[RequestOptions::HEADERS] = ['Content-Type' => 'application/json'];
        }

        $this->last_request = $data;

        $res = $this->guzzle->request($method, $url, $params);
        $status = $res->getStatusCode();
        $content = $res->getBody()->getContents();

        if ($status >= 400 && $status !== 404) {
            $err = $method . ' ' . $url . PHP_EOL;
            if ($data) $err .= Utils::jsonEncode($data) . PHP_EOL;
            $err .= "Status : " . $status . PHP_EOL;
            $err .= $content . PHP_EOL;
            trigger_error($err);
        }

        return Utils::jsonDecode($content);
    }

    /**
     * @param string $url
     * @param array $data
     * @return object
     */
    public function get(string $url, array $data = []): object
    {
        return $this->request('GET', $url, $data);
    }

    /**
     * @param string $url
     * @param array $data
     * @return object
     */
    public function post(string $url, array $data = []): object
    {
        return $this->request('POST', $url, $data);
    }

    /**
     * @param string $url
     * @param array $data
     * @return object
     */
    public function put(string $url, array $data = []): object
    {
        return $this->request('PUT', $url, $data);
    }

    /**
     * @param string $url
     * @return object
     */
    public function delete(string $url): object
    {
        return $this->request('DELETE', $url);
    }

    /**
     * @param string $url
     * @param array $data
     * @return Result
     */
    public function search(string $url = '', array $data = []): Result
    {
        return new Result($this->post(rtrim($url, '/') . '/_search', $data));
    }

    /**
     * @param string $url
     * @param array|string $data
     * @param string $time
     * @return Result
     */
    public function scroll(string $url = '', string|array $data = [], string $time = '1m'): Result
    {
        $url = rtrim($url, '/') . '/_search';

        // First search
        if (!is_string($data)) {
            return new Result($this->post($url . '?scroll=' . $time, $data));
        }

        // Next search
        return new Result($this->post('/_search/scroll', [
            'scroll' => $time,
            'scroll_id' => $data
        ]));
    }

    /**
     * @param string $id
     * @return Result
     */
    public function clearScroll(string $id): Result
    {
        return new Result($this->delete('/_search/scroll/' . $id));
    }

    /**
     * @param string $url
     * @param array $data
     * @return object
     */
    public function bulk(string $url, array $data): object
    {
        $params = [
            RequestOptions::HTTP_ERRORS => false,
            RequestOptions::HEADERS => ['Content-Type' => 'application/x-ndjson'],
            RequestOptions::BODY => ''
        ];

        foreach ($data as $row) {
            $params[RequestOptions::BODY] .= Utils::jsonEncode($row) . "\n";
        }

        $res = $this->guzzle->post($url . '/_bulk', $params);
        $status = $res->getStatusCode();
        $content = $res->getBody()->getContents();

        if ($status >= 400) {
            $err = 'POST ' . $url . '/_bulk' . PHP_EOL;
            $err .= "Status : " . $status . PHP_EOL;
            $err .= $content . PHP_EOL;
            trigger_error($err);
        }

        return Utils::jsonDecode($content);
    }

    // -------------------------------------------------------------------------
    // Getter
    // -------------------------------------------------------------------------

    /**
     * @return array
     */
    public function lastRequest(): array
    {
        return $this->last_request;
    }

    // -------------------------------------------------------------------------
}
