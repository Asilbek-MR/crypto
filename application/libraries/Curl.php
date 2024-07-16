<?php
/**
 * CoinTable
 *
 * A content management system for cryptocurrency related information.
 *
 * This content is released under the CodeCanyon Standard Licenses.
 *
 * Copyright (c) 2017 - 2021, RunCoders
 *
 *
 * @package   CoinTable
 * @author    RunCoders
 * @license	  https://codecanyon.net/licenses/standard?ref=RunCoders
 * @copyright Copyright (c) 2017 - 2021, RunCoders (https://runcoders.net)
 * @since	  Version 2.0
 *
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Curl_lib
 *
 * @package		MyEnvato
 * @subpackage	Libraries
 * @author		RunCoders
 */


class Curl
{

    /**
     * Curl_lib constructor.
     *
     * includes PHP Curl Class (https://github.com/php-curl-class/php-curl-class)
     */

    public function __construct()
    {
        // Curl folder path
        $this->path = APPPATH.'third_party'.DIRECTORY_SEPARATOR.'Curl'.DIRECTORY_SEPARATOR;
        // require the classes autoloader file
        require_once $this->path.'autoloader.php';
    }

}
