EBDB migration tools
============================

This application is used by ebizu developers who use **ebdb/ebdb-staging** as their database application. The purpose is to integrate the migration functionality from all apps that use **ebdb/ebdb-staging**.


[![Latest Stable Version](https://poser.pugx.org/yiisoft/yii2-app-basic/v/stable.png)](https://packagist.org/packages/yiisoft/yii2-app-basic)

REQUIREMENTS
------------

1. PHP 5.6.0 or above
2. Composer

INSTALLATION
------------
### Install Package via Composer
Install package via composer using following command:
```
composer install
```

### Init Environment for developemt
```
sh install.sh dev
```


CONFIGURATION
-------------
### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=your-local-db',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8',
];
```
USING THE APPLICATION
---------------------

[Visit the Yii-2 Migration Documentation](http://www.yiiframework.com/doc-2.0/guide-db-migrations.html)
