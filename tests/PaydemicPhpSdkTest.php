<?php

namespace Paydemic\PaydemicPhpSdk;

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
            'account.devdemic.com',
            $credentials['accessKeyId'],
            $credentials['secretAccessKey']
        );
    }

    private static function loadCredentials()
    {
        $credentials = file_get_contents(
            dirname(__DIR__) . "/tests/credentials/accessKey.json"
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
        $this->assertInstanceOf('\Paydemic\PaydemicPhpSdk\PaydemicPhpSdk', $actual);
    }

    public function testBla()
    {
        self::$sdk->bla();
        $this->assertTrue(true);
    }
}
