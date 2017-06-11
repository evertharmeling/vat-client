<?php

namespace VIESApi\Client;

use GuzzleHttp\Psr7\Request;
use Http\Client\Exception\RequestException;
use Http\Client\HttpClient;
use Psr\Http\Message\ResponseInterface;
use VIESApi\Exception\InvalidResponseException;
use VIESApi\Exception\TaxableObjectNotFoundException;
use VIESApi\Parser\VATParser;

/**
 * @author Evert Harmeling <evertharmeling@gmail.com>
 */
class Client
{
    const BASE_URL  = 'http://www.controleerbtwnummer.nl/api.php';

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var VATParser
     */
    private $responseParser;

    public function __construct(HttpClient $httpClient, VATParser $responseParser)
    {
        $this->httpClient = $httpClient;
        $this->responseParser = $responseParser;
    }

    /**
     * @param string $VATNumber
     */
    public function getInfo($VATNumber)
    {
        return $this->responseParser->parse($this->get([
            'vat_number' => $VATNumber
        ]));
    }

    /**
     * @param array $params
     *
     * @return array|\stdClass
     *
     * @throws RequestException
     */
    private function get(array $params = [])
    {
        $request = $this->createHttpGetRequest(self::BASE_URL, $params);

        $response = $this->httpClient->sendRequest($request);

        return $this->handleResponse($response);
    }

    /**
     * @param string $url
     * @param array $params
     *
     * @return Request
     */
    private function createHttpGetRequest($url, array $params = [])
    {
        $url .= (count($params) > 0 ? '?' . http_build_query($params, null, '&', PHP_QUERY_RFC3986) : '');

        return new Request('GET', $url);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return \stdClass
     * @throws InvalidResponseException
     * @throws TaxableObjectNotFoundException
     */
    private function handleResponse(ResponseInterface $response)
    {
        $result = json_decode((string) $response->getBody()->getContents());

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidResponseException('Could not parse response', $response);
        }

        if (is_object($result) && !$result->valid) {
            throw new TaxableObjectNotFoundException();
        }

        return $result;
    }
}
