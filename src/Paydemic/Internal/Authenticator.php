<?php
/**
 * This file is part of the Paydemic.PaydemicPhpSdk
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Paydemic\Internal;

use \Paydemic\Internal\HttpClient\HttpClientInterface;

class Authenticator
{
    private $client;
    private $accessKeyId;
    private $secretAccessKey;

    public function __construct(
        $accessKeyId,
        $secretAccessKey,
        HttpClientInterface $httpClient
    ) {
        $this->accessKeyId = $accessKeyId;
        $this->secretAccessKey = $secretAccessKey;
        $this->client = $httpClient;
    }

    public function refreshTemporaryCredentials()
    {
        return $this->client->request(
            PaydemicRequests::TEMPORARY_CREDENTIALS_METHOD,
            PaydemicRequests::TEMPORARY_CREDENTIALS_PATH,
            [
                'json' => [
                    'accessKeyId' => $this->accessKeyId,
                    'secretAccessKey' => $this->secretAccessKey
                ]
            ]
        );
    }
}
