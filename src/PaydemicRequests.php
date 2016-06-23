<?php
/**
 * This file is part of the Paydemic.PaydemicPhpSdk
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Paydemic\PaydemicPhpSdk;

/**
 * Interface PaydemicRequests
 *  - holds definitions for available Paydemic requests.
 * @package Paydemic\PaydemicPhpSdk
 */
interface PaydemicRequests
{
    const TEMPORARY_CREDENTIALS_METHOD = 'POST';
    const TEMPORARY_CREDENTIALS_PATH = '/authentication/temporarycredentials';
}
