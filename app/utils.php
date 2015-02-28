<?php

use Strimoid\Models\Group;
use Strimoid\Models\User;

if (! function_exists('shadow')) {
    function shadow($text)
    {
        $text = Str::ascii($text);
        $text = Str::lower($text);

        return $text;
    }
}

if (! function_exists('hash_email')) {
    function hash_email($email)
    {
        return hash('sha384', Config::get('app.email_salt').$email.md5($email));
    }
}

if (! function_exists('shadow_email')) {
    function shadow_email($email)
    {
        $shadow = Str::lower($email);
        $shadow = str_replace('.', '', $shadow);

        return preg_replace('/\+(.)*@/', '@', $shadow);
    }
}

if (! function_exists('parse_usernames')) {

    // Change "@username" and "g/groupname" into markdown links
    function parse_usernames($body)
    {
        $body = preg_replace_callback('/(?<=^|\s)c\/([a-z0-9_-]+)(?=$|\s|:|.)/i', function ($matches) {
            $content = Content::find($matches[1]);

            if ($content) {
                return '['.str_replace('_', '\_', $content->title).']('.$content->getSlug().')';
            } else {
                return 'c/'.$matches[1];
            }
        }, $body);

        $body = preg_replace_callback('/(?<=^|\s)u\/([a-z0-9_-]+)(?=$|\s|:|.)/i', function ($matches) {
            $target = User::where('shadow_name', Str::lower($matches[1]))->first();

            if ($target) {
                return '[u/'.str_replace('_', '\_', $target->name).'](/u/'.$target->name.')';
            } else {
                return 'u/'.$matches[1];
            }
        }, $body);

        $body = preg_replace_callback('/(?<=^|\s)@([a-z0-9_-]+)(?=$|\s|:|.)/i', function ($matches) {
            $target = User::where('shadow_name', Str::lower($matches[1]))->first();

            if ($target) {
                return '[@'.str_replace('_', '\_', $target->name).'](/u/'.$target->name.')';
            } else {
                return '@'.$matches[1];
            }
        }, $body);

        $body = preg_replace_callback('/(?<=^|\s)(?<=\s|^)g\/([a-z0-9_-żźćńółęąśŻŹĆĄŚĘŁÓŃ]+)(?=$|\s|:|.)/i', function ($matches) {
            $groupName = shadow($matches[1]);

            $target = Group::where('shadow_urlname', $groupName)->first();
            $fakeGroup = class_exists('Folders\\'.studly_case($groupName));

            if ($target || $fakeGroup) {
                $urlname = $target ? $target->urlname : $groupName;

                return '[g/'.str_replace('_', '\_', $urlname).'](/g/'.$urlname.')';
            } else {
                return 'g/'.$matches[1];
            }
        }, $body);

        return $body;
    }
}

if (! function_exists('mid_to_b58')) {
    // Convert MongoID to Base58
    function mid_to_b58($mongoId)
    {
        return gmp_strval(gmp_init($mongoId, 16), 58);
    }
}

if (! function_exists('b58_to_mid')) {
    function b58_to_mid($base58)
    {
        return gmp_strval(gmp_init($base58, 58), 16);
    }
}

if (! function_exists('toBool')) {
    // Convert to boolean
    function toBool($var)
    {
        $lower = strtolower($var);

        if ($var === true || $lower == 'on' || $lower == 'true') {
            $result = true;
        }

        return (isset($result) && $result) ?: false;
    }
}

if (! function_exists('between')) {
    function between($value, $min, $max)
    {
        $value = (int) $value;

        return max(min($value, $max), $min);
    }
}
