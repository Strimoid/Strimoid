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
            $pubKey = $app['config']['pubnub.pub_key'];
            $subKey = $app['config']['pubnub.sub_key'];

            $pubNub = new \Pubnub\Pubnub($pubKey, $subKey);

            return new PubNub($pubNub);
        });
    }

}
