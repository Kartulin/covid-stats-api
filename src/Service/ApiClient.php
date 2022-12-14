<?php

namespace App\Service;

use App\Exception\ApiException;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiClient
{
    private HttpClientInterface $client;
    private string $method = 'GET';
    private string $endpoint;
    private array $params = [];
    private LoggerInterface $logger;


    public function __construct(HttpClientInterface $covid19apiClient, LoggerInterface $httpClientLogger)
    {
        $this->client = $covid19apiClient;
        $this->logger = $httpClientLogger;
    }

    /**
     * @return array
     * @throws ApiException
     */
    private function _send(): array
    {
        try {
            $response = $this->client->request($this->method, $this->endpoint,
                ['query' => $this->params]);

            return $response->toArray();

        } catch (ExceptionInterface $e) {
            throw new ApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws ApiException
     */
    public function getCountriesSummary(): array
    {
        $this->endpoint = '/summary';

        return $this->_send();
    }

    /**
     * @throws ApiException
     */
    public function getTotalDayOneByCountrySlug(string $slug): array
    {
        $this->endpoint = '/total/dayone/country/' . $slug;

        return $this->_send();
    }
}