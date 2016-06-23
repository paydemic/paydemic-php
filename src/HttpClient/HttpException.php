<?php
/**
 * This file is part of the Paydemic.PaydemicPhpSdk
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Paydemic\PaydemicPhpSdk\HttpClient;

use \Paydemic\PaydemicPhpSdk\Exception\ExceptionInterface;
use \Paydemic\PaydemicPhpSdk\Exception\RuntimeException;

class HttpException extends RuntimeException implements ExceptionInterface
{
    public $httpMethod;
    public $requestPath;
    public $requestOptions;

    public function __construct(
        $message,
        $code,
        \Exception $previous,
        $httpMethod,
        $requestPath,
        $requestOptions
    ) {
        parent::__construct($message, $code, $previous);

        $this->httpMethod = $httpMethod;
        $this->requestPath = $requestPath;
        $this->requestOptions = $requestOptions;
    }
}
