<?php namespace Strimoid\Http\Controllers;

class SearchController extends BaseController {

    protected $builder;

    public function search()
    {
        if (Input::has('q'))
        {
            $keywords = preg_replace('/((\w+):(\w+\pL.))+\s?/i', '', Input::get('q'));

            switch (Input::get('t')) {
                case 'e':
                    $builder = Entry::where('text', 'like', '%'. $keywords .'%');
                    break;
                case 'g':
                    $builder = Group::where('name', 'like', '%'. $keywords .'%')
                        ->orWhere('urlname', 'like', '%'. $keywords .'%')
                        ->orWhere('tags', $keywords);
                    break;
                case 'c':
                default:
                    $builder = Content::where(function($query) use($keywords)
                        {
                            $query->where('title', 'like', '%'. $keywords .'%')
                                ->orWhere('description', 'like', '%'. $keywords .'%');
                        });
                    break;
            }

            $this->builder = $builder;
            $this->setupFilters(Input::get('q'));

            $results = $this->builder->paginate(25);
        }

        Return View::make('search.main', compact('results'));
    }

    protected function getArguments($text)
    {
        $arguments = array();

        preg_match_all('/(\w+):([\w\pL.]+)/i', $text, $matches, PREG_SET_ORDER);

        foreach ($matches as $match)
        {
            $key = $match[1];
            $arguments[$key] = $match[2];
        }

        return $arguments;
    }

    protected function setupFilters($text)
    {
        $arguments = $this->getArguments($text);

        foreach ($arguments as $key => $value)
        {
            switch ($key)
            {
                case 'g':
                    $this->filterGroup($value);
                    break;
                case 'u':
                    $this->filterUser($value);
                    break;
                case 't':
                    $this->filterTime($value);
                    break;
                case 'd':
                    $this->filterDomain($value);
                    break;
                case 'nsfw':
                    $this->filterNSFW($value);
                    break;
            }
        }
    }

    protected function filterGroup($value)
    {
        $group = Group::shadow($value)->first();

        if ($group)
        {
            $this->builder->where('group_id', $group->_id);
        }
    }

    protected function filterUser($value)
    {
        $user = User::shadow($value)->first();

        if ($user)
        {
            $this->builder->where('user_id', $user->_id);
        }
    }

    protected function filterTime($value)
    {
        try
        {
            $value = 'PT'. Str::upper($value);
            $time = Carbon::now()->sub(new DateInterval($value));

            $this->builder->where('created_at', '>', carbon_to_md($time));
        }
        catch (Exception $e) {}
    }

    protected function filterNSFW($value)
    {
        if ($value == 'yes')
        {
            $this->builder->where('nsfw', true);
        }
        else if ($value == 'no')
        {
            $this->builder->where('nsfw', '!=', true);
        }
    }

    protected function filterDomain($value)
    {
        $this->builder->where('domain', $value);
    }

}