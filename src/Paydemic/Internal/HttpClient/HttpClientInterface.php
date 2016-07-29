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
     * @param string $method       http method (GET, POST, PUT, DELETE ...)
     * @param string $key          access key
     * @param string $secret       secret key
     * @param string $token        AWS session token
     * @param int    $tokenExpires AWS session token expiration moment as UNIX timestamp
     * @param string $path         path relative to base uri; pass '' (empty string) for no path
     * @param string $body         request body
     * @return mixed a "PromiseInterface" (according to the Promise/A+ standard)
     */
    public function signedRequest(
        $method,
        $key,
        $secret,
        $token,
        $tokenExpires,
        $path,
        $body = null
    );
}
