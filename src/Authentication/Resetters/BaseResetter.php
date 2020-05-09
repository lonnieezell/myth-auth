<?php namespace Myth\Auth\Authentication\Resetters;

class BaseResetter
{
    protected $config;

    /**
     * Allows for setting a config file on the Resetter.
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
     * Gets a config settings for current Resetter.
     *
     * @return $array
     */
    public function getResetterSettings()
    {
        return (object) $this->config->userResetters[get_class($this)];
    }

}
