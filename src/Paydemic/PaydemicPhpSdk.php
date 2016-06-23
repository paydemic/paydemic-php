<?php
/**
 * This file is part of the Paydemic.PaydemicPhpSdk
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Paydemic;

use Paydemic\Internal\Authenticator;
use Paydemic\Internal\Exception\HttpException;
use Paydemic\Internal\HttpClient\HttpClientBasedOnGuzzle;
use Paydemic\Internal\HttpClient\HttpResponse;
use Paydemic\Internal\Logger;

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
        $this->log = Logger::getLogger('PaydemicPhpSdk');
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
                    $this->log->err($he);
                    //  TODO OGG: remove these
                    //  $this->log->warn("Houston, we've got a problem!");
                    //  $this->log->info("Http method of the request: " . $he->httpMethod);
                    //  $this->log->alert("Http method of the request: " . $he->httpMethod);
                    //  $this->log->emerg("Http method of the request: " . $he->httpMethod);
                }
            );
    }
}
