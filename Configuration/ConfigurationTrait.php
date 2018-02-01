<?php
namespace MCPS\Helper\Configuration;

trait ConfigurationTrait
{

    private function defaultConfig()
    {
        throw new \LogicException('You must override the defaultConfig() method. The method return an array.');
    }

    public final function setConfig(array $config)
    {
        $defaultConfig = $this->defaultConfig();
        foreach ($defaultConfig as $defaultConfigKey => $defaultConfigValue) {
            if (array_key_exists($defaultConfigKey, $config)) {
                $defaultType = gettype($defaultConfigValue);
                if (!settype($config[$defaultConfigKey], $defaultType)) {
                    $config[$defaultConfigKey] = $defaultConfigValue;
                }
            }
        }
        \Configuration::updateValue(strtoupper($this->name), serialize($config), true);
    }

    public final function getConfig()
    {
        $defaultConfig = $this->defaultConfig();
        if (!$dbConfig = @unserialize(\Configuration::get(strtoupper($this->name)))) {
            return $defaultConfig;
        }
        return array_merge($defaultConfig, $dbConfig);
    }
}
