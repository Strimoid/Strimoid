<?php

use Jenssegers\Mongodb\Model as Eloquent;

class BaseModel extends Eloquent
{

    public function getLocalTime()
    {
        $timezone = new DateTimeZone(Settings::get('timezone'));

        return $this->created_at->setTimeZone($timezone)->format('d/m/Y H:i:s');
    }

    public function getVoteState()
    {
        if (Auth::guest() || !$this->votes)
        {
            return 'none';
        }

        $pos = array_search(Auth::user()->_id, array_column($this->votes, 'user_id'));

        if ($pos === false)
        {
            return 'none';
        }

        $vote = $this->votes[$pos];

        if ($vote['up'])
        {
            return 'uv';
        }
        else
        {
            return 'dv';
        }
    }

    public function getVoteStateAttribute() {
        return $this->getVoteState();
    }

    public function mpush($column, $value = null, $unique = false)
    {
        if (!$this->_id)
            return new Exception('Tried to push on model without id');

        $builder = $this->newQuery()->where('_id', $this->_id);
        $builder->push($column, $value, $unique);
    }

    public function mpull($column, $value = null)
    {
        if (!$this->_id)
            return new Exception('Tried to pull on model without id');

        $builder = $this->newQuery()->where('_id', $this->_id);
        $builder->pull($column, $value);
    }

    public static function validate($input)
    {
        return Validator::make($input, static::$rules);
    }

}