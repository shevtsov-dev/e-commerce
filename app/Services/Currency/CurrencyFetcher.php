<?php

declare(strict_types=1);

namespace App\Services\Currency;

use App\Services\Currency\Interfaces\CurrencyFetcherInterface;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Response;
use SimpleXMLElement;

class CurrencyFetcher implements CurrencyFetcherInterface
{
    /**
     * @const RATES_URL
     */
    private const RATES_URL = 'RATES_URL';

    /**
     * @param Factory $http
     */
    public function __construct(
        private readonly Factory $http,
    ) {
    }

    /**
     * @return array<string, mixed>
     *
     * @throws ConnectionException
     * @throws Exception
     */
    public function fetch(): array
    {
        $response = $this->makeRequest();

        $xml  = $this->processXmlResponse($response);
        $json = $this->convertXmlToJson($xml);

        return $this->decodeJson($json);
    }

    /**
     * @return Response
     *
     * @throws ConnectionException
     */
    private function makeRequest(): Response
    {
        $response = $this->http->withOptions(['verify' => false])
            ->get(env(self::RATES_URL));

        if ($response->failed()) {
            throw new ConnectionException(safe_trans('messages.request_failed', ['status' => $response->status()]));
        }

        return $response;
    }

    /**
     * @param Response $response
     *
     * @return SimpleXMLElement
     *
     * @throws Exception
     */
    private function processXmlResponse(Response $response): SimpleXMLElement
    {
        $xml = simplexml_load_string($response->body());

        if ($xml === false) {
            throw new Exception(safe_trans('messages.invalid_xml'));
        }

        return $xml;
    }

    /**
     * @param SimpleXMLElement $xml
     *
     * @return string
     *
     * @throws Exception
     */
    private function convertXmlToJson(SimpleXMLElement $xml): string
    {
        $json = json_encode($xml);

        if ($json === false) {
            throw new Exception(safe_trans('messages.invalid_json_encoding'));
        }

        return $json;
    }

    /**
     * @param string $json
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    private function decodeJson(string $json): array
    {
        $result = json_decode($json, true);

        if ($result === null) {
            throw new Exception(safe_trans('messages.invalid_json_decoding'));
        }

        return $result;
    }
}
