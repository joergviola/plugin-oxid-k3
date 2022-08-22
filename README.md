## Plugin K3 ObjectCode

An oxid module to integrate K3 configurator from ObjectCode

## Installation via composer

- In the composer.json file add a new repository

  - manual
  ```
   "repositories": [
      {
        "type": "vcs",
        "url": "https://github.com/FATCHIP-GmbH/plugin-oxid-k3"
      }
    ]
  ```
  -  command line
  ```
  composer config repositories.fatchip-gmbh/plugin-oxid-k3 vcs https://github.com/FATCHIP-GmbH/plugin-oxid-k3
  ```

- Generate a github token with access to the FATCHIP-GmbH/plugin-oxid-k3 repository
- execute the following command in the base folder (where the composer.json file is located)
```
composer require fatchip-gmbh/plugin-oxid-k3 --update-no-dev
```
- enter token to authenticate
- execute database migrations
```
vendor/bin/oe-eshop-db_migrate migrations:migrate
```
- activate the module after the composer install is finished
```
vendor/bin/oe-console oe:module:activate fcobjectcodek3
```

## Manual Installation
- Copy the content into source/modules of the shop installation
- In the composer.json file in the base folder of the shop add the autoload configuration or extend if already existing:

```
"autoload": {
   "psr-4": {
        "FATCHIP\\ObjectCodeK3\\": "./source/modules/fc/fcobjectcodek3",
   }
}

```

- Connect to the webserver with a console, navigate to the shop base folder and execute the following command:
```
vendor/bin/composer dump-autoload
```

- install oxid module configuration
```
vendor/bin/oe-console oe:module:install-configuration source/modules/fc/fcobjectcodek3
```

- apply oxid module configuration
```
vendor/bin/oe-console oe:module:apply-configuration
```

- execute database migrations
```
vendor/bin/oe-eshop-db_migrate migrations:migrate
```

- activate oxid module
```
vendor/bin/oe-console oe:module:activate fcobjectcodek3
```

## Configuration

### Endpoints
On installation the required SEO endpoints get created for
oxshopid = 1 and oxlang = 1.

Endpoints:
k3/connect/ -> used to connect via K3 backend
k3/cart/ -> internally used by k3 to add configurations to basket
k3/articles/ -> internally used by k3 to export articles

### Module Configuration
Through the module settings you can activate(default)/deactivate the functionality without
disabling the module. You can also control the k3 environment through the test mode option.

### Module Handling
K3 configurations gets added via oxid persparams and basket item prices are
set to the amount given by k3.

Logentries gets written in log/fcobjectcodek3.log.

## Author
FATCHIP GmbH | https://www.fatchip.de | support@fatchip.de

## License
see LICENSE file