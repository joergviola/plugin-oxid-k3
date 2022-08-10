# install
```
vendor/bin/oe-console oe:module:install-configuration source/modules/fc/fcobjectcodek3
```

# apply configuration
```
vendor/bin/oe-console oe:module:apply-configuration
```

# migration
```
vendor/bin/oe-eshop-db_migrate migrations:migrate
```

# activate
```
vendor/bin/oe-console oe:module:activate fcobjectcodek3
```

# uninstall
```
vendor/bin/oe-console oe:module:uninstall-configuration fcobjectcodek3
```

# todo
- perspam prüfen ob text als Feldtyp ausreicht
- config und appcode an bestellartikel speichern
  - ggf im admin anzeigen
- anpssung emails auf persparam