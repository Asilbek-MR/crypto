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
 * Class Cronjob
 *
 * @package		CoinTable
 * @subpackage	Controllers
 * @author		RunCoders
 */

class Cronjob extends CT_Controller
{
    /**
     * Executes synchronisation
     * (Read documentation: Setup - Cron job)
     */
    public function index()
    {
        $this->coin_table->sync();
    }
}
