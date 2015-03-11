<?php

namespace EnvironmentCrazy;

class EnvironmentCrazy {

    protected static $environment_variable = 'ENVIRONMENT';

    protected static $productive = 'productive';

    protected static $private = 'private';

    protected static $staging = 'staging';

    protected static $local = 'local';

    private static $environment;

    private static $strip_quotes = true;

    private static $cast_types = true;

    private static $initiated = false;

    /**
     * Set the initial value.
     */
    private static function init()
    {
        if(self::$initiated) return;

        self::$initiated = true;

        self::initEnvironment();
    }

    /**
     * Set the environment class variable.
     */
    private static function initEnvironment()
    {
        self::$environment = static::get(static::$environment_variable, static::$local);
    }

    /**
     * @return bool
     */
    public static function isProductive()
    {
        return static::setIf(static::$productive, true, false);
    }

    /**
     * @return bool
     */
    public static function isPrivate()
    {
        return static::setIf(static::$private, true, false);
    }

    /**
     * @return bool
     */
    public static function isStaging()
    {
        return static::setIf(static::$staging, true, false);
    }

    /**
     * @return bool
     */
    public static function isLocal()
    {
        return static::setIf(static::$local, true, false);
    }

    /**
     * @param $value
     * @param null $default
     * @return null
     */
    public static function setIfProductive($value, $default = null)
    {
        return static::setIf(static::$productive, $value, $default);
    }

    /**
     * @param $value
     * @param null $default
     * @return null
     */
    public static function setIfPrivate($value, $default = null)
    {
        return static::setIf(static::$private, $value, $default);
    }

    /**
     * @param $value
     * @param null $default
     * @return null
     */
    public static function setIfStaging($value, $default = null)
    {
        return static::setIf(static::$staging, $value, $default);
    }

    /**
     * @param $value
     * @param null $default
     * @return null
     */
    public static function setIfLocal($value, $default = null)
    {
        return static::setIf(static::$local, $value, $default);
    }

    /**
     * @param $value
     * @param null $default
     * @return null
     */
    public static function setIfElse($value, $default = null)
    {
        return static::setIf('else', $value, $default);
    }

    /**
     * @param $environment
     * @param $value
     * @param null $default
     * @return null
     */
    public static function setIf($environment, $value = null, $default = null)
    {
        self::init();

        if(is_string($environment) && ! is_null($value))
        {
            if($environment == self::$environment || $environment == 'else')
            {
                return $value;
            }

            if( ! is_null($default)) return $default;

            return null;
        }

        if(array_key_exists(self::$environment, (array) $environment))
        {
            return $environment[self::$environment];
        }

        if(array_key_exists('else', (array) $environment))
        {
            return $environment['else'];
        }

        return null;
    }

    /**
     * Gets the value of an environment variable. Supports boolean, empty and null.
     *
     * Borrowed from Illuminate/Foundation/helpers.php
     *
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    public static function get($key = null, $default = null)
    {
        self::init();

        if(is_null($key)) return self::$environment;

        $value = getenv($key);

        if($value === false) return $default;

        $value = self::_cast_types($value);

        $value = self::_strip_quotes($value);

        return $value;
    }

    /**
     * @param $value
     * @return bool|null|string
     */
    private static function _cast_types($value)
    {
        if( ! self::$cast_types) return $value;

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

        return $value;
    }

    /**
     * @param $value
     * @return string
     */
    private static function _strip_quotes($value)
    {
        if( ! self::$strip_quotes) return $value;

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
     * @param bool $value
     */
    public static function castTypes($value = true)
    {
        self::$cast_types = $value;
    }

    /**
     * @param bool $value
     */
    public static function stripQuotes($value = true)
    {
        self::$strip_quotes = $value;
    }

}