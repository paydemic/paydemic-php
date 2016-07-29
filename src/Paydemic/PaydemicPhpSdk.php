<?php
/**
 * This file is part of the Paydemic.PaydemicPhpSdk
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Paydemic;

use Paydemic\Internal\Authenticator;
use Paydemic\Internal\HttpClient\HttpClientBasedOnGuzzle;
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
}
