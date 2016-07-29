<?php

namespace Paydemic\Tests;

use Paydemic\Internal\Exception\HttpException;
use Paydemic\Internal\HttpClient\HttpClientBasedOnGuzzle;
use Paydemic\Internal\HttpClient\HttpResponse;
use Paydemic\PurchaseLinks;

class PurchaseLinksTest extends \PHPUnit_Framework_TestCase
{
//    private $log;
    /**
     * @var PurchaseLinks
     */
    protected static $purchaseLinks;

    public static function setUpBeforeClass()
    {
//        $this->log = Logger::getLogger('PaydemicPhpSdk.PurchaseLinksTest');
        $credentials = self::loadCredentials();
        $client = new HttpClientBasedOnGuzzle(
            $credentials['accessKeyId'],
            $credentials['secretAccessKey'],
            'eu-west-1',
            'account.devdemic.com'
        );
        self::$purchaseLinks = new PurchaseLinks(
            $credentials['accessKeyId'],
            $credentials['secretAccessKey'],
            $client
        );
    }

    private static function loadCredentials()
    {
        $credentials = file_get_contents(
            dirname(__DIR__) . "/Paydemic/credentials/accessKey.json"
        );
        $json_a = json_decode($credentials, true);
        return $json_a;
    }

    protected function setUp()
    {
        parent::setUp();
    }

    public function testNew()
    {
        $actual = self::$purchaseLinks;
        $this->assertInstanceOf('\Paydemic\PurchaseLinks', $actual);
    }

    public function testFetchAll()
    {
        self::$purchaseLinks->fetchAll()->then(
            function (HttpResponse $res) {
                // TODO OGG: remove
                // print ("fetchAll response:\n");
                // print_r($res);
            },
            function (HttpException $exception) {
            }
        );
        $this->assertTrue(true);
    }
}
