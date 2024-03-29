<?php

use Illuminate\Support\Str;
use Strimoid\Models\Content;
use Strimoid\Models\Group;
use Strimoid\Models\User;
use Stringy\Stringy;
use Vinkla\Hashids\Facades\Hashids;

if (!function_exists('shadow')) {
    function shadow($text): string
    {
        $text = Str::ascii($text);
        return Str::lower($text);
    }
}

if (!function_exists('shadow_email')) {
    function shadow_email($email)
    {
        $shadow = Str::lower($email);
        $shadow = str_replace('.', '', $shadow);

        return preg_replace('/\+(.)*@/', '@', $shadow);
    }
}

if (!function_exists('parse_usernames')) {
    // Change "@username" and "g/groupname" into markdown links
    function parse_usernames($body)
    {
        $body = preg_replace_callback('/(?<=^|\s)c\/([a-z0-9_-]+)(?=$|\s|:|.)/i', function ($matches) {
            $contentId = Hashids::decode($matches[1]);

            if (!$contentId) {
                return 'c/' . $matches[1];
            }

            $content = Content::find($contentId[0]);

            if ($content) {
                return '[' . str_replace('_', '\_', (string) $content->title) . '](' . $content->getSlug() . ')';
            }

            return 'c/' . $matches[1];
        }, (string) $body);

        $body = preg_replace_callback('/(?<=^|\s)u\/([a-z0-9_-]+)(?=$|\s|:|.)/i', function ($matches) {
            $target = User::name($matches[1])->first();

            if ($target) {
                return '[u/' . str_replace('_', '\_', (string) $target->name) . '](/u/' . $target->name . ')';
            }

            return 'u/' . $matches[1];
        }, (string) $body);

        $body = preg_replace_callback('/(?<=^|\s)@([a-z0-9_-]+)(?=$|\s|:|.)/i', function ($matches) {
            $target = User::name($matches[1])->first();

            if ($target) {
                return '[@' . str_replace('_', '\_', (string) $target->name) . '](/u/' . $target->name . ')';
            }

            return '@' . $matches[1];
        }, (string) $body);

        $body = preg_replace_callback('/(?<=^|\s)(?<=\s|^)g\/([a-z0-9_-żźćńółęąśŻŹĆĄŚĘŁÓŃ]+)(?=$|\s|:|.)/i', function ($matches) {
            $target = Group::name($matches[1])->first();
            $fakeGroup = class_exists('Folders\\' . Str::studly($matches[1]));

            if ($target || $fakeGroup) {
                $urlname = $target->urlname ?? $matches[1];

                return '[g/' . str_replace('_', '\_', $urlname) . '](/g/' . $urlname . ')';
            }

            return 'g/' . $matches[1];
        }, (string) $body);

        return $body;
    }
}

if (!function_exists('toBool')) {
    // Convert to boolean
    function toBool($var): bool
    {
        $lower = strtolower((string) $var);

        if ($var === true || $lower === 'on' || $lower === 'true') {
            $result = true;
        }

        return $result ?? false;
    }
}

if (!function_exists('between')) {
    function between($value, int $min, int $max)
    {
        $value = (int) $value;

        return max(min($value, $max), $min);
    }
}

if (!function_exists('hashids_decode')) {
    function hashids_decode($raw)
    {
        $ids = Hashids::decode($raw);

        return current($ids);
    }
}

if (!function_exists('user')) {
    function user(): object
    {
        if (auth()->guest()) {
            return (object) [];
        }

        return auth()->user();
    }
}

if (!function_exists('setting')) {
    function setting($key = null)
    {
        if (!$key) {
            return app('settings');
        }

        return app('settings')->get($key);
    }
}

if (!function_exists('s')) {
    function s($str, string $encoding = null): Stringy
    {
        return new Stringy($str, $encoding);
    }
}

if (!function_exists('strans')) {
    function strans($id = null, $replace = [], $locale = null, string $encoding = null): Stringy
    {
        $str = app('translator')->get($id, $replace, $locale);
        return new Stringy($str, $encoding);
    }
}
