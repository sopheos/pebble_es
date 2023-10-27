<?php

namespace Pebble\ES;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

/**
 * IndexAbstract
 *
 * @author mathieu
 */
class Request
{
    protected Client $guzzle;
    protected string $index;

    protected array $last_request = [];

    // -------------------------------------------------------------------------
    // Constructors
    // -------------------------------------------------------------------------

    /**
     * @param Client $guzzle
     */
    public function __construct(Client $guzzle, string $index)
    {
        $this->guzzle = $guzzle;
        $this->index = $index;
    }

    // -------------------------------------------------------------------------
    // Tools
    // -------------------------------------------------------------------------

    public function getIndex(): string
    {
        return $this->index;
    }

    public function setIndex(string $index): static
    {
        $this->index = $index;
        return $this;
    }

    // -------------------------------------------------------------------------
    // Index
    // -------------------------------------------------------------------------

    public function hasIndex()
    {
        return $this->jsonRequest('HEAD', $this->index)->status() === 200;
    }

    public function createIndex(array $mappings = [], array $settings = []): Response
    {
        $data = [];

        if ($settings) {
            $data['settings'] = $settings;
        }

        if ($mappings) {
            $data['mappings'] = $mappings;
        }

        return $this->jsonRequest('PUT', $this->index, $data);
    }

    public function deleteIndex()
    {
        return $this->jsonRequest('DELETE', $this->index);
    }

    // -------------------------------------------------------------------------
    // Document
    // -------------------------------------------------------------------------

    public function hasDoc(string $id): bool
    {
        return $this->jsonRequest('HEAD', $this->index . '/_doc/' . $id)->status() === 200;
    }

    public function getDoc(string $id): Response
    {
        return $this->jsonRequest('GET', $this->index . '/_doc/' . $id);
    }

    public function createDoc(array $data, ?string $id = null): Response
    {
        return $id
            ? $this->jsonRequest('PUT', $this->index . '/_doc/' . $id, $data)
            : $this->jsonRequest('POST', $this->index . '/_doc', $data);
    }

    public function deleteDoc(string $id): Response
    {
        return $this->jsonRequest('DELETE', $this->index . '/_doc/' . $id);
    }

    public function updateDoc(string $id, array $data): Response
    {
        return $this->jsonRequest('POST', $this->index . '/_update/' . $id, $data);
    }

    // -------------------------------------------------------------------------
    // Multi documents
    // -------------------------------------------------------------------------

    public function search(array $data = []): Response
    {
        return $this->jsonRequest('POST', $this->index . '/_search', $data);
    }

    public function scroll(string|array $data = [], string $time = '1m'): Response
    {
        // First search
        if (is_array($data)) {
            return $this->jsonRequest('POST', $this->index . '/_search?scroll=' . $time, $data);
        }

        // Next search
        return $this->jsonRequest('POST', '_search/scroll', [
            'scroll' => $time,
            'scroll_id' => $data
        ]);
    }

    /**
     * @param string $id
     * @return Result
     */
    public function clearScroll(string $id): Response
    {
        return $this->jsonRequest('DELETE', '_search/scroll', [
            'scroll_id' => $id
        ]);
    }

    /**
     * @param string $url
     * @param array $data
     * @return object
     */
    public function bulk(array $data): object
    {
        $params = [
            RequestOptions::HTTP_ERRORS => false,
            RequestOptions::HEADERS => ['Content-Type' => 'application/x-ndjson'],
            RequestOptions::BODY => ''
        ];

        foreach ($data as $row) {
            $params[RequestOptions::BODY] .= json_encode($row) . "\n";
        }

        $this->last_request = $data;
        return $this->request('POST', '_bulk', $params);
    }

    /**
     * @param AbstractRaw $query
     */
    public function updateByQuery(AbstractRaw $query, bool $proceedOnConflicts = true): Response
    {
        $conflicts = $proceedOnConflicts ? '?conflicts=proceed' : '';
        return $this->jsonRequest('POST', $this->index . '/_update_by_query' . $conflicts, $query->raw());
    }

    /**
     * @param AbstractRaw $query
     */
    public function deleteByQuery(AbstractRaw $query, bool $proceedOnConflicts = true): Response
    {
        $conflicts = $proceedOnConflicts ? '?conflicts=proceed' : '';
        return $this->jsonRequest('POST', $this->index . '/_delete_by_query' . $conflicts, $query->raw());
    }

    // -------------------------------------------------------------------------
    // Requests
    // -------------------------------------------------------------------------

    /**
     * @param string $method
     * @param string $url
     * @param array $data
     * @return Response
     */
    public function jsonRequest(string $method, string $url, array $data = []): Response
    {
        $params = [RequestOptions::HTTP_ERRORS => false];

        if ($data) {
            $params[RequestOptions::JSON] = $data;
            $params[RequestOptions::HEADERS] = ['Content-Type' => 'application/json'];
        }

        return $this->request($method, $url, $params);
    }

    public function request(string $method, string $url, array $params): Response
    {
        try {
            $this->last_request = $params;
            $res = $this->guzzle->request($method, $url, $params);
            return new Response($res->getStatusCode(), $res->getBody()->getContents());
        } catch (\Exception $ex) {
            trigger_error($ex);
            return new Response(500, $ex->getMessage());
        }
    }

    /**
     * @return array
     */
    public function lastRequest(): array
    {
        return $this->last_request;
    }

    // -------------------------------------------------------------------------
}
