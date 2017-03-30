# Config

forked from [hassankhan/config](https://github.com/hassankhan/config)
the one and only difference which I made is :
access config values by file name 


```php
<?php
use Noodlehaus\Config;

// Load all supported files in a directory
$conf = new Config(__DIR__ . '/config');

```


consider in mentioned path we have multiple config file and one of them is mail.php
and it's content is as follow 

```php
<?php
return [ 
       'driver'=>'file'
];


```


now you can access this variable like follow :

```php
// Get value using key
$debug = $conf->get('mail.driver');

```