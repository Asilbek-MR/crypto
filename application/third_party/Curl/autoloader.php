<?php

// autoloader
spl_autoload_register(array(new Curl_Autoloader(), 'autoload'));


class Curl_Autoloader
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classes';
    }

    /**
     * Autoloader
     *
     * @param string $class The name of the class to attempt to load.
     */
    public function autoload($class)
    {
        if (strpos($class, 'Curl\\') !== 0)
        {
            return;
        }

        $class_file = str_replace('Curl\\','', $class) . '.php';
        $filepath = $this->path . DIRECTORY_SEPARATOR . $class_file;

        include $filepath;
    }
}