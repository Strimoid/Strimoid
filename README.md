Strimoid
========

[![Join the chat at https://gitter.im/Strimoid/Strimoid](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/Strimoid/Strimoid?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge) [![Build Status](https://travis-ci.org/Strimoid/Strimoid.svg?branch=master)](https://travis-ci.org/Strimoid/Strimoid) [![Test Coverage](https://codeclimate.com/github/Strimoid/Strimoid/badges/coverage.svg)](https://codeclimate.com/github/Strimoid/Strimoid) [![Code Climate](https://codeclimate.com/github/Strimoid/Strimoid/badges/gpa.svg)](https://codeclimate.com/github/Strimoid/Strimoid)

Source code of Strm.pl, brand-new social service.

Requirements
========
* PHP 7 with intl, mbstring, openssl, pdo, pdo-mysql extensions
* MySQL (MariaDB 10.1 recommended, but might even work with PostgreSQL)

API
========
You can find API documentation at https://developers.strm.pl

Documentation
========
We are providing documentation generated automatically by Sami at https://sami.strm.pl

How to use it?
========
* run:

```
composer create-project strimoid/strimoid --stability=dev
```

* edit .env file and then run:

```
php artisan migrate
```

To run web app from console use:

```
php artisan serve
```

How to use it with Docker?
========
```
docker-compose up -d
docker-compose run web migrate --force
```

To do
========
* [ ] Tests, tests, tests!
* [ ] Better frontend.
* [ ] Improve API: change routing, add more documentation, add ETags support.
* [ ] Many many other things...

Questions?
========
Just join #strimoid @ Freenode and feel free to ask.
