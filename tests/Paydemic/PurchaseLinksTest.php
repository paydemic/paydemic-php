<?php

namespace Paydemic\Tests;

// use Paydemic\Internal\Exception\HttpException;
use Paydemic\Internal\HttpClient\HttpClientBasedOnGuzzle;
// use Paydemic\Internal\HttpClient\HttpResponse;
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
        $credentials = self::loadCredentials();
        $client = new HttpClientBasedOnGuzzle(
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

    public function testCreate()
    {
        $res = self::$purchaseLinks->create(
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
        $res = self::$purchaseLinks->listAll()->wait();
        $nbPls = count($res);
        print("\nFound $nbPls Purchase Links.\n");
        $this->assertTrue(
            $nbPls > 0,
            "At least one Purchase Link should be found!"
        );

        $firstPurchaseLink = self::$purchaseLinks->read($res[0]['id'])->wait();
        print("\nFirst read purchase link:\n");
        print_r($firstPurchaseLink);
        print("\n");

        $this->assertEquals($res[0]['id'], $firstPurchaseLink['id']);
        // TODO OGG: this can be enhanced - test that all other PL attrs are set
    }
}
