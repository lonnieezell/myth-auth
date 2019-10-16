<?php namespace Myth\Auth\Authentication\Activators;

class BaseActivator
{
    protected $config;

    /**
     * Allows for setting a config file on the Activator.
     *
     * @param $config
     *
     * @return $this
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Gets a config settings for current Activator.
     *
     * @return $array
     */
    public function getActivatorSettings()
    {
        return (object) $this->config->userActivators[get_class($this)];
    }

}
