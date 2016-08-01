<?php
/**
 * This file is part of the Paydemic.PaydemicPhpSdk
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Paydemic\Internal\HttpClient;

use Aws\Credentials\Credentials;

interface HttpClientInterface
{
    /**
     * Sends an unsigned request and returns a Promise/A+ (https://promisesaplus.com/)
     * @param string $method http method (GET, POST, PUT, DELETE ...)
     * @param string $path   path relative to base uri; pass empty string '' for no path
     * @param string $body   request body
     * @return mixed a "PromiseInterface" (according to the Promise/A+ standard)
     */
    public function unsignedRequest(
        $method,
        $path,
        $body = null
    );

    /**
     * Sends a V4 signed request and returns a Promise/A+ (https://promisesaplus.com/)
     * @param string      $method      http method (GET, POST, PUT, DELETE ...)
     * @param Credentials $credentials AWS credentials
     * @param string      $path        path relative to base uri; pass '' (empty string) for no path
     * @param string      $body        request body
     * @return mixed a "PromiseInterface" (according to the Promise/A+ standard)
     */
    public function signedRequest(
        $method,
        $credentials,
        $path,
        $body = null
    );
}
