# Paydemic SDK for PHP

## Run tests locally

```
composer install
composer test
```

## Run static analysis tools, run tests with coverage and generate API Doc

```
composer build
```

After it finishes, have a look in the `build` folder.

## Add it as a dependency to your PHP project
 
### From Packagist

Run the following in your project root folder:

```
composer require paydemic/paydemic-php-sdk
```

### From GitHub

Edit your _composer.json_ file directly and add the following:

```
...
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/paydemic/paydemic-php"
    }
],
"require": {
    "paydemic/paydemic-php": "dev-master"
}
...
```


## Usage examples

Have a look intoo `tests/Paydemic/PaydemicPhpSdkTest.php`
