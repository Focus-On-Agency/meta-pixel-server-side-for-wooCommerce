<?php

namespace Focuson\MPSFW\Support;

use Focuson\MPSFW\Support\App;

class BaseServiceProvider
{
    protected $app;
    protected $view;
    protected $config;

    public function __construct(App $app)
    {
        $this->app = $app;
    }
}
