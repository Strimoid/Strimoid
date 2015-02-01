<?php namespace Strimoid\Facades; 

use Illuminate\Support\Facades\Facade;

class PS extends Facade {

    protected static function getFacadeAccessor() { return 'Strimoid\Contracts\PubSub'; }

}
