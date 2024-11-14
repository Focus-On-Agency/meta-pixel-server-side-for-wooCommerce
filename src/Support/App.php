<?php

namespace Focuson\MPSFW\Support;

class App
{
    protected static $instance = null;
    public $bladeInstance;
	public $cacheInstance;
    public $configInstance;

    private function __construct()
    {
        $this->configInstance = new Config();
		$this->registerProviders();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

	protected function registerProviders()
    {
        foreach (glob(__DIR__ . '/../Providers/*.php') as $providerFile)
		{
            $fileName = basename($providerFile, '.php');
			$namespaceBase = str_replace('Support', '', __NAMESPACE__);
			$providerClass = $namespaceBase . 'Providers\\' . $fileName;

            if (class_exists($providerClass)) {
                $provider = new $providerClass($this);
                
                // Check if the provider has a register method
                if (method_exists($provider, 'register')) {
                    $provider->register();
                }
            }
        }
    }

    public function view($view, $data = [])
    {
        return $this->bladeInstance->render($view, $data);
    }

    public function config($key, $default = null)
    {
        return $this->configInstance->get($key, $default);
    }
}
