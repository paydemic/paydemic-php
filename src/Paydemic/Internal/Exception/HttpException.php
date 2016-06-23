<?php
/**
 * This file is part of the Paydemic.PaydemicPhpSdk
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Paydemic\Internal\Exception;

class HttpException extends RuntimeException implements ExceptionInterface
{
    public $httpMethod;
    public $requestPath;
    public $requestOptions;

    public function __construct(
        $message,
        $code,
        $previous,
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
