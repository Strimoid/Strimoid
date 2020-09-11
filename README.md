Strimoid
========

[![Join the chat at https://gitter.im/Strimoid/Strimoid](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/Strimoid/Strimoid) [![Build Status](https://semaphoreci.com/api/v1/strimoid/strimoid/branches/develop/shields_badge.svg)](https://semaphoreci.com/strimoid/strimoid) [![Test Coverage](https://codeclimate.com/github/Strimoid/Strimoid/badges/coverage.svg)](https://codeclimate.com/github/Strimoid/Strimoid) [![Code Climate](https://codeclimate.com/github/Strimoid/Strimoid/badges/gpa.svg)](https://codeclimate.com/github/Strimoid/Strimoid) [![Docker Repository on Quay](https://quay.io/repository/strimoid/strimoid/status "Docker Repository on Quay")](https://quay.io/repository/strimoid/strimoid)

Source code of [Strm.pl](https://strm.pl), brand-new social service.

API documentation
========
You can find API documentation at [https://developers.strm.pl](https://developers.strm.pl)

How to start
========
You'll need to get [Docker](https://www.docker.com/products/overview) and docker-compose, if you don't have them already installed.

```bash
docker-compose up -d --build
docker-compose exec php artisan migrate:fresh --seed
```

Then go to [http://localhost:8000](http://localhost:8000) and enjoy.
You can use `admin/admin` or `user/user` credentials to sign in.

To do
========
* [ ] Tests, tests, tests!
* [ ] Better frontend, with support for i18n
* [ ] Improve API
* [ ] Many many other things...

Any questions?
========
Just join any of following channels and please feel free to ask, we will try to answer as soon as possible.

* Telegram: [@strimoid](tg://resolve?domain=strimoid)
* IRC: [#strimoid @ Freenode](irc://chat.freenode.net/#strimoid)

