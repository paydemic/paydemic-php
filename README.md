# paydemic-php
[Paydemic.com](https://paydemic.com) API for PHP


## Installation

`composer require paydemic/paydemic-php-sdk`


## API Overview

Every resource is accessed via your `paydemic` instance:

```php
$paydemic = new PaydemicPhpSdk('<accessKey>', '<secretAccessKey>')
$paydemic->{ RESOURCE_NAME }->{ METHOD_NAME }
```


Every resource method returns an instance of PromiseInterface (https://promisesaplus.com/),
so you don't have to use the regular callback. E.g.
<a name="create_purchaselink"></a>
```php
// Create a new purchase link:
$finalUrl = "https://haskell.org";
$title = "Haskell org";
$currencyCode = "USD";
$price = 4.0;

$paydemic->PurchaseLinks->create(
    $finalUrl,
    $title,
    $currencyCode,
    $price
)->then(
    // on success
    function ($purchaseLink) { /* some code */ },
    // on exception
    function ($exception) { /* some error handling code */ },
)
```

There is also a `wait()` method which just waits for the Promise to complete and returns the result:

```php
// Create a new purchase link:
$purchaseLink = $paydemic->PurchaseLinks->create(
    $finalUrl,
    $title,
    $currencyCode,
    $price
)->wait();
```

<a name="retrieve_purchaselink"></a>
```php
// Retrieve an existing purchase link:
$retrieved = $paydemc->PurchaseLinks->retrieve($purchaseLink['id'])->wait();
```

<a name="list_purchaselink"></a>
```php
// List all the existing purchase links under this project:
$listed = $paydemic->PurchaseLinks->listAll()->wait();
```

<a name="update_purchaselink"></a>
```php
// Update an existing purchase link:
$finalUrlUpdate = $finalUrl . '#updated';
$titleUpdate = $title . ' UPDATED';
$priceUpdate = 6.0;

$updated = $paydemic->PurchaseLinks->update(
    $purchaseLink['id'],
    $finalUrlUpdate,
    $titleUpdate,
    $currencyCode,
    $priceUpdate
)->wait();
```

<a name="remove_purchaselink"></a>
```php
// Delete an existing purchase link:
$paydemic->PurchaseLinks->delete($purchaseLink['id'])->wait();
```


### Available resources & methods

 * PurchaseLinks
    * [`create($finalUrl, $title, $currencyCode, $price)`](https://github.com/paydemic/paydemic-php#create_purchaselink)
    * [`retrieve($id)`](https://github.com/paydemic/paydemic-php#retrieve_purchaselink)
    * [`listAll()`](https://github.com/paydemic/paydemic-php#list_purchaselink)
    * [`update($id, $finalUrl, $title, $currencyCode, $price)`](https://github.com/paydemic/paydemic-php#update_purchaselink)
    * [`delete($id)`](https://github.com/paydemic/paydemic-php#remove_purchaselink)


## Development

### Run tests locally

```
composer install
composer test
```

### Run static analysis tools, run tests with coverage and generate API Doc

```
composer build
```

After it finishes, have a look in the `build` folder.


## Contribution
   * If you would like to contribute, please fork the repo and send in a pull request.
