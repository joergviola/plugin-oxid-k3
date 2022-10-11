## Plugin K3 ObjectCode

An OXID module to integrate the K3 product configurator from ObjectCode.


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

- Copy the content to the directory `source/modules/oc/k3` of the shop installation
  - the structure should be `source/modules/oc/k3/Application/`, `source/modules/oc/k3/Core/` ...
- In the composer.json file in the shop base folder add the autoload configuration or extend if already existing:
	```
	"autoload": {
	   "psr-4": {
		  "ObjectCode\\K3\\": "./source/modules/oc/k3"
	   }
	}
	```
- Connect to the webserver with a console, navigate to the shop base folder and execute the following command:
	```
	composer dump-autoload
	```
- install OXID module configuration
	```
	vendor/bin/oe-console oe:module:install-configuration source/modules/oc/k3
	```
- apply OXID module configuration
	```
	vendor/bin/oe-console oe:module:apply-configuration
	```
- execute database migrations
	```
	vendor/bin/oe-eshop-db_migrate migrations:migrate
	```

- activate OXID module
	```
	vendor/bin/oe-console oe:module:activate ock3
	```


## Configuration


### Module Configuration
Through the module settings you can activate(default)/deactivate the functionality without
disabling the module. You can also control the K3 environment through the test mode option.


### Module Handling
K3 configurations gets added via OXID persparams and basket item prices are
set to the amount given by K3.

Logentries gets written in `log/ock3.log`.

On installation the attribute `K3` with OXID `k3product` get created to control the export articles.
Just assign the attribute to the articles you want to export, the value does not matter.
If no attribute is assigned, all articles get exported.

Only active and buyable articles get exported.


## Infos


### Endpoints used by ObjectCode K3
- connector: `index.php?cl=oc_ock3_connectorcontroller`
- cart: `index.php?cl=oc_ock3_basketcontroller`
- export: `index.php?cl=oc_ock3_productexportcontroller`


## Author
ObjectCode GmbH  | www.objectcode.de | info@objectcode.de


## License
see LICENSE file
