<?php
/**
 * Created by IntelliJ IDEA.
 * Author: flashytime
 * Date: 2015/8/27 21:20
 */

namespace Flashytime\IdGenerator\Provider;


class LumenIdGeneratorServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $configPath = __DIR__ . '/../config/id-generator.php';
        $this->mergeConfigFrom($configPath, 'id-generator');
    }
}