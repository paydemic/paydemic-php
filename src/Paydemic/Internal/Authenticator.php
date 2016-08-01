<?php
/**
 * This file is part of the Paydemic.PaydemicPhpSdk
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Paydemic\Internal;

use Aws\Credentials\Credentials;
use DateTime;
use DateTimeZone;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\PromiseInterface;
use \Paydemic\Internal\HttpClient\HttpClientInterface;

class Authenticator
{
    private $client;
    private $accessKeyId;
    private $secretAccessKey;
    /**
     * @var Credentials
     */
    private $credentials;
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

    /**
     * Refreshes temporary credentials or loads them from cache if still enough
     * time (10 mintes) till expiration.
     * @return PromiseInterface promise fulfilled with credentials
     */
    public function refreshTemporaryCredentials()
    {
        $currentDateTime = new DateTime('now', new DateTimeZone('UTC'));
        if ($this->credentials &&
            $this->credentials->getExpiration() - $currentDateTime->getTimestamp() > 600) {
            $this->log->info('Reusing temporary credentials (cached ones expire in more than 10 minutes) ...');
            return new FulfilledPromise($this->credentials);
        }

        $this->log->info('Refreshing temporary credentials (cached ones expire in less than 10 minutes) ...');
        $payload = json_encode(
            [
                'accessKeyId' => $this->accessKeyId,
                'secretAccessKey' => $this->secretAccessKey
            ]
        );
        return $this->client
            ->unsignedRequest(
                'POST',
                '/authentication/temporarycredentials',
                $payload
            )
            ->then(function ($credentialsResponse) use ($currentDateTime) {
                $credentialsJson = json_decode($credentialsResponse->body, true);
                $expirationDateTime = new DateTime(
                    $credentialsJson['expiration'],
                    new DateTimeZone('UTC')
                );
                $this->credentials = new Credentials(
                    $credentialsJson['accessKeyId'],
                    $credentialsJson['secretAccessKey'],
                    $credentialsJson['sessionToken'],
                    $expirationDateTime->getTimestamp()
                );
                return $this->credentials;
            });
    }
}
