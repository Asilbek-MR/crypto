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
 * Class Admin
 *
 * @package		CoinTable
 * @subpackage	Controllers
 * @author		RunCoders
 */

class Admin extends CT_Controller
{

    /**
     * Admin constructor.
     *
     * Load Ion Auth library and check logged user
     *
     */

    public function __construct()
    {
        parent::__construct();

        $this->loadAuth();

        if(!$this->ion_auth->is_admin()) redirect('auth/login', 'refresh');
    }

    // --------------------------------------------------------------------

    /**
     * Date formats available with current date
     *
     * @return array
     */

    private function dateFormatExamples()
    {
        $examples = array();

        foreach ($this->config->item('date_formats') as $format) {
            $examples[] = array($format, date($format));
        }

        return $examples;
    }

    // --------------------------------------------------------------------

    /**
     * Time formats available with current time
     *
     * @return array
     */

    private function timeFormatExamples()
    {
        $examples = array();

        foreach ($this->config->item('time_formats') as $format) {
            $examples[] = array($format, date($format));
        }

        return $examples;
    }

    // --------------------------------------------------------------------

    /**
     * Languages available values
     *
     * @return array
     */

    private function languageValues()
    {
        $values = array();
        foreach ($this->config->item('languages_available') as $code => $details) {
            $values[] = array(
                'name'  => "{$details['name']} ($code)",
                'value' => $code
            );
        }

        return $values;
    }

    // --------------------------------------------------------------------
    /**
     * Base page is send here
     * Other pages are async templates
     *
     */

    public function index()
    {
        $url = site_url();

        $data = new stdClass();
        $data->favicon  = '';
        $data->title    = 'Coin Table - Administration';

        $data->css = array(
            'https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.css',
            'https://cdn.jsdelivr.net/npm/semantic-ui-calendar@0.0.8/dist/calendar.min.css',
            'https://cdn.datatables.net/v/se/dt-1.11.3/fc-4.0.1/fh-3.2.0/datatables.min.css',
            adminAsset('css','admin.css?v='.COINTABLE)
        );

        $data->js = array(
            'https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js',
            'https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.js',
            'https://cdn.jsdelivr.net/npm/semantic-ui-calendar@0.0.8/dist/calendar.min.js',
            'https://cdn.jsdelivr.net/npm/angular@1.8.2/angular.min.js',
            'https://cdn.jsdelivr.net/npm/angular-ui-router@1.0.29/release/angular-ui-router.min.js',
            'https://cdn.datatables.net/v/se/dt-1.11.3/datatables.min.js',
            'https://cdn.datatables.net/v/se/dt-1.11.3/fc-4.0.1/fh-3.2.0/datatables.min.js',
            'https://cdn.jsdelivr.net/npm/moment@2.29.1/min/moment.min.js',
            adminAsset('js','admin.min.js?v='.COINTABLE)
        );

        $data->menu = array(
            array('general',            'setting',              'General Settings'),
            array('social',             'thumbs up',            'Social'),
            array('header_menu',        'sidebar',              'Header & Menu'),
            array('donation',           'hand peace',           'Donation'),
            array('footer',             'ellipsis horizontal',  'Footer'),
            array('coins',              'bitcoin',              'Coins'),
            array('market_page',        'unordered list',       'Market Page'),
            array('press_page',         'feed',                 'Press Page'),
            array('mining_page',        'microchip',            'Mining Page'),
            array('converter_page',     'retweet',              'Converter Page'),
            //array('icos_page',          'calendar check',       'ICOs Page'),
            array('exchanges_page',     'sync',                 'Exchanges Page'),
            array('services_page',      'shopping cart',        'Services Page'),
            array('currency_page',      'chart line',           'Currency Page'),
            array('custom_pages',       'file outline',         'Custom Pages'),
            array('users',              'user',                 'Users'),
            array('trends_page',        'chart line',           'Trends Page')
        );

        usort($data->menu, function ($a, $b) {
            return strcasecmp($a[2], $b[2]);
        });

        $data->copyrights = array(
            '&copy; RunCoders',
            'https://runcoders.net/'
        );

        $constants = $data->constants = new stdClass();

        $constants->social_networks     = $this->config->item('social_networks');
        $constants->twitter_cards       = $this->config->item('twitter_cards');
        $constants->themes              = $this->config->item('themes');
        $constants->timezones           = timeZonesValues();
        $constants->languages           = $this->languageValues();
        $constants->date_formats        = $this->dateFormatExamples();
        $constants->time_formats        = $this->timeFormatExamples();
        $constants->web_miners          = $this->config->item('web_miners');
        $constants->image_placeholder   = adminAsset('images','placeholder.png');

        $constants->market_columns = array(
            array('name'  => 'Price',               'value' => 'price'),
            array('name'  => 'Market Cap',          'value' => 'market_cap'),
            array('name'  => 'Total Supply',        'value' => 'total_supply'),
            array('name'  => 'Circulating Supply',  'value' => 'circulating_supply'),
            array('name'  => 'Volume 24h',          'value' => 'volume_24h'),
            array('name'  => 'Change 24h',          'value' => 'change_24h'),
            array('name'  => 'Chart 7d',            'value' => 'chart_7d')
        );

        $urls = $constants->urls = new stdClass();
        $urls->admin                = $url.'admin/';
        $urls->api                  = $url.'api/';
        $urls->custom_pages         = $url.CT_CUSTOM_PAGES.'/';
        $urls->market_page          = $url.CT_MARKET_PAGE.'/';
        $urls->converter_page       = $url.CT_CONVERTER_PAGE.'/';
        $urls->icos_page            = $url.CT_ICOS_PAGE.'/';
        $urls->mining_page          = $url.CT_MINING_PAGE.'/';
        $urls->press_page           = $url.CT_PRESS_PAGE.'/';
        $urls->exchanges_page       = $url.CT_EXCHANGES_PAGE.'/';
        $urls->services_page        = $url.CT_SERVICES_PAGE.'/';
        $urls->trends_page          = $url.CT_TRENDS_PAGE.'/';
        $urls->currency_page        = $url.CT_CURRENCY_PAGE.'/';
        $urls->logout               = $url.'auth/logout';


        $this->load->view('admin/base', $data);
    }

    // --------------------------------------------------------------------

    /**
     * Get HTML template for Admin Panel page
     *
     * @param string $state
     */

    public function template($state)
    {
        $this->load->view("admin/templates/$state");
    }

}
