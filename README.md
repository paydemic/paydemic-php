# Paydemic SDK for PHP

## Run tests locally

```
composer install
composer test
```

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

**NOTE**: Check the _tests_ folder for examples
