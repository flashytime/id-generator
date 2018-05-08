<?php
/**
 * Created by IntelliJ IDEA.
 * Author: flashytime
 * Date: 2015/8/27 21:20
 */

namespace Flashytime\IdGenerator\Provider;

use Flashytime\IdGenerator\IdGenerator;
use Illuminate\Support\ServiceProvider;

class BaseServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    public function register()
    {
        $this->app->singleton('id-generator', function ($app) {
            $config = $app->config->get('id-generator');

            return new IdGenerator($config);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['id-generator'];
    }
}
