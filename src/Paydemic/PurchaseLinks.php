<?php
/**
 * This file is part of the Paydemic.PaydemicPhpSdk
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Paydemic;

use Paydemic\Internal\Authenticator;
// use Paydemic\Internal\Exception\HttpException;
use Paydemic\Internal\HttpClient\HttpClientInterface;
// use Paydemic\Internal\HttpClient\HttpResponse;
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

    /**
     * Lists all Purchase Links.
     * @return mixed Promise fulfilled with response json
     */
    public function listAll()
    {
        return $this->authenticator->refreshTemporaryCredentials()
            ->then(
                function (/*HttpResponse */$res) {
                    $resJson = json_decode($res->body, true);

                    $this->log->info('Listing Purchase Links ...');

                    return $this->client->signedRequest(
                        PaydemicRequests::PURCHASE_LINKS_GET_METHOD,
                        $resJson["accessKeyId"],
                        $resJson["secretAccessKey"],
                        $resJson['sessionToken'],
                        $resJson["expiration"],
                        PaydemicRequests::PURCHASE_LINKS_GET_PATH,
                        null
                    );
                },
                function (/*HttpException */$he) {
                    $this->log->err($he);
                }
            )
            ->then(
                function (/*HttpResponse */$res) {
                    return json_decode($res->body, true);
                }
            );
    }
}
