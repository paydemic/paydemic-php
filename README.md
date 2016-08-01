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

## Add it as a dependency to your PHP project's _composer.json_
 
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
