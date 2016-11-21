pop-ftp
=======

OVERVIEW
--------
`pop-ftp` is a simple and convenient FTP adapter for processing FTP requests via PHP.

`pop-ftp` is a component of the [Pop PHP Framework](http://www.popphp.org/).

INSTALL
-------

Install `pop-ftp` using Composer.

    composer require popphp/pop-ftp

BASIC USAGE
-----------

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

