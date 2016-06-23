<?php
/**
 * This file is part of the Paydemic.PaydemicPhpSdk
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Paydemic\Internal\HttpClient;

use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\RejectedPromise;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7;
use Paydemic\Internal\Exception\HttpException;
use Paydemic\Internal\Logger;

class HttpClientBasedOnGuzzle implements HttpClientInterface
{
    private $log;
    private $client;

    public function __construct($host, $timeout)
    {
        $this->log =
            Logger::getLogger('PaydemicPhpSdk.HttpClientBasedOnGuzzle');

        $handler = new CurlHandler();
        $stack = HandlerStack::create($handler); // Wrap w/ middleware

        $this->client = new Client([
            'handler' => $stack,
            'base_uri' => "https://$host/api",
            'timeout'  => $timeout,
            'headers' => [ 'Host' => $host ]
        ]);
    }

    public function request($method, $path = null, array $options = [])
    {
        return $this->client->requestAsync($method, $path, $options)
            ->then(
                function (ResponseInterface $res) use ($method, $path, $options) {
                    $resultBodyContents = $res->getBody()->getContents();
                    $resultJson = json_decode($resultBodyContents, true);
                    if (isset($resultJson['errorMessage'])) {
                        $this->log->err($resultBodyContents);
                        $httpException = new HttpException(
                            "Paydemic.Internal.HttpException: " . $resultJson['errorMessage'],
                            null,
                            null,
                            $method,
                            $path,
                            $options
                        );
                        return new RejectedPromise($httpException);
                    }

                    $httpResponse = new HttpResponse(
                        $resultBodyContents,
                        $res->getStatusCode(),
                        $res->getReasonPhrase(),
                        $res->getHeaders(),
                        $res->getProtocolVersion()
                    );
                    return new FulfilledPromise($httpResponse);
                },
                function (RequestException $e) use ($method, $path, $options) {
                    $httpException = new HttpException(
                        "Paydemic.Internal.HttpException: " . $e->getMessage(),
                        $e->getCode(),
                        $e,
                        $method,
                        $path,
                        $options
                    );
                    return new RejectedPromise($httpException);
                }
            );
    }
}
