<?php
/**
 * This file is part of the Paydemic.PaydemicPhpSdk
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Paydemic\Internal\HttpClient;

use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\RejectedPromise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use Paydemic\Internal\Exception\HttpException;
use Paydemic\Internal\Logger;
use Aws\Credentials\Credentials;
use Aws\Signature\SignatureV4;

class HttpClientBasedOnGuzzle implements HttpClientInterface
{
    private $baseuri;
    private $client;
    private $log;
    private $key;
    private $secret;
    private $signatureV4;

    public function __construct(
        $key,
        $secret,
        $region,
        $host,
        $timeout = 20.0
    ) {
        $this->log =
            Logger::getLogger('PaydemicPhpSdk.HttpClientBasedOnGuzzle');
        $this->baseuri = "https://$host";

        $this->key = $key;
        $this->secret = $secret;

        $handler = new CurlHandler();
        $stack = HandlerStack::create($handler); // Wrap w/ middleware

        $this->client = new Client([
            'handler' => $stack,
            'base_uri' => $this->baseuri,
            'timeout'  => $timeout,
            'headers' => [
                'Host' => $host,
                'User-Agent' => 'paydemic-php/v1.0.0'
            ]
        ]);

        $this->signatureV4 = new SignatureV4("execute-api", $region);
    }

    public function signedRequest(
        $method,
        $token,
        $tokenExpires,
        $path = null,
        $body = null
    ) {
        $signedRequest = $this->signatureV4->signRequest(
            new Request(
                $method,
                $this->baseuri . ($path ? $path : ''),
                [],
                $body
            ),
            new Credentials($this->key, $this->secret, $token, $tokenExpires)
        );
        return $this->sendAsync($signedRequest);
    }

    public function unsignedRequest(
        $method,
        $path = null,
        $body = null
    ) {
        return $this->sendAsync(
            new Request(
                $method,
                $this->baseuri . ($path ? $path : ''),
                [],
                $body
            )
        );
    }

    private function sendAsync(RequestInterface $request)
    {
        return $this->client->sendAsync($request)->then(
            function (ResponseInterface $res) use ($request) {
                $resultBodyContents = $res->getBody()->getContents();
                $resultJson = json_decode($resultBodyContents, true);
                if (isset($resultJson['errorMessage'])) {
                    $this->log->err($resultBodyContents);
                    $httpException = new HttpException(
                        "Paydemic.Internal.HttpException: " . $resultJson['errorMessage'],
                        null,
                        null,
                        $request->getMethod(),
                        $request->getUri()->getPath(),
                        []
                    );
                    return new RejectedPromise($httpException);
                }

                return new HttpResponse(
                    $resultBodyContents,
                    $res->getStatusCode(),
                    $res->getReasonPhrase(),
                    $res->getHeaders(),
                    $res->getProtocolVersion()
                );
                // TODO OGG: remove
                // return new FulfilledPromise($httpResponse);
            },
            function (RequestException $e) use ($request) {
                $httpException = new HttpException(
                    "Paydemic.Internal.HttpException: " . $e->getMessage(),
                    $e->getCode(),
                    $e,
                    $request->getMethod(),
                    $request->getUri()->getPath(),
                    []
                );
                return new RejectedPromise($httpException);
            }
        );
    }
}
