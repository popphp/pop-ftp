pop-ftp
=======

[![Join the chat at https://discord.gg/TZjgT74U7E](https://media.popphp.org/img/discord.svg)](https://discord.gg/TZjgT74U7E)

* [Overview](#overview)
* [Install](#install)
* [Quickstart](#quickstart)

Overview
--------
`pop-ftp` is a simple and convenient FTP adapter for processing FTP requests via PHP.

It is a component of the [Pop PHP Framework](https://www.popphp.org/).

[Top](#pop-ftp)

Install
-------

Install `pop-ftp` using Composer.

    composer require popphp/pop-ftp

Or, require it in your composer.json file

    "require": {
        "popphp/pop-ftp" : "^4.0.1"
    }

[Top](#pop-ftp)

Quickstart
----------

### Create a new directory, change into it and upload a file

```php
use Pop\Ftp\Ftp;

$ftp = new Ftp('ftp.myserver.com', 'username', 'password');

$ftp->mkdir('somedir');
$ftp->chdir('somedir');

$ftp->put('file_on_server.txt', 'my_local_file.txt');
```

### Download file from a directory

```php
use Pop\Ftp\Ftp;

$ftp = new Ftp('ftp.myserver.com', 'username', 'password');

$ftp->chdir('somedir');

$ftp->get('my_local_file.txt', 'file_on_server.txt');
```

[Top](#pop-ftp)

