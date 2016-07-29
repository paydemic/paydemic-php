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
     * @return array Promise fulfilled with the created Purchase Link:
     * <pre>
     * [
     *      'id' => '0d64fc71-6950-4b48-9876-f380ae760b69'
     *      'finalUrl' => 'https://haskell.org'
     *      'title' => 'Haskell dot org'
     *      'price' =>
     *      [
     *          'currencyCode" => 'USD'
     *          'amount' => 4.00
     *      ]
     *      'purchaseUrl' => 'https://account.paydemic.com/buy/NLFDOPZ6U5EU5MRQISNVVGV6KI'
     *      'creationDate' => '2016-07-29T21:15:42.113Z'
     * ]
     * </pre>
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
                        'POST',
                        $resJson["accessKeyId"],
                        $resJson["secretAccessKey"],
                        $resJson['sessionToken'],
                        $resJson["expiration"],
                        '/api/purchaselinks',
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
     * Retrieves a Purchase Link.
     * @param string $id id of the Purchase Link to read
     * @return array Promise fulfilled with the retrieved Purchase Link:
     * <pre>
     * [
     *      'id' => '0d64fc71-6950-4b48-9876-f380ae760b69'
     *      'finalUrl' => 'https://haskell.org'
     *      'title' => 'Haskell dot org'
     *      'price' =>
     *      [
     *          'currencyCode" => 'USD'
     *          'amount' => 4.00
     *      ]
     *      'purchaseUrl' => 'https://account.paydemic.com/buy/NLFDOPZ6U5EU5MRQISNVVGV6KI'
     *      'creationDate' => '2016-07-29T21:15:42.113Z'
     * ]
     * </pre>
     */
    public function retrieve($id)
    {
        return $this->authenticator->refreshTemporaryCredentials()
            ->then(
                function (/*HttpResponse */$res) use ($id) {
                    $resJson = json_decode($res->body, true);

                    $this->log->info("Reading Purchase Link $id ...");

                    return $this->client->signedRequest(
                        'GET',
                        $resJson["accessKeyId"],
                        $resJson["secretAccessKey"],
                        $resJson['sessionToken'],
                        $resJson["expiration"],
                        "/api/purchaselinks/$id",
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
     * Updates a Purchase Link.
     * @param string $id       id of the Purchase Link
     * @param string $url      url to be sold
     * @param string $title    purchase link title
     * @param string $currency currency ISO code (RON, USD, EUR ...)
     * @param double $price    price of the purchase link
     * @return array Promise fulfilled with the updated Purchase Link:
     * <pre>
     * [
     *      'id' => '0d64fc71-6950-4b48-9876-f380ae760b69'
     *      'finalUrl' => 'https://haskell.org'
     *      'title' => 'Haskell dot org'
     *      'price' =>
     *      [
     *          'currencyCode" => 'USD'
     *          'amount' => 4.00
     *      ]
     *      'purchaseUrl' => 'https://account.paydemic.com/buy/NLFDOPZ6U5EU5MRQISNVVGV6KI'
     *      'creationDate' => '2016-07-29T21:15:42.113Z'
     * ]
     * </pre>
     */
    public function update($id, $url, $title, $currency, $price)
    {
        return $this->authenticator->refreshTemporaryCredentials()
            ->then(
                function (/*HttpResponse */$res) use (
                    $id,
                    $url,
                    $title,
                    $currency,
                    $price
                ) {
                    $resJson = json_decode($res->body, true);

                    $this->log->info("Updating Purchase Link $id ...");

                    return $this->client->signedRequest(
                        'PUT',
                        $resJson["accessKeyId"],
                        $resJson["secretAccessKey"],
                        $resJson['sessionToken'],
                        $resJson["expiration"],
                        "/api/purchaselinks/$id",
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
     * Deletes a Purchase Link.
     * @param string $id id of the Purchase Link
     * @return array Promise fulfilled with Purchase Link deletion status:
     * <pre>
     * [
     *      'id' => '0d64fc71-6950-4b48-9876-f380ae760b69'
     *      'status' => 'SUCCESS'
     * ]
     * </pre>
     */
    public function delete($id)
    {
        return $this->authenticator->refreshTemporaryCredentials()
            ->then(
                function (/*HttpResponse */$res) use ($id) {
                    $resJson = json_decode($res->body, true);

                    $this->log->info("Deleting Purchase Link $id ...");

                    return $this->client->signedRequest(
                        'DELETE',
                        $resJson["accessKeyId"],
                        $resJson["secretAccessKey"],
                        $resJson['sessionToken'],
                        $resJson["expiration"],
                        "/api/purchaselinks/$id",
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
     * @return array Promise fulfilled with the list of Purchase Links
     */
    public function listAll()
    {
        return $this->authenticator->refreshTemporaryCredentials()
            ->then(
                function (/*HttpResponse */$res) {
                    $resJson = json_decode($res->body, true);

                    $this->log->info('Listing Purchase Links ...');

                    return $this->client->signedRequest(
                        'GET',
                        $resJson["accessKeyId"],
                        $resJson["secretAccessKey"],
                        $resJson['sessionToken'],
                        $resJson["expiration"],
                        '/api/purchaselinks',
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
