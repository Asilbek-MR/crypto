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
 * Class CT_Controller
 *
 * @package		CoinTable
 * @subpackage	Core
 * @category    Controllers
 * @author		RunCoders
 */

class CT_Controller extends CI_Controller {

	/**
	 * Holds visitor's selected price currency
	 * @var string
	 */

	protected $price_currency;

	// --------------------------------------------------------------------

	/**
	 * Holds visitor's selected price currency rate information
	 * @var object
	 */

	protected $price_rate;

	// --------------------------------------------------------------------

	/**
	 * Holds visitor's language code
	 * @var string
	 */

	protected $lang_code;

	// --------------------------------------------------------------------

	/**
	 * Holds visitor's language information
	 * @var object
	 */

	protected $language;

	// --------------------------------------------------------------------

    /**
     * If true, timezone will be defined on constructor
     *
     * @var bool
     */
    protected $set_timezone = true;

    /**
     * If true, timezone will be defined on constructor
     *
     * @var bool
     */
    protected $set_default_lang = true;


    /**
     * CT_Controller constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();

		if ($this->tableExists('options')) {
			if ($this->set_timezone) $this->coin_table->setTimeZone();
			if ($this->set_default_lang) $this->coin_table->setDefaultLang();
		}
    }

	// --------------------------------------------------------------------

	/**
	 * @param string $table
	 *
	 * @return bool
	 */
	protected function tableExists($table) {
		return $this->db->table_exists($table);
	}

	// --------------------------------------------------------------------

	/**
	 * @return null|string
	 */
	protected function currentDBVersion() {
		if ($this->tableExists('options')) {
			return $this->coin_table->getOption('db_version', null, false);
		}
		return null;
	}

    // --------------------------------------------------------------------

    /**
     * Load Ion Auth shorter
     */

    protected function loadAuth()
    {
        $this->load->library('ion_auth');
    }

    // --------------------------------------------------------------------

    /**
     * Generate CSRF nonce
     *
     * @return array
     */

    public function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key   = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    // --------------------------------------------------------------------

    /**
     * Validates CSRF nonce
     * @return bool
     */

    public function _valid_csrf_nonce()
    {
        $csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
        if ($csrfkey && $csrfkey == $this->session->flashdata('csrfvalue'))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Render page
     *
     * @param string $view
     * @param array|null $data
     * @param bool $returnhtml
     *
     * @return mixed
     */

    public function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
    {

        $this->viewdata = (empty($data)) ? $this->data: $data;

        $view_html = $this->load->view($view, $this->viewdata, $returnhtml);

        if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
    }

    // --------------------------------------------------------------------

    /**
     * Decodes input JSON
     *
     * @return mixed
     */

    protected function getJson()
    {
        $data = $this->input->raw_input_stream;
        return json_decode($data, true);
    }

    // --------------------------------------------------------------------

    /**
     * Ensures all keys in array
     *
     * @param array $array
     * @param array $keys
     *
     * @return bool
     */

    protected function areSet($array, $keys)
    {
        foreach ($keys as $key){
            if(!array_key_exists($key, $array))
                return false;
        }
        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Sets CORS headers
     *
     */

    protected function corsHeaders()
    {
        $this->output->set_header('Access-Control-Allow-Origin: *');
        $this->output->set_header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method');
        $this->output->set_header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
    }

	// --------------------------------------------------------------------

	/**
	 *
	 */
	protected function disallowCacheHeaders() {
		$this->output->set_header('pragma: no-cache');
		$this->output->set_header('cache-control: no-store');
	}

	// --------------------------------------------------------------------

	/**
	 *
	 */
	protected function allowCacheHeaders($max_age = 60) {
		$this->output->set_header('pragma: public');
		$this->output->set_header('cache-control: public, max-age='.$max_age);
	}

    // --------------------------------------------------------------------

    /**
     * Encodes data to JSON and outputs it
     * Also sets the response status
     *
     * @param mixed $res
     * @param int|bool $status
     * @param string|array|null $headers
     */

    protected function sendJson($res = null, $status = 200, $headers = null)
    {
        // for easier control
        if($status === true) $status = 200;
        if($status === false) $status = 404;

        // set custom headers if defined
        if(is_array($headers)){
            foreach ($headers as $header){
                $this->output->set_header($header);
            }
        }
        elseif (is_string($headers)){
            $this->output->set_header($headers);
        }

        // Send JSON response
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($status)
            ->set_output(json_encode($res, JSON_NUMERIC_CHECK))
            ->_display();
        exit;
    }

	// --------------------------------------------------------------------

	/**
	 * Sets the price currency to be used
	 *
	 */

	protected function setPriceCurrency()
	{
		// gets cookie value
		$this->price_currency = $this->input->cookie('ct_price_currency');

		// if not exists will be use default
		if(!$this->price_currency) {
			$this->price_currency = $this->coin_table->settingsGet('general','default_price');
		}

		$this->price_rate = $this->coin_table->getExchangeRates($this->price_currency);
	}

	// --------------------------------------------------------------------

	/**
	 * Sets the language to be used
	 *
	 */

	protected function setLanguage()
	{
		$languages      = $this->config->item('languages_available');
		$default_lang   = $this->coin_table->settingsGet('general','language');

		// gets cookie value
		$lang = $this->input->cookie('ct_language');

		// if not exists will be use default
		if(!$lang || empty($languages[$lang])) {
			$lang = $default_lang;
		}

		// add language to CoinTable lib
		$this->coin_table->visitor_lang = $lang;

		$this->lang_code                = $lang;
		$this->language                 = $languages[$lang];
		// load the translation pack
		$this->lang->load('coin_table', $this->language['directory']);
	}

}
