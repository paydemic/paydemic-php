<?php
/**
 * This file is part of the Paydemic.PaydemicPhpSdk
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Paydemic;

use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\RejectedPromise;
use Paydemic\Internal\Authenticator;
use Paydemic\Internal\Exception\HttpException;
use Paydemic\Internal\HttpClient\HttpClientBasedOnGuzzle;
use Paydemic\Internal\HttpClient\HttpClientInterface;
use Paydemic\Internal\HttpClient\HttpResponse;
use Paydemic\Internal\Logger;
use Paydemic\Internal\PaydemicRequests;

class PurchaseLinks
{
    private $log;
    private $authenticator;
    private $client;

    /**
     * PurchaseLinks constructor.
     * @param string              $accessKeyId     access key
     * @param string              $secretAccessKey secret access key
     * @param HttpClientInterface $client          an instance of HttpClientInterface implementation
     * @return mixed Promise fulfilled with HttpResponse or rejected with HttpException
     */
    public function __construct(
        $accessKeyId,
        $secretAccessKey,
        $client
    ) {
        $this->log = Logger::getLogger('PaydemicPhpSdk.PurchaseLinks');
        $this->client = $client;
        $this->authenticator =
            new Authenticator($accessKeyId, $secretAccessKey, $this->client);
    }

    public function fetchAll()
    {
        return $this->authenticator->refreshTemporaryCredentials()
            ->then(
                function (HttpResponse $res) {
                    $resJson = json_decode($res->body, true);
                    $this->log->info('Fetching all Purchase Links with token: ' . "\n'" . $resJson['sessionToken'] . "' ...");
                    return $this->client->signedRequest(
                        PaydemicRequests::PURCHASE_LINKS_GET_METHOD,
                        $resJson['sessionToken'],
                        strtotime($resJson["expiration"]),
                        PaydemicRequests::PURCHASE_LINKS_GET_PATH
                    );
                },
                function (HttpException $he) {
                    $this->log->err($he);
                }
            )
            ->then(
                function (HttpResponse $res) {
                     $resJson = json_decode($res->body, true);
                    // TODO OGG: remove
                    $this->log->info('fetchAll() response code: ' . $res->code . " " . $res->phrase . "\n");
                    // $this->log->info('refreshTemporaryCredentials response body: ' . "\n");
                    // $this->log->info($res->body . "\n");
                    //
                    // $this->log->warn("\nSession Token: \n" .$parsedResponse["sessionToken"]);
                    // $this->log->warn("\nSession Token expiration: \n" .$parsedResponse["expiration"]);
                    // $this->log->warn("\nSession Token expiration as UNIX timestamp: \n" . strtotime($parsedResponse["expiration"]));
                    return $resJson;
                },
                function (HttpException $he) {
                    $this->log->err($he);
                }
            );
    }
}
