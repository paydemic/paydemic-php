<?php
/**
 * This file is part of the Paydemic.PaydemicPhpSdk
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Paydemic\PaydemicPhpSdk;

use Paydemic\PaydemicPhpSdk\HttpClient\HttpClientBasedOnGuzzle;
use Paydemic\PaydemicPhpSdk\HttpClient\HttpException;
use Paydemic\PaydemicPhpSdk\HttpClient\HttpResponse;

class PaydemicPhpSdk
{
    private $log;
    private $authenticator;
    private $client;

    public function __construct(
        $host,
        $accessKeyId,
        $secretAccessKey,
        $timeout = 20.0
    ) {
        $this->log = Logger::getLogger('PaydemicPhpSdk.Main');
        $this->client = new HttpClientBasedOnGuzzle($host, $timeout);
        $this->authenticator =
            new Authenticator($accessKeyId, $secretAccessKey, $this->client);
    }

    public function bla()
    {
        $this->authenticator->refreshTemporaryCredentials()
            ->then(
                function (HttpResponse $res) {
                    $this->log->info(
                        'refreshTemporaryCredentials response code: ' .
                        $res->code . " " . $res->phrase . "\n"
                    );
                    $this->log->info('refreshTemporaryCredentials response body: ' . "\n");
                    $this->log->info($res->body . "\n");
                },
                function (HttpException $he) {
                    $this->log->warn("Houston, we've got a problem!");
                    $this->log->err($he);
                    $this->log->info("Http method of the request: " . $he->httpMethod);
                    $this->log->alert("Http method of the request: " . $he->httpMethod);
                    $this->log->emerg("Http method of the request: " . $he->httpMethod);
                }
            );
    }
}