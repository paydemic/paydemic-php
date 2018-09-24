<?php

namespace Paydemic\Tests;

use PHPUnit\Framework\TestCase;
use Paydemic\PaydemicPhpSdk;

class PaydemicPhpSdkTest extends TestCase
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

    public function testCRUDL()
    {
        $finalUrl = "https://paywalledsite.com/paid-access-article";
        $title = "My paid access article title";
        $currencyCode = "USD";
        $price = 4.0;

        // test create
        $created = self::$sdk->PurchaseLinks->create(
            $finalUrl,
            $title,
            $currencyCode,
            $price
        )->wait();
        print("\nCreated Purchase Link:\n");
        print_r($created);
        print("\n");
        $id = $created['id'];
        $this->assertNotEmpty($id);
        $this->assertEquals($finalUrl, $created['finalUrl']);
        $this->assertEquals($title, $created['title']);
        $this->assertEquals($currencyCode, $created['price']['currencyCode']);
        $this->assertEquals($price, round($created['price']['amount']));

        // test retrieve
        $retrieved = self::$sdk->PurchaseLinks->retrieve($id)->wait();
        print("\nRetrieved Purchase Link:\n");
        print_r($retrieved);
        print("\n");
        $this->assertEquals($id, $retrieved['id']);
        $this->assertEquals($finalUrl, $retrieved['finalUrl']);
        $this->assertEquals($title, $retrieved['title']);
        $this->assertEquals($currencyCode, $retrieved['price']['currencyCode']);
        $this->assertEquals($price, round($retrieved['price']['amount']));

        // test update
        $finalUrlUpdate = $finalUrl . '#updated';
        $titleUpdate = $title . ' UPDATED';
        $priceUpdate = 6.0;
        $updated = self::$sdk->PurchaseLinks->update(
            $id,
            $finalUrlUpdate,
            $titleUpdate,
            $currencyCode,
            $priceUpdate
        )->wait();
        print("\nUpdated Purchase Link ${created['id']}:\n");
        print_r($updated);
        $this->assertEquals($id, $updated['id']);
        $this->assertEquals($finalUrlUpdate, $updated['finalUrl']);
        $this->assertEquals($titleUpdate, $updated['title']);
        $this->assertEquals($currencyCode, $updated['price']['currencyCode']);
        $this->assertEquals($priceUpdate, round($updated['price']['amount']));

        // test list all
        $listed = self::$sdk->PurchaseLinks->listAll()->wait();
        $nbListed = count($listed);
        print("\nListed $nbListed Purchase Links.\n");
        $this->assertTrue(
            $nbListed > 0,
            "At least one Purchase Link should be found by listAll()!"
        );

        // test delete
        $deleted = self::$sdk->PurchaseLinks->delete($id)->wait();
        print("\nDeleted Purchase Link ${created['id']}:\n");
        print_r($deleted);
        $this->assertEquals($id, $deleted['id']);
        $this->assertEquals('SUCCESS', $deleted['status']);
    }

    public function testCUDwithDescription()
    {
        $finalUrl = "https://paywalledsite.com/paid-access-article";
        $title = "My paid access article title";
        $currencyCode = "EUR";
        $price = 2.0;
        $description = "Extra information";

        // test create
        $created = self::$sdk->PurchaseLinks->create(
            $finalUrl,
            $title,
            $currencyCode,
            $price,
            $description
        )->wait();
        print("\nCreated Purchase Link:\n");
        print_r($created);
        print("\n");
        $id = $created['id'];
        $this->assertNotEmpty($id);
        $this->assertEquals($finalUrl, $created['finalUrl']);
        $this->assertEquals($title, $created['title']);
        $this->assertEquals($currencyCode, $created['price']['currencyCode']);
        $this->assertEquals($price, round($created['price']['amount']));
        $this->assertEquals($description, $created['description']);


        // test update
        $finalUrlUpdate = $finalUrl . '#updated';
        $titleUpdate = $title . ' UPDATED';
        $priceUpdate = 6.0;
        $descriptionUpdate = $description . ' UPDATED';
        $updated = self::$sdk->PurchaseLinks->update(
            $id,
            $finalUrlUpdate,
            $titleUpdate,
            $currencyCode,
            $priceUpdate,
            $descriptionUpdate
        )->wait();
        print("\nUpdated Purchase Link ${created['id']}:\n");
        print_r($updated);
        $this->assertEquals($id, $updated['id']);
        $this->assertEquals($finalUrlUpdate, $updated['finalUrl']);
        $this->assertEquals($titleUpdate, $updated['title']);
        $this->assertEquals($currencyCode, $updated['price']['currencyCode']);
        $this->assertEquals($priceUpdate, round($updated['price']['amount']));
        $this->assertEquals($descriptionUpdate, $updated['description']);

        // test delete
        $deleted = self::$sdk->PurchaseLinks->delete($id)->wait();
        print("\nDeleted Purchase Link ${created['id']}:\n");
        print_r($deleted);
        $this->assertEquals($id, $deleted['id']);
        $this->assertEquals('SUCCESS', $deleted['status']);
    }
}
