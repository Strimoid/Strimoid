<?php

namespace Strimoid\Providers;

use Auth;
use DB;
use Hash;
use Illuminate\Support\ServiceProvider;
use Str;
use Validator;

class ValidatorServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Validator::extend('unique_email', function ($attribute, $value, $parameters) {
            if (isset($parameters[1])) {
                $attribute = $parameters[1];
            }

            $value = str_replace('.', '', $value);
            $value = preg_replace('/\+(.)*@/', '@', $value);

            $count = DB::table($parameters[0])
                ->where($attribute, $value)
                ->count();

            return $count == 0;
        });

        Validator::extend('safe_url', fn ($attribute, $value, $parameters) => starts_with($value, 'http'));

        Validator::extend('url_custom', fn ($attribute, $value, $parameters) => preg_match('@^https?://[^\s/$.?#].[^\s]*$@iS', $value));

        Validator::extend('real_email', function ($attribute, $value, $parameters) {
            $blockedDomains = [

                // normal mail mail providers (used to spam)

                'mail.ru', 'inbox.ru', 'list.ru', 'bk.ru', 'yandex.ru', '126.com', '163.com', 'sina.com',

                // dae providers

                'drdrb.com', 'drdrb.net', // 10minutesmail.com

                'mailinator.com', 'mailinator2.com', 'monumentmail.com', 'reallymymail.com', 'spamgoes.in', 'mailtothis.com',
                'zippymail.info', 'tradermail.info', 'devnullmail.com', 'mailismagic.com',
                'letthemeatspam.com', 'bobmail.info', 'veryrealemail.com', // mailinator.com

                'guerrillamail.com', 'guerrillamailblock.com', 'sharklasers.com', 'guerrillamail.net', 'guerrillamail.org',
                'guerrillamail.biz', 'spam4.me', 'grr.la', 'guerrillamail.de', // guerrillamail.com

                'postalmail.biz', 'rainmail.biz', 'mailblog.biz', // temp-mail.org

                'tagyourself.com', 'spikio.com', 'co.uk', '6paq.com', 'fammix.com', 'whatpaas.com', 'advantimo.com',
                'beddly.com', '7tags.com', 'getairmail.com', 'whatiaas.com', 'acentri.com', 'vomoto.com', 'appixie.com',
                'evopo.com', 'consumerriot.com', 'vidchart.com', 'broadbandninja.com', '99experts.com', 'daintly.com',
                'droplar.com', 'paplease.com', 'grandmasmail.com', // getairmail.com

                'yopmail.com', 'yopmail.fr', 'yopmail.net', 'fr.nf', 'fr.nf', 'ze.tc', 'xl.cx',
                'zik.dj', '1s.fr', 'fr.nf', // yopmail.com

                'uroid.com', // tempail.com

                'mintemail.com', // mintemail.com
                'mailcatch.com', // mailcatch.com
                'tempsky.com', // tempsky.com
                'tempemail.net', // TempEMail.net
                'fakeinbox.com', // fakeinbox.com
                'meltmail.com', // meltmail.com
                'tempmailer.com', // tempmailer.com
                'mt2014.com', // mytrashmail.com
                'incognitomail.org', // incognitomail.com

                'armyspy.com', 'cuvox.de', 'dayrep.com', 'einrot.com', 'fleckens.hu', 'gustr.com', 'ourrapide.com', 'rhyta.com',
                'superrito.com', 'teleworm.us', // fakemailgenerator.com

                'coldemail.info', 'burstmail.info', 'solvemail.info', 'mailtemp.info', 'hopemail.biz', 'toomail.biz',
                'mailrock.biz', 'mailpick.biz', 'mailcat.biz', // http://api.temp-mail.ru/request/domains/format/xml/

                'mailforspam.com', 'mfsa.ru', // mailforspam.com

                'yourtempmail.com', // yourtempmail.com
                'dispostable.com', // dispostable.com
                'spamgourmet.com', // spamgourmet.com
                'maildrop.cc', // maildrop.cc
                'lazyinbox.com', // lazyinbox.com
                'mailnesia.com', // mailnesia.com
                'eyepaste.com', // eyepaste.com
                'mmmmail.com', // mmmmail.com
                'inbox.si', // inbox.si
                'mailstache.com', // mailstache.com
                'no-spam.ws', // no-spam.ws
                'fakebox.org', // fakebox.org
                'trashmail.ws', // trashmail.ws
                'emailisvalid.com', // emailisvalid.com
                'koszmail.pl', // koszmail.pl
                'migmail.pl', // migmail.pl
                'tymczasowy.com', // tymczasowy.com
                'niepodam.pl', // niepodam.pl

                'trashbox.pl', 'meltmail.net', // trashbox.pl

                // misc
                'uyhip.com', 'coieo.com', 'disposable.name', 'spamobox.com', 'q314.net', 'forward.cat', 'dispomail.eu',
                'freemail.ms', 'hideme.be', 'anonymbox.com', 'poczter.eu', 'ssoia.com', 'my10minutemail.com',
                '10minutmail.pl', 'co.za', 'tryalert.com', 'tmpeml.info',
                'mytrashmail.com', 'cbair.com', 'doiea.com',
            ];

            $domain = explode('@', $value, 2);
            $parts = explode('.', Str::lower($domain[1]));

            try {
                return !in_array($parts[count($parts) - 2] . '.' . $parts[count($parts) - 1], $blockedDomains);
            } catch (\Throwable $throwable) {
                return false;
            }
        });

        Validator::extend('strong_password', function ($attribute, $value, $parameters) {
            $easyPasswords = [
                '111111', '121212', '123456', 'qwerty', 'polska', 'zaq12wsx', '111111', 'aaaaaa', 'matrix', 'monika', 'marcin',
                'misiek', 'master', 'abc123', 'qwerty1', 'qazwsx', 'mateusz', 'strims', 'strimoid', 'qwe123', 'zzzzzz',
            ];

            return !in_array($value, $easyPasswords);
        });

        Validator::extend('reserved_groupnames', function ($attribute, $value, $parameters) {
            $names = [
                'subscribed', 'moderated', 'blocked', 'random', 'all', 'observed', 'saved',
                'subskrybowane', 'moderowane', 'zablokowane', 'blokowane', 'losowa', 'losowe',
                'wszystko', 'wszystkie', 'obserwowani', 'obserwowane', 'zapisane', 'folder',
                'upvoted', 'downvoted', 'uv', 'dv', 'mod', 'sub', 'los', 'blok', 'blo', 'blocked',
                'zbanowane', 'zbanowany', 'zbanowano', 'notvoted', 'nieocenione', 'domain', 'domena',
                'popular', 'popularne',
            ];

            return !in_array($value, $names);
        });

        Validator::extend('user_password', fn ($attribute, $value, $parameters) => Hash::check($value, Auth::user()->password));
    }

    public function register(): void
    {
    }
}
