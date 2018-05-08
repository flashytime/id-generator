<?php
/**
 * Created by IntelliJ IDEA.
 * Author: flashytime
 * Date: 2015/8/27 21:20
 */

namespace Flashytime\IdGenerator\Provider;


class LaravelIdGeneratorServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $configPath = __DIR__ . '/../config/id-generator.php';
        if (function_exists('config_path')) {
            $publishPath = config_path('id-generator.php');
        } else {
            $publishPath = base_path('config/id-generator.php');
        }
        $this->publishes([$configPath => $publishPath], 'config');
    }
}