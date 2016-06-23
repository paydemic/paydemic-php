<?php
/**
 * This file is part of the Paydemic.PaydemicPhpSdk
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Paydemic\PaydemicPhpSdk\HttpClient;

class HttpResponse
{
    public $body;
    public $code;
    public $phrase;
    public $headers;
    public $protocolVersion;

    public function __construct(
        $body,
        $code,
        $phrase,
        $headers,
        $protocolVersion
    ) {
        $this->body = $body;
        $this->code = $code;
        $this->phrase = $phrase;
        $this->headers = $headers;
        $this->protocolVersion = $protocolVersion;
    }
}
