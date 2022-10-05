## Plugin K3 ObjectCode

An oxid module to integrate K3 configurator from ObjectCode

## Installation via composer

- In the composer.json file add a new repository

  - manual
  ```
   "repositories": [
      {
        "type": "vcs",
        "url": "https://github.com/OC-Git/plugin-oxid-k3"
      }
    ]
  ```
  -  command line
  ```
  composer config repositories.objectcode/plugin-oxid-k3 vcs https://github.com/OC-Git/plugin-oxid-k3
  ```
  
- execute the following command in the shop base folder (where the composer.json file is located)
```
composer require objectcode/plugin-oxid-k3 --update-no-dev
```
- execute database migrations
```
vendor/bin/oe-eshop-db_migrate migrations:migrate
```
- activate the module after the composer install is finished
```
vendor/bin/oe-console oe:module:activate ock3
```

## Manual Installation
- Copy the content to the directory source/modules/oc/k3 of the shop installation
  - the structure should be than source/modules/oc/k3/Application/, source/modules/oc/k3/Core ....
- In the composer.json file in the shop base folder add the autoload configuration or extend if already existing:

```
"autoload": {
   "psr-4": {
      "ObjectCode\\K3\\": "../../../source/modules/oc/k3"
   }
}

```

- Connect to the webserver with a console, navigate to the shop base folder and execute the following command:
```
vendor/bin/composer dump-autoload
```

- install oxid module configuration
```
vendor/bin/oe-console oe:module:install-configuration source/modules/oc/k3
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
vendor/bin/oe-console oe:module:activate ock3
```

## Configuration

### Endpoints
On installation the required SEO endpoints get created for
oxshopid = 1 and oxlang = 0.

eg. index.php?cl=oc_ock3_connectorcontroller&shp=1&lang=0 -> k3/connect/

Endpoints:
k3/connect/ -> used to connect via K3 backend
k3/cart/ -> internally used by k3 to add configurations to basket
k3/articles/ -> internally used by k3 to export articles

Please check that your configured language shop matches with the language and shop
defined for the default seo urls. If not create new matching seo urls via the OXID admin SEO tab.

eg.
index.php?cl=oc_ock3_connectorcontroller&shp=1&lang=1 -> en/k3/connect/

### Module Configuration
Through the module settings you can activate(default)/deactivate the functionality without
disabling the module. You can also control the k3 environment through the test mode option.

### Module Handling
K3 configurations gets added via oxid persparams and basket item prices are
set to the amount given by k3.

Logentries gets written in log/ock3.log.

On installation the attribute "K3" with oxid (k3product) get created to control the export articles.
Just assign the attribute to the articles you want to export, the value does not matter.
If no attribute is assigned, articles get exported.

Only active and buyable articles get exported.

## Author
ObjectCode GmbH  | www.objectcode.de | info@objectcode.de

## License
see LICENSE file