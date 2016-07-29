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
    private $log;

    public function __construct(
        $accessKeyId,
        $secretAccessKey,
        HttpClientInterface $httpClient
    ) {
        $this->accessKeyId = $accessKeyId;
        $this->secretAccessKey = $secretAccessKey;
        $this->client = $httpClient;
        $this->log = Logger::getLogger('PaydemicPhpSdk.Authenticator');
    }

    public function refreshTemporaryCredentials()
    {
        // TODO OGG: take the expiration timestamp as param and check if token is expired
        // otherwise return a fulfilled promise with the previous token value
        $this->log->info('Refreshing temporary credentials ...');
        $payload = json_encode(
            [
                'accessKeyId' => $this->accessKeyId,
                'secretAccessKey' => $this->secretAccessKey
            ]
        );
        return $this->client->unsignedRequest(
            PaydemicRequests::TEMPORARY_CREDENTIALS_METHOD,
            PaydemicRequests::TEMPORARY_CREDENTIALS_PATH,
            $payload
        );
    }
}
