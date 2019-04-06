Invite Manager
===============

This is project for event's invite managment.


Installation
------------

For installing project you need clone repository from GITHUB^

```
git clone https://github.com/lekha-its-me/invite_project.git
```

Then you run:

```
$ composer install
```

> NOTE: You need change in .env file access to database and mail transport.

Next step - run:

```
$ php bin/console doctrine:migrations:migrate
```

Usage
-----

Main changes that you need to do:

1. You can upload guests list from .csv file. File must have 3 fields: Firstname, Lastname, E-mail

2. You can select from imported list guests for whom yhou want sent invite. You should specify subject and body of invitation letter.

3. Guest revice letter with uniqe qr-code.

4. When you check your guests tickets you should scan qr-code, if it right you get answer? that all correct.
If guest won't finds - you get answer that qr-code is incorrect.



License
-------

[![license](https://img.shields.io/github/license/greeflas/default-project.svg)](LICENSE)

This project is released under the terms of the BSD-3-Clause [license](LICENSE).

Copyright (c) 2019, Alexey Baranov
