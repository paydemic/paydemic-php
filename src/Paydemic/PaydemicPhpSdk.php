<?php
/**
 * This file is part of the Paydemic.PaydemicPhpSdk
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Paydemic;

use Paydemic\Internal\HttpClient\HttpClientBasedOnGuzzle;
use Paydemic\Internal\Logger;

class PaydemicPhpSdk
{
    /**
     * @var PurchaseLinks
     */
    public $PurchaseLinks;

    public function __construct(
        $accessKeyId,
        $secretAccessKey,
        $timeout = 20.0
    ) {
        $this->log = Logger::getLogger('PaydemicPhpSdk');

        $this->PurchaseLinks = new PurchaseLinks(
            $accessKeyId,
            $secretAccessKey,
            new HttpClientBasedOnGuzzle(
                'eu-west-1',
                'account.paydemic.com',
                $timeout
            )
        );
    }
}
