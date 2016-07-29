<?php
/**
 * This file is part of the Paydemic.PaydemicPhpSdk
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Paydemic\Internal\HttpClient;

use Aws\Credentials\Credentials;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\RejectedPromise;
use GuzzleHttp\Psr7\Request;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use Paydemic\Internal\ApiGatewaySignatureV4;
use Paydemic\Internal\Exception\HttpException;
use Paydemic\Internal\Logger;

class HttpClientBasedOnGuzzle implements HttpClientInterface
{
    private $baseuri;
    private $client;
    private $log;
    private $signatureV4;
    private $host;

    public function __construct(
        $region,
        $host,
        $timeout = 20.0
    ) {
        $this->log =
            Logger::getLogger('PaydemicPhpSdk.HttpClientBasedOnGuzzle');
        $this->baseuri = "https://$host";

        $this->host = $host;

        $handler = new CurlHandler();
        $stack = HandlerStack::create($handler); // Wrap w/ middleware

        $this->client = new Client([
            'handler' => $stack,
            'base_uri' => $this->baseuri,
            'timeout'  => $timeout,
            'headers' => [
                'Host' => $this->host,
                'Accept' => 'application/json',
                'User-Agent' => 'paydemic-php/v1.0.0'
            ]
        ]);

        $this->signatureV4 = new ApiGatewaySignatureV4("execute-api", $region);
    }

    public function signedRequest(
        $method,
        $key,
        $secret,
        $token,
        $tokenExpires,
        $path,
        $body = null
    ) {
        $signedRequest = $this->signatureV4->signRequest(
            new Request(
                $method,
                $this->baseuri . $path,
                [],
                $body
            ),
            new Credentials($key, $secret, $token, $tokenExpires)
        );
        return $this->sendAsync($signedRequest);
    }

    public function unsignedRequest(
        $method,
        $path,
        $body = null
    ) {
        return $this->sendAsync(
            new Request(
                $method,
                $this->baseuri . $path,
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
