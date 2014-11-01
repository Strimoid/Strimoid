<?php

use Illuminate\Support\Facades\Facade;

class OAuth extends Facade {

    protected static function getFacadeAccessor() { return 'oauth'; }

}
