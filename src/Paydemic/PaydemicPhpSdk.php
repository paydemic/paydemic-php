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

    private $accessKeyId;
    private $secretAccessKey;

    private $purchaseLinks;

    public function __construct(
        $region,
        $host,
        $accessKeyId,
        $secretAccessKey,
        $timeout = 20.0
    ) {
        $this->log = Logger::getLogger('PaydemicPhpSdk');

        $this->client = new HttpClientBasedOnGuzzle(
            $accessKeyId,
            $secretAccessKey,
            $region,
            $host,
            $timeout
        );
        $this->authenticator =
            new Authenticator($accessKeyId, $secretAccessKey, $this->client);
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            switch ($property) {
                case "purchaseLinks":
                    if (!isset($this->purchaseLinks)) {
                        $this->purchaseLinks = new PurchaseLinks(
                            $this->accessKeyId,
                            $this->secretAccessKey,
                            $this->client
                        );
                    }
                    break;
//                TODO OGG: add the other
                default:
                    // nothing to do
            }
            return $this->$property;
        }
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
