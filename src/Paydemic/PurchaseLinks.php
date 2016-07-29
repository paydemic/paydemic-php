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
     * Creates a Purchase Link.
     * @param string $url      url to be sold
     * @param string $title    purchase link title
     * @param string $currency currency ISO code (RON, USD, EUR ...)
     * @param double $price    price of the purchase link
     * @return mixed Promise fulfilled with response json
     */
    public function create($url, $title, $currency, $price)
    {
        return $this->authenticator->refreshTemporaryCredentials()
            ->then(
                function (/*HttpResponse */$res) use (
                    $url,
                    $title,
                    $currency,
                    $price
                ) {
                    $resJson = json_decode($res->body, true);

                    $this->log->info("Creating Purchase Link ...");

                    return $this->client->signedRequest(
                        PaydemicRequests::PURCHASE_LINKS_CREATE_METHOD,
                        $resJson["accessKeyId"],
                        $resJson["secretAccessKey"],
                        $resJson['sessionToken'],
                        $resJson["expiration"],
                        PaydemicRequests::PURCHASE_LINKS_CREATE_PATH,
                        json_encode(
                            [
                                'finalUrl' => $url,
                                'title' => $title,
                                'price' =>
                                [
                                    'currencyCode' => $currency,
                                    'amount' => $price
                                ]
                            ]
                        )
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

    /**
     * Reads a Purchase Link.
     * @param string $id id of the Purchase Link to read
     * @return mixed Promise fulfilled with response json
     */
    public function read($id)
    {
        return $this->authenticator->refreshTemporaryCredentials()
            ->then(
                function (/*HttpResponse */$res) use ($id) {
                    $resJson = json_decode($res->body, true);

                    $this->log->info("Reading Purchase Link $id ...");

                    return $this->client->signedRequest(
                        PaydemicRequests::PURCHASE_LINKS_LIST_METHOD,
                        $resJson["accessKeyId"],
                        $resJson["secretAccessKey"],
                        $resJson['sessionToken'],
                        $resJson["expiration"],
                        PaydemicRequests::PURCHASE_LINKS_LIST_PATH . "/$id",
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
                        PaydemicRequests::PURCHASE_LINKS_LIST_METHOD,
                        $resJson["accessKeyId"],
                        $resJson["secretAccessKey"],
                        $resJson['sessionToken'],
                        $resJson["expiration"],
                        PaydemicRequests::PURCHASE_LINKS_LIST_PATH,
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
