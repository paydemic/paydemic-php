<?php

namespace Paydemic\Tests;

use Paydemic\PaydemicPhpSdk;

class PaydemicPhpSdkTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PaydemicPhpSdk
     */
    protected static $sdk;

    public static function setUpBeforeClass()
    {
        $credentials = self::loadCredentials();

        self::$sdk = new PaydemicPhpSdk(
            $credentials['accessKeyId'],
            $credentials['secretAccessKey']
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
        $actual = self::$sdk;
        $this->assertInstanceOf('\Paydemic\PaydemicPhpSdk', $actual);
    }

    public function testGetPurchaseLinks()
    {
        $this->assertInstanceOf(
            '\Paydemic\PurchaseLinks',
            self::$sdk->PurchaseLinks
        );
    }

    public function testCreate()
    {
        $res = self::$sdk->PurchaseLinks->create(
            "https://haskell.org",
            "Haskell org",
            "USD",
            4.0
        )->wait();
        print("\nCreate Purchase Link result:\n");
        print_r($res);
        print("\n");
        $this->assertNotEmpty($res['id']);
        $this->assertEquals('https://haskell.org', $res['finalUrl']);
        $this->assertEquals('Haskell org', $res['title']);
        $this->assertEquals('USD', $res['price']['currencyCode']);
        $this->assertEquals(4, round($res['price']['amount']));
    }

    public function testListAllAndReadOne()
    {
        $res = self::$sdk->PurchaseLinks->listAll()->wait();
        $nbPls = count($res);
        print("\nFound $nbPls Purchase Links.\n");
        $this->assertTrue(
            $nbPls > 0,
            "At least one Purchase Link should be found!"
        );

        $firstPurchaseLink = self::$sdk->PurchaseLinks->retrieve($res[0]['id'])->wait();
        print("\nFirst read purchase link:\n");
        print_r($firstPurchaseLink);
        print("\n");

        $this->assertEquals($res[0]['id'], $firstPurchaseLink['id']);
    }
}
