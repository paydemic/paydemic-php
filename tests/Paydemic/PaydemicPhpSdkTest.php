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
            'eu-west-1',
            'account.devdemic.com',
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
            self::$sdk->purchaseLinks
        );
    }
}
