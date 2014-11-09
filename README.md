Strimoid
========

[![Build Status](https://travis-ci.org/Strimoid/Strimoid.svg?branch=master)](https://travis-ci.org/Strimoid/Strimoid) [![Coverage Status](https://img.shields.io/coveralls/Strimoid/Strimoid.svg)](https://coveralls.io/r/Strimoid/Strimoid) [![Code Climate](https://codeclimate.com/github/Strimoid/Strimoid/badges/gpa.svg)](https://codeclimate.com/github/Strimoid/Strimoid)

Source code of Strimoid.pl, brand-new social service.

Requirements
========
* PHP 5.5+ with APCu, MongoDB and ZMQ extensions.
* If you have too much free time, you can try with HHVM + Mongofill and hhvm-zmq instead of PHP.
* MongoDB.

API
========
You can find API documentation at https://developers.strimoid.pl

How to start?
========
Create file with desired environment name:
```
echo "<?php return 'production';" > bootstrap/environment.php
```

Install dependencies using Composer:

```
composer.phar install
```

Compile resources using Gulp:

```
npm install
gulp
```

To do
========
* [ ] AngularJS based frontend, developed as external project.
* [ ] Improve API: change routing, add more documentation, add ETags support.
* [ ] Many, many, other things...

Questions?
========
Just join #strimoid @ Freenode and feel free to ask.
