<?php namespace Strimoid\Providers; 

use Illuminate\Support\ServiceProvider;
use Strimoid\Services\PubNub;

class PubSubServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Strimoid\Contracts\PubSub', function($app)
        {
            $pubKey = $app['config']['services.pub_key'];
            $subKey = $app['config']['services.sub_key'];
            $secret = $app['config']['services.secret'];

            $pubNub = new \Pubnub\Pubnub($pubKey, $subKey, $secret);

            return new PubNub($pubNub);
        });
    }

}
