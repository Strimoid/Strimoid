<?php

namespace Strimoid\Http\Controllers;

use DateInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Strimoid\Models\Content;
use Strimoid\Models\Entry;
use Strimoid\Models\Group;
use Strimoid\Models\User;

class SearchController extends BaseController
{
    protected $builder;
    public function __construct(private readonly \Illuminate\Contracts\View\Factory $viewFactory)
    {
    }

    public function search(Request $request)
    {
        $results = null;

        if ($request->has('q')) {
            $query = $request->get('q');

            $keywords = preg_replace(
                '/((\w+):(\w+\pL.))+\s?/i',
                '',
                (string) $query
            );

            $builder = match ($request->get('t')) {
                'e' => Entry::where('text', 'ilike', '%' . $keywords . '%'),
                'g' => Group::where('name', 'ilike', '%' . $keywords . '%')
                    ->orWhere('urlname', 'like', '%' . $keywords . '%'),
                default => Content::where(
                    function ($query) use ($keywords): void {
                        $query->where('title', 'ilike', '%' . $keywords . '%')
                            ->orWhere('description', 'ilike', '%' . $keywords . '%');
                    }
                ),
            };

            $this->builder = $builder;
            $this->setupFilters($query);

            $results = $this->builder->paginate(25);
        }

        return $this->viewFactory->make('search.main', compact('results'));
    }

    protected function getArguments(string $text): array
    {
        $arguments = [];

        preg_match_all('/(\w+):([\w\pL.]+)/i', $text, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $key = $match[1];
            $arguments[$key] = $match[2];
        }

        return $arguments;
    }

    protected function setupFilters(string $text): void
    {
        $arguments = $this->getArguments($text);

        foreach ($arguments as $key => $value) {
            match ($key) {
                'g' => $this->filterGroup($value),
                'u' => $this->filterUser($value),
                't' => $this->filterTime($value),
                'd' => $this->filterDomain($value),
                'nsfw' => $this->filterNSFW($value),
            };
        }
    }

    protected function filterGroup($value): void
    {
        $group = Group::name($value)->first();

        if ($group) {
            $this->builder->where('group_id', $group->getKey());
        }
    }

    protected function filterUser($value): void
    {
        $user = User::name($value)->first();

        if ($user) {
            $this->builder->where('user_id', $user->getKey());
        }
    }

    protected function filterTime($value): void
    {
        try {
            $value = 'PT' . Str::upper($value);
            $time = Carbon::now()->sub(new DateInterval($value));

            $this->builder->where('created_at', '>', $time);
        } catch (\Exception) {
        }
    }

    protected function filterNSFW($value): void
    {
        if ($value === 'yes') {
            $this->builder->where('nsfw', true);
        } elseif ($value === 'no') {
            $this->builder->where('nsfw', '!=', true);
        }
    }

    protected function filterDomain($value): void
    {
        $this->builder->where('domain', $value);
    }
}
