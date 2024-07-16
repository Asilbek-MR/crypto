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
 * Class Rss
 *
 * @package		CoinTable
 * @subpackage	Libraries
 * @author		RunCoders
 */

class Rss
{

    /**
     * Rss constructor.
     *
     * includes SimplePie (http://simplepie.org/) feed parser
     */

    public function __construct()
    {
        // SimplePie folder path
        $this->path = APPPATH.'third_party'.DIRECTORY_SEPARATOR.'SimplePie'.DIRECTORY_SEPARATOR;
        // require the classes autoloader file
        require_once $this->path.'autoloader.php';
    }

}
