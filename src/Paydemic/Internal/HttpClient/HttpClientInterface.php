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
     * Sends a request and returns a Promise/A+ (https://promisesaplus.com/)
     * @param string $method  http method (GET, POST, PUT, DELETE ...)
     * @param string $path    path relative to base uri
     * @param array  $options optional - can contain 'headers', 'json' body ...
     *      Example:
     *      [
     *          'headers' =>
     *          [
     *              'headerName1' => 'headerValue1',
     *              'headerName2' => 'headerValue2',
     *          ]
     *          'json' =>
     *          [
     *              'someKey1' => 'someValue1',
     *              'someKey2' => 'someValue2'
     *          ]
     *      ]
     * @return mixed a "PromiseInterface" (according to the Promise/A+ standard)
     */
    public function request($method, $path = null, array $options = []);
}
