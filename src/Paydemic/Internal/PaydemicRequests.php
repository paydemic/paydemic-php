<?php
/**
 * This file is part of the Paydemic.PaydemicPhpSdk
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Paydemic\Internal;

/**
 * Interface PaydemicRequests
 *  - holds definitions for available Paydemic requests.
 * @package Paydemic\PaydemicPhpSdk
 */
interface PaydemicRequests
{
    const TEMPORARY_CREDENTIALS_METHOD = 'POST';
    const TEMPORARY_CREDENTIALS_PATH = '/authentication/temporarycredentials';

    const PURCHASE_LINKS_CREATE_METHOD = "POST";
    const PURCHASE_LINKS_CREATE_PATH = "/api/purchaselinks";

    const PURCHASE_LINKS_LIST_METHOD = "GET";
    const PURCHASE_LINKS_LIST_PATH = "/api/purchaselinks";
}
