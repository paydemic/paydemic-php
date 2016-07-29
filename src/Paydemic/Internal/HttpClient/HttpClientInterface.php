<?php
/**
 * This file is part of the Paydemic.PaydemicPhpSdk
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Paydemic\Internal\HttpClient;

interface HttpClientInterface
{
    /**
     * Sends an unsigned request and returns a Promise/A+ (https://promisesaplus.com/)
     * @param string $method http method (GET, POST, PUT, DELETE ...)
     * @param string $path   path relative to base uri
     * @param string $body   request body
     * @return mixed a "PromiseInterface" (according to the Promise/A+ standard)
     */
    public function unsignedRequest(
        $method,
        $path = null,
        $body = null
    );

    /**
     * Sends a V4 signed request and returns a Promise/A+ (https://promisesaplus.com/)
     * @param string $method       http method (GET, POST, PUT, DELETE ...)
     * @param string $token        AWS session token
     * @param int    $tokenExpires AWS session token expiration moment as UNIX timestamp
     * @param string $path         path relative to base uri
     * @param string $body         request body
     * @return mixed a "PromiseInterface" (according to the Promise/A+ standard)
     */
    public function signedRequest(
        $method,
        $token,
        $tokenExpires,
        $path = null,
        $body = null
    );
}
