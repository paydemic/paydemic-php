<?php
/**
 * This file is part of the Paydemic.PaydemicPhpSdk
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Paydemic\PaydemicPhpSdk;

use Monolog\Logger as Monologger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use \Bramus\Monolog\Formatter\ColoredLineFormatter;

class Logger
{
    public static function getLogger($name)
    {
        $stdoutHandler = new StreamHandler('php://stdout', Monologger::INFO);
        $fileHandler = new RotatingFileHandler(
            dirname(__DIR__) . '/logs/paydemic-php-sdk.log',
            5,
            Monologger::INFO
        );

        // the last "true" here tells it to remove empty []'s
        $coloredFormatter =
            new ColoredLineFormatter(null, null, null, true, true);
        $coloredFormatter->includeStacktraces(true);
        $stdoutHandler->setFormatter($coloredFormatter);
        $fileHandler->setFormatter($coloredFormatter);

        $logger = new Monologger($name);
        $logger->pushHandler($stdoutHandler);
        $logger->pushHandler($fileHandler);
        return $logger;
    }
}
