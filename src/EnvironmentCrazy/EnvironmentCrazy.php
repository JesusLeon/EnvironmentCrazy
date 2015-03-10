<?php

namespace EnvironmentCrazy;

class EnvironmentCrazy {

    protected $environment_variable = 'ENVIRONMENT';

    protected $productive = 'productive';

    protected $private = 'private';

    protected $staging = 'staging';

    protected $local = 'local';

    protected $environment;

    private $withDotenv = false;

    function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->initDotenv();

        $this->initEnvironment();
    }

    /**
     * Set the environment class variable.
     */
    private function initEnvironment()
    {
        $this->environment = $this->get($this->environment_variable, $this->local);
    }

    /**
     * @return bool
     */
    public function isProductive()
    {
        return $this->environment == $this->productive;
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        return $this->environment == $this->private;
    }

    /**
     * @return bool
     */
    public function isStaging()
    {
        return $this->environment == $this->staging;
    }

    /**
     * @return bool
     */
    public function isLocal()
    {
        return $this->environment == $this->local;
    }

    /**
     * Gets the value of an environment variable. Supports boolean, empty and null.
     *
     * Borrowed from Illuminate/Foundation/helpers.php
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function get($key=null, $default = null)
    {
        if(is_null($key)) return $this->environment;

        $value = getenv($key);

        if($value === false) return $default;

        switch (strtolower($value))
        {
            case 'true':
            case '(true)':
                return true;

            case 'false':
            case '(false)':
                return false;

            case 'null':
            case '(null)':
                return null;

            case 'empty':
            case '(empty)':
                return '';
        }

        if (
            ($value != '' && strpos('"', $value) === 0)
            && (string) $value === substr('"', -strlen($value))
        )
        {
            return substr($value, 1, -1);
        }

        return $value;
    }

    /**
     * @param $value
     * @return null
     */
    public function setIfProductive($value)
    {
        return $this->setIf($this->productive, $value);
    }

    /**
     * @param $value
     * @return null
     */
    public function setIfPrivate($value)
    {
        return $this->setIf($this->private, $value);
    }

    /**
     * @param $value
     * @return null
     */
    public function setIfStaging($value)
    {
        return $this->setIf($this->staging, $value);
    }

    /**
     * @param $value
     * @return null
     */
    public function setIfLocal($value)
    {
        return $this->setIf($this->local, $value);
    }

    /**
     * @param $value
     * @return null
     */
    public function setIfElse($value)
    {
        return $this->setIf('else', $value);
    }

    /**
     * @param $environment
     * @param $value
     * @return null
     */
    public function setIf($environment, $value=null)
    {
        if(is_string($environment) && ! is_null($value))
        {
            if($environment == $this->environment || $environment == 'else')
            {
                return $value;
            }

            return null;
        }

        if(array_key_exists($this->environment, (array) $environment))
        {
            return $environment[$this->environment];
        }

        if(array_key_exists('else', (array) $environment))
        {
            return $environment['else'];
        }

        return null;
    }

    /**
     * If present, initialize vlucas/dotenv.
     */
    private function initDotenv()
    {
        $this->withDotenv = class_exists('Dotenv');

        if($this->withDotenv) \Dotenv::load(dirname(dirname(__DIR__)));
    }

    /**
     * Make Dotenv immutable. This means that once set, an environment variable cannot be overridden.
     */
    public function makeImmutable()
    {
        if($this->withDotenv) \Dotenv::makeImmutable();
    }

    /**
     * Make Dotenv mutable. Environment variables will act as, well, variables.
     */
    public function makeMutable()
    {
        if($this->withDotenv) \Dotenv::makeMutable();
    }

}