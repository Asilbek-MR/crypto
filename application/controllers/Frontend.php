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
 * Class Frontend
 *
 * @package		CoinTable
 * @subpackage	Controllers
 * @author		RunCoders
 */

class Frontend extends CT_Controller
{

    /**
     * Acceptance of cookie use variable
     * @var bool
     */

    private $gdpr_checked;

    // --------------------------------------------------------------------

    /**
     * Frontend constructor.
     *
     * Checks URL params for price currency overwrite
     * Or fetch it from cookie
     */

    public function __construct()
    {
        parent::__construct();

		if ($this->tableExists('options')) {
			$this->setPriceCurrency();
			$this->setLanguage();
			$this->gdpr_checked = $this->input->cookie('ct_gdpr_checked');
		}
    }

    // --------------------------------------------------------------------

    /**
     * Replaces in data custom SEO fields
     *
     * @param object $data
     * @param array $option
     */

    private function joinCustomSEO($data, $option)
    {
        if(empty($option['seo_enabled']))
            return;

        $seo = array();

        if(!empty($option['seo_title']))
            $seo['title'] = $option['seo_title'];

        if(!empty($option['seo_description']))
            $seo['description'] = $option['seo_description'];

        if(!empty($option['seo_og_image_url']))
            $seo['og_image'] = $option['seo_og_image_url'];

        if(!empty($option['seo_twitter_image_url']))
            $seo['twitter_image'] = $option['seo_twitter_image_url'];

        joinToData($data, $seo);
    }

    // --------------------------------------------------------------------

    /**
     * Base information & HTML for front-end page.
     *
     * Will print the final page.
     * Extra array allows standard param override.
     *
     * @param string $content
     * @param object $extra
     *
     */

    private function baseView($content, $extra = null)
    {
        // fetch options
        $general          = $this->coin_table->settingsGet('general');
        $header_menu      = $this->coin_table->settingsGet('header_menu');
        $footer           = $this->coin_table->settingsGet('footer');
        $theme            = $general['layout_theme'];
        $themes           = $this->config->item('themes');
        $theme_details    = $themes[$theme];


        // determine the column number on footer
        // empty menu will not appear
        $count_footer_cols = 1;

        if(count($footer['menu1'])) $count_footer_cols++;
        if(count($footer['menu2'])) $count_footer_cols++;
        if(count($footer['menu3'])) $count_footer_cols++;

        if($count_footer_cols === 2) $footer_col_wide = 'eight';
        elseif ($count_footer_cols === 3) $footer_col_wide = 'five';
        elseif ($count_footer_cols === 4) $footer_col_wide = 'four';
        else $footer_col_wide = 'sixteen';

        // miner script details
        $mining_script    = $this->coin_table->settingsGet('mining_script');
        $web_miners       = $this->config->item('web_miners');
        $mining           = null;

        if($mining_script['miner'] !== 'disabled' && isset($web_miners[$mining_script['miner']])) {
            $miner  = $web_miners[$mining_script['miner']];

            $mining = array(
                'script'        => $miner['script'],
                'constructor'   => $miner['constructor'],
                'key'           => $mining_script['key'],
                'throttle'      => $mining_script['throttle']
            );
        }

        // Global stats for top labels
        $global_stats           = $this->coin_table->getGlobalStats();
        $market_cap_percentages = $global_stats->market_cap_percentage;
        $display_mcps           = array();

        foreach ($market_cap_percentages as $symbol => $percentage) {
            $display_mcps[] = "$symbol $percentage%";
        }

        // data passed to base view
        // see implementation on views/frontend/base

        $data                           = new stdClass();
        $data->languages                = $this->config->item('languages_available');
        $data->lang                     = $this->lang_code;
        $data->favicon                  = $general['favicon_url'];
        $data->logo                     = $general['logo_url'];
        $data->url                      = current_url();
        $data->type                     = 'website';
        $data->title                    = _t($general['title']);
        $data->description              = _t($general['description']);
        $data->name                     = $general['name'];
        $data->og_image                 = $general['og_image_url'];
        $data->twitter_card             = $general['twitter_card'];
        $data->twitter_image            = $general['twitter_image_url'];
        $data->twitter_username         = $general['twitter_username'];
        $data->twitter_creator          = $general['twitter_creator'];
        $data->theme                    = $theme;
        $data->theme_color              = $theme_details[1];
        $data->price_currency           = $this->price_currency;
        $data->custom_css               = $general['custom_css'];
        $data->custom_html              = $general['custom_html'];
        $data->social                   = $this->coin_table->settingsGet('social');
        $data->header                   = $header_menu;
        $data->footer                   = $footer;
        $data->donation                 = $this->coin_table->settingsGet('donation');
        $data->sidebar_side             = ($header_menu['style'] === 'left' || $header_menu['style'] === 'left_sidebar') ? 'left' : 'right';
        $data->padding_top              = $header_menu['brand_type'] === 'name' ? '100px' : ($header_menu['logo_height'] + 80).'px';
        $data->footer_col_wide          = $footer_col_wide;
        $data->content                  = $content;
        $data->mining                   = $mining;
        $data->gdpr                     = $general['gdpr_enabled'] && empty($this->gdpr_checked);
        $data->gdpr_title               = _t($general['gdpr_title']);
        $data->gdpr_message             = _t($general['gdpr_message']);

        // convert global stats values to current price currency
        $stats = $data->stats = new stdClass();
        $stats->market_cap              = ct_number_format($this->coin_table->fx('USD', $this->price_currency, $global_stats->total_market_cap_usd));
        $stats->volume                  = ct_number_format($this->coin_table->fx('USD', $this->price_currency, $global_stats->total_volume_24h_usd));
        $stats->exchanges               = $this->coin_table->settingsGet('exchanges_page','enabled') ? $global_stats->total_exchanges : null;
        $stats->cryptocurrencies        = $global_stats->total_cryptocurrencies;
        $data->market_cap_percentages   = implode('&nbsp;&nbsp;&nbsp;', $display_mcps);

        // Main URLs
        $urls = $data->urls = new stdClass();
        $urls->market_page      = site_url(CT_MARKET_PAGE);
        $urls->currency_page    = site_url(CT_CURRENCY_PAGE);
        $urls->press_page       = site_url(CT_PRESS_PAGE);
        $urls->mining_page      = site_url(CT_MINING_PAGE);
        $urls->services_page    = site_url(CT_SERVICES_PAGE);
        $urls->icos_page        = site_url(CT_ICOS_PAGE);
        $urls->exchanges_page   = site_url(CT_EXCHANGES_PAGE);
        $urls->api              = site_url('api');

        $data->css = array(
            'https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.css',
            frontendAsset('css','frontend.css?v='.COINTABLE)
        );

        $data->js = array(
            'https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js',
            'https://cdn.jsdelivr.net/npm/jquery.cookie@1.4.1/jquery.cookie.min.js',
            'https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.js',
            'https://cdn.jsdelivr.net/npm/money@0.2.0/money.min.js',
            frontendAsset('js','frontend.min.js?v='.COINTABLE)
        );

        $constants = $data->constants = new stdClass();
        $constants->price_currency  = $this->price_currency;
        $constants->price_rate      = $this->price_rate;
        $constants->urls            = $urls;

        // join the overridden params
        joinToData($data, $extra);

        // send frontend page
        $this->load->view('frontend/base', $data);
    }

    // --------------------------------------------------------------------

    /**
     * Gets the generated HTML content for page
     *
     * @param string $page
     * @param array|null $data
     *
     * @return string
     */

    private function pageView($page, $data = null)
    {
        return $this->load->view("frontend/pages/$page", $data, true);
    }

    // --------------------------------------------------------------------

    /**
     * Shows defined page as homepage
     *
     */

	public function index()
    {
		// should install?
		if (file_exists(__DIR__ . '/Install.php')) {
			redirect('install');
		}
		// should upgrade?
	    $db_version = $this->currentDBVersion();
		if ((!$db_version || version_compare($db_version, CT_DB_VERSION, '<'))
		    && file_exists(__DIR__ . '/Upgrade.php')) {
			redirect('upgrade');
		}

        $fp = $this->coin_table->settingsGet('general','front_page');

        switch($fp) {
            case 'market_page':
                $this->market();
                break;
            case 'press_page':
                $this->press();
                break;
            case 'mining_page':
                $this->mining();
                break;
            case 'converter_page':
                $this->converter();
                break;
            case 'icos_page':
                $this->icos();
                break;
            case 'exchanges_page':
                $this->exchanges();
                break;
            case 'services_page':
                $this->services();
                break;
            case 'trends_page':
                $this->trends();
                break;
            default:
                $this->pages($fp);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Market Page
     *
     * Shows cryptocurrency table
     *
     * @param null|int $page
     *
     * @since 5.2.0 Asynchronous charts (7 days)
     *
     */

    public function market($page = null)
    {
        // fetch options
        $save           = $this->input->cookie('ct_market_search_save') === 'true';
        $market_page    = $this->coin_table->settingsGet('market_page');
        // URL params
        $search_params  = $this->input->get();

        // restore search if visitor enabled 'save' option
        if(empty($search_params) && $save && ($saved_search = $this->input->cookie('ct_market_search'))) {
            $search_params = unserialize($saved_search);
            if(!is_array($search_params)) $search_params = array();
        }

        // get results and pagination
        $results = $this->coin_table->searchCoins($search_params, $market_page['page_size'], $page);

        // keep current search if visitor enabled 'save' option
        if($save) {
            $this->input->set_cookie(array(
                'prefix'    => 'ct_',
                'name'      => 'market_search',
                'value'     => serialize($search_params),
                'expire'    => 60*24*60*60,
                'httponly'  => true
            ));
        }

		$slugs = [];

        foreach ($results->pagination->items as &$c) {
			$slugs[] = $c['slug'];

            // convert values to current price currency
            $c['price']         = priceFormat($this->coin_table->fx('USD', $this->price_currency, $c['price_usd']), $this->price_rate);
            $c['volume']        = priceFormat($this->coin_table->fx('USD', $this->price_currency, $c['volume_24h_usd']), $this->price_rate);
            $c['market_cap']    = priceFormat($this->coin_table->fx('USD', $this->price_currency, $c['market_cap_usd']), $this->price_rate);

            if(isset($c['info'])) {
                $c['name'] = trim(_t($c['info']->localization, $c['name'])); // coin's name translated

            }


	        if (!empty($c['symbol']) && strlen($c['symbol']) > 25) $c['symbol'] = substr($c['symbol'], 0, 22) . '...';
	        if (!empty($c['name']) && strlen($c['name']) > 25) $c['name'] = substr($c['name'], 0, 22) . '...';

            if(!isset($c['tracking_slug'])) { // if not custom asset
                $c['image_small'] = coinImageUrl($c['slug'], $c['image_small']); // image url
            }
        }

        $params     = $results->params;
        $extremes   = $results->extremes;

        // Ion.RangeSliders values

        $market_cap_slider          = new stdClass();
        $market_cap_slider->values  = sliderValues($extremes->market_cap->min, $extremes->market_cap->max);
        sliderValueIndex($market_cap_slider, $params->mcf,'from');
        sliderValueIndex($market_cap_slider, $params->mct,'to');

        $price_slider           = new stdClass();
        $price_slider->values   = sliderValues($extremes->price->min, $extremes->price->max);
        sliderValueIndex($price_slider, $params->pf,'from');
        sliderValueIndex($price_slider, $params->pt,'to');

        $volume_slider          = new stdClass();
        $volume_slider->values  = sliderValues($extremes->volume->min, $extremes->volume->max);
        sliderValueIndex($volume_slider, $params->vf,'from');
        sliderValueIndex($volume_slider, $params->vt,'to');

        // data passed to market view
        $data                       = new stdClass();
        $data->theme                = $this->coin_table->settingsGet('general','layout_theme');
        $data->results              = $results;
        $data->placeholder          = frontendAsset('images','placeholder.png');
        $data->redirect             = site_url(CT_MARKET_PAGE);
        $data->currency_redirect    = site_url(CT_CURRENCY_PAGE);
        $data->market_cap_slider    = $market_cap_slider;
        $data->price_slider         = $price_slider;
        $data->volume_slider        = $volume_slider;


        // join market page settings
        joinToData($data, $market_page);

        // prepare data for base view
        // include market script
        $extra_data         = new stdClass();
        $extra_data->title  = _t($market_page['title']); // translated title

        $extra_data->js = array(
            'https://cdn.jsdelivr.net/npm/echarts@4.9.0/dist/echarts-en.min.js',
            'https://cdn.jsdelivr.net/npm/ion-rangeslider@2.2.0/js/ion.rangeSlider.min.js',
            'https://cdn.datatables.net/v/se/dt-1.11.3/fc-4.0.1/fh-3.2.0/datatables.min.js',
            frontendAsset('js','market.min.js?v='.COINTABLE)
        );
        $extra_data->css = array(
            'https://cdn.jsdelivr.net/npm/ion-rangeslider@2.2.0/css/ion.rangeSlider.min.css',
            'https://cdn.jsdelivr.net/npm/ion-rangeslider@2.2.0/css/ion.rangeSlider.skinNice.min.css',
            'https://cdn.datatables.net/v/se/dt-1.11.3/fc-4.0.1/fh-3.2.0/datatables.min.css'
        );
        $extra_data->constants = array(
            'market_cap_slider' => $market_cap_slider,
            'price_slider'      => $price_slider,
            'volume_slider'     => $volume_slider,
            'slugs'             => $slugs,
            'params'            => $params
        );

        // join custom SEO params
        $this->joinCustomSEO($extra_data, $market_page);

        // show market page
        $this->baseView($this->pageView('market', $data), $extra_data);
    }

    // --------------------------------------------------------------------

    /**
     * Currency Page
     *
     * Shows Currency specific information
     * 'slug' will be defined by CoinGecko
     *
     * @param string $slug
     *
     */

    public function currency($slug)
    {
        // fetch cryptocurrency info
        $coin = $this->coin_table->getCoin($slug);

        if(!is_array($coin)) $coin = $this->coin_table->getCustomAsset($slug);

        // show not found page if not exists
        if(!is_array($coin)) show_404();

        $general            = $this->coin_table->settingsGet('general');
        $currency_page      = $this->coin_table->settingsGet('currency_page');

        // convert values to current price currency
        $coin['price']      = priceFormat($this->coin_table->fx('USD', $this->price_currency, $coin['price_usd']));
        $coin['volume']     = priceFormat($this->coin_table->fx('USD', $this->price_currency, $coin['volume_24h_usd']), $this->price_rate);
        $coin['market_cap'] = priceFormat($this->coin_table->fx('USD', $this->price_currency, $coin['market_cap_usd']), $this->price_rate);

        if(!isset($coin['tracking_slug'])) { // if not custom asset
            $coin['image_large'] = coinImageUrl($coin['slug'], $coin['image_large']); // image url
        }

        // data passed to currency view
        $data                   = new stdClass();
        $data->theme            = $general['layout_theme'];
        $data->price_currency   = $this->price_currency;
        $data->price_rate       = $this->price_rate;
        $data->coin             = $coin;
        $data->change_24h       = changeDetails($coin['price_usd_change_24h']);

        $data->display_content = $currency_page['show_content'] ? _t($coin['page_content']) : false; // custom content

        if(isset($coin['info'])) {
            $info               = $coin['info'];
            $info->genesis_date = $info->genesis_unix ? date($general['date_format'], $info->genesis_unix) : null;
            $coin['name']       = _t($coin['info']->localization, $coin['name']); // coin's name translated

            $data->display_description = $currency_page['show_description'] ? _t($info->description) : false; // original description translated
        }
        else {
            $data->display_description = false; // don't show description
        }

        // Chart values are only available in some cryptocurrencies
        if($this->price_rate->type === 'crypto'){
            if(in_array($this->price_currency, array('bitcoin','ethereum','litecoin','bitcoin-cash','binancecoin','ripple','eos','stellar'))) {
                $chart_currency = $this->price_rate->unit;
                $price_unit = $this->price_rate->unit;
            }
            else {
                $chart_currency = 'USD';
                $price_unit = '$';
            }
        }
        else { // all fiat are available
            $chart_currency = $this->price_currency;
            $price_unit = $this->price_rate->unit;
        }


        // join currency page settings
        joinToData($data, $currency_page);


        $extra_data         = new stdClass();
        $extra_data->title  = "{$coin['name']} ({$coin['symbol']})";

        $extra_data->js = array(
            'https://cdn.jsdelivr.net/npm/echarts@4.9.0/dist/echarts-en.min.js',
            frontendAsset('js', 'currency.min.js?v='.COINTABLE)
        );

        $extra_data->constants = array(
            'coin' => array(
                'slug'          => $coin['slug'],
                'name'          => $coin['name'],
                'symbol'        => $coin['symbol'],
                'tracking'      => isset($coin['tracking_slug']) ? array($coin['tracking_slug'], $coin['tracking_multiple']) : null
            ),
            'chart' => array(
                'currency'      => $chart_currency,
                'price'         => array('label' => t('price') . " ($price_unit)", 'color' => $currency_page['price_color']),
                'volume'        => array('label' => t('volume') . " ($price_unit)", 'color' => $currency_page['volume_color']),
                'market_cap'    => array('label' => t('market_cap') . " ($price_unit)", 'color' => $currency_page['market_cap_color'])
            ),
	        'tickers' => array(
				'enabled'       => ! empty($currency_page['show_tickers']),
		        'size'          => empty($currency_page['tickers_size']) ? 20 : $currency_page['tickers_size'],
	        ),
	        'lang'              => $this->lang_code,
        );

        $this->baseView($this->pageView('currency', $data), $extra_data);
    }

    // --------------------------------------------------------------------

    /**
     * Press Page
     *
     * Show RSS feeds items
     *
     * @param null|int $page
     *
     */

    public function press($page = null)
    {
        // fetch options
        $press_page     = $this->coin_table->settingsGet('press_page');

        // if disable show not found page
        if(!$press_page['enabled']) show_404();

        // data for press view
        $data               = new stdClass();
        $data->pagination   = pagination($this->coin_table->getRSSData(), $page, $press_page['page_size']);
        $data->theme        = $this->coin_table->settingsGet('general','layout_theme');
        $data->redirect     = site_url(CT_PRESS_PAGE);

        // join press page settings
        joinToData($data, $press_page);

        // prepare data for base view
        // join custom SEO params
        $extra_data         = new stdClass();
        $extra_data->title  = _t($press_page['title']); // translated title
        $this->joinCustomSEO($extra_data, $press_page);

        // show press page
        $this->baseView($this->pageView('press', $data), $extra_data);
    }

    // --------------------------------------------------------------------

    /**
     * Mining Page
     *
     * Show mining equipment (provided by CryptoCompare)
     *
     * @param int|null $page
     *
     */

    public function mining($page = null)
    {
        // fetch options
        $mining_page = $this->coin_table->settingsGet('mining_page');

        // if disable show not found page
        if(!$mining_page['enabled']) show_404();


        $results = $this->coin_table->searchMiningEquipment($this->input->get(), $mining_page['page_size'], $page);

        // convert cost to current price currency
        foreach ($results->pagination->items as $equipment) {
            $equipment->price = priceFormat($this->coin_table->fx('USD', $this->price_currency, $equipment->cost), $this->price_rate);
        }


        // data for mining view
        $data           = new stdClass();
        $data->theme    = $this->coin_table->settingsGet('general','layout_theme');
        $data->redirect = site_url(CT_MINING_PAGE);
        $data->results  = $results;

        // join mining page settings
        joinToData($data, $mining_page);

        // prepare data for base view
        // include mining script
        $extra_data         = new stdClass();
        $extra_data->title  = _t($mining_page['title']); // translated title
        $extra_data->js     = frontendAsset('js', 'mining.min.js?v='.COINTABLE);
        $extra_data->constants = array(
            'params' => $results->params
        );

        // join custom SEO params
        $this->joinCustomSEO($extra_data, $mining_page);

        // show mining page
        $this->baseView($this->pageView('mining', $data), $extra_data);
    }

    // --------------------------------------------------------------------

    /**
     * Converter Page
     *
     * Multi currency price conversion tool
     *
     */

    public function converter()
    {
        // fetch options
        $converter_page = $this->coin_table->settingsGet('converter_page');

        // if disabled show not found page
        if(!$converter_page['enabled']) show_404();

        // data for convert view
        $data           = new stdClass();
        $data->rates    = $this->coin_table->getExchangeRates();
        $data->theme    = $this->coin_table->settingsGet('general','layout_theme');

        // join converter page settings
        joinToData($data, $converter_page);

        // prepare data for base view
        // include converter script
        $extra_data         = new stdClass();
        $extra_data->title  = _t($converter_page['title']); // translated title
        $extra_data->js     = frontendAsset('js', 'converter.min.js?v='.COINTABLE);

        // join custom SEO params
        $this->joinCustomSEO($extra_data, $converter_page);

        // show converter page
        $this->baseView($this->pageView('converter', $data), $extra_data);
    }

    // --------------------------------------------------------------------

    /**
     * ICOs Page
     *
     * type could be finished, live or upcoming
     * information is provided by ICO Watch List
     *
     * @param string $type
     * @param int|null $page
     *
     */

    public function icos($type = 'live', $page = null)
    {
        // fetch options
        $icos_page    = $this->coin_table->settingsGet('icos_page');

        // if disabled show not found page
        if(!$icos_page['enabled']) show_404();

        // constraint type to available values
        if($type !== 'live' && $type !== 'upcoming' && $type !== 'finished')
            $type = 'live';

        // data for icos view
        $data               = new stdClass();
        $data->theme        = $this->coin_table->settingsGet('general','layout_theme');
        $data->type         = $type;
        $data->pagination   = pagination($this->coin_table->getICOs($type), $page, 50);
        $data->redirect     = site_url(CT_ICOS_PAGE);

        // join icos page settings
        joinToData($data, $icos_page);

        // prepare data for base view
        $extra_data         = new stdClass();
        $extra_data->title  = _t($icos_page['title']); // translated title
        $extra_data->js     = frontendAsset('js', 'icos.min.jsv='.COINTABLE);

        // join custom SEO params
        $this->joinCustomSEO($extra_data, $icos_page);

        // show icos page
        $this->baseView($this->pageView('icos', $data), $extra_data);
    }

    // --------------------------------------------------------------------

    /**
     * Exchanges Page
     *
     * Exchanges listing by 24h volume
     *
     * @param int|null $page
     *
     */

    public function exchanges($page = null)
    {
        // fetch options
        $exchanges_page = $this->coin_table->settingsGet('exchanges_page');

        // if disabled show not found page
        if(!$exchanges_page['enabled']) show_404();

        $pagination = pagination($this->coin_table->getExchanges(), $page, $exchanges_page['page_size']);

        // convert volume to current price currency
        foreach ($pagination->items as $exchange) {
            $exchange->trade_volume = priceFormat($this->coin_table->fx('bitcoin', $this->price_currency, $exchange->trade_volume_24h_btc), $this->price_rate);
        }


        // data for exchanges view
        $data               = new stdClass();
        $data->pagination   = $pagination;
        $data->currency     = $this->price_currency;
        $data->theme        = $this->coin_table->settingsGet('general','layout_theme');
        $data->redirect     = site_url(CT_EXCHANGES_PAGE);

        // join exchange page settings
        joinToData($data, $exchanges_page);

        // prepare data for base view
        $extra_data         = new stdClass();
        $extra_data->title  = _t($exchanges_page['title']); // translated title

        // join custom SEO params
        $this->joinCustomSEO($extra_data, $exchanges_page);

        // show exchanges page
        $this->baseView($this->pageView('exchanges', $data), $extra_data);
    }

    // --------------------------------------------------------------------

    /**
     * Service Page
     *
     * Bitcoin Accepting services listing
     *
     * @param int|null $page
     *
     */

    public function services($page = null)
    {
        // fetch options
        $services_page  = $this->coin_table->settingsGet('services_page');

        // if disabled show not found page
        if(!$services_page['enabled']) show_404();

        // data for services view
        $data                   = new stdClass();
        $data->pagination       = pagination($this->coin_table->getServices(), $page, $services_page['page_size']);
        $data->price_currency   = $this->price_currency;
        $data->theme            = $this->coin_table->settingsGet('general','layout_theme');
        $data->redirect         = site_url(CT_SERVICES_PAGE);

        // join services page settings
        joinToData($data, $services_page);

        // prepare data for base view
        $extra_data         = new stdClass();
        $extra_data->title  = _t($services_page['title']); // translated title

        // join custom SEO params
        $this->joinCustomSEO($extra_data, $services_page);

        // show services page
        $this->baseView($this->pageView('services', $data), $extra_data);
    }

    // --------------------------------------------------------------------

    /**
     * Trends Page
     *
     * Top price gainers and losers in 24h
     *
     */

    public function trends()
    {
        // fetch options
        $trends_page  = $this->coin_table->settingsGet('trends_page');

        // if disabled show not found page
        if(!$trends_page['enabled']) show_404();

        $trends = $this->coin_table->getCoinTrends($trends_page['top_size']);

        // convert values to current price currency
        // translate coin's name
        // append currency page and image urls

        foreach ($trends->gainers as &$c) {
            $c['price']         = priceFormat($this->coin_table->fx('USD', $this->price_currency, $c['price_usd']), $this->price_rate);
            $c['volume']        = priceFormat($this->coin_table->fx('USD', $this->price_currency, $c['volume_24h_usd']), $this->price_rate);
            $c['change']        = ct_number_format($c['price_usd_change_24h'],2);
            $c['name']          = _t($c['info']->localization, $c['name']);
            $c['url']           = site_url(CT_CURRENCY_PAGE . '/' . $c['slug']);
            $c['image_small']   = coinImageUrl($c['slug'], $c['image_small']);
        }

        foreach ($trends->losers as &$c) {
            $c['price']         = priceFormat($this->coin_table->fx('USD', $this->price_currency, $c['price_usd']), $this->price_rate);
            $c['volume']        = priceFormat($this->coin_table->fx('USD', $this->price_currency, $c['volume_24h_usd']), $this->price_rate);
            $c['change']        = ct_number_format(abs($c['price_usd_change_24h']),2);
            $c['name']          = _t($c['info']->localization, $c['name']);
            $c['url']           = site_url(CT_CURRENCY_PAGE . '/' . $c['slug']);
            $c['image_small']   = coinImageUrl($c['slug'], $c['image_small']);
        }

        // data for trends view

        $data                   = new stdClass();
        $data->price_currency   = $this->price_currency;
        $data->theme            = $this->coin_table->settingsGet('general','layout_theme');
        $data->redirect         = site_url(CT_TRENDS_PAGE);
        $data->gainers          = $trends->gainers;
        $data->losers           = $trends->losers;

        // join trends page settings
        joinToData($data, $trends_page);

        // prepare data for base view
        $extra_data         = new stdClass();
        $extra_data->title  = _t($trends_page['title']); // translated title
        $extra_data->js = array(
            'https://cdn.datatables.net/v/se/dt-1.11.3/fc-4.0.1/fh-3.2.0/datatables.min.js',
            frontendAsset('js', 'trends.min.js?v='.COINTABLE)
        );
        $extra_data->css = array(
            'https://cdn.datatables.net/v/se/dt-1.11.3/fc-4.0.1/fh-3.2.0/datatables.min.css'
        );

        // join custom SEO params
        $this->joinCustomSEO($extra_data, $trends_page);

        // show trends page
        $this->baseView($this->pageView('trends', $data), $extra_data);
    }

    // --------------------------------------------------------------------
    /**
     * Custom Pages
     *
     * @param int|string $request
     *
     */

    public function pages($request)
    {
        // fetch options
        $page   = $this->coin_table->getCustomPage($request);

        // test if exists, show not found on failure
        if(!is_array($page)) show_404();

        // page can be not public
        // but admins can preview the page
        if(!$page['public']) {
            // check URL param 'preview'
            if($this->input->get('preview') !== '1') show_404();

            // load Ion Auth
            $this->loadAuth();

            // if is not an admin logged or preview not provided
            // show not found page
            if(!$this->ion_auth->is_admin()) show_404();
        }

        // data for custom page view
        $data           = new stdClass();
        $data->theme    = $this->coin_table->settingsGet('general','layout_theme');

        // join custom page params
        joinToData($data, $page);

        // prepare data for base view
        $extra_data         = new stdClass();
        $extra_data->title  = _t($page['title']); // translated title

        // join custom SEO params
        $this->joinCustomSEO($extra_data, $page);

        // show custom page
        $this->baseView($this->pageView('custom_page', $data), $extra_data);
    }

    // --------------------------------------------------------------------

    /**
     * Go
     *
     * Used to redirect on menu link item
     * This helps to hide the URL
     *
     * source can be header (top & sidebar) plus footer_1, footer_2 or footer_3 (footer menus)
     *
     * @param string $src
     * @param int $index
     *
     */

    public function go($src, $index)
    {
        $destination = null;

        // header
        if($src === 'header'){
            // fetch header_menu option
            $menu = $this->coin_table->settingsGet('header_menu', 'menu');

            // test existence and typology of the item
            if(isset($menu[$index]) && $menu[$index]['type'] === 'link') {
                $destination = $menu[$index]['data'];
            }
        }

        // footer
        else if($src === 'footer_1' || $src === 'footer_2' || $src === 'footer_3'){
            // fetch footer option
            $footer = $this->coin_table->settingsGet('footer');

            // get requested menu
            $n      = str_replace('footer_','', $src);
            $menu   = $footer["menu$n"];

            // test existence and typology of the item
            if(isset($menu[$index]) && $menu[$index]['type'] === 'link') {
                $destination = $menu[$index]['data'];
            }
        }

        if(empty($destination)){
            redirect('/', 'refresh'); // return to front page if something wrong
        }
        else {
            redirect($destination, 'refresh'); // redirects to destination URL
        }
    }

    // --------------------------------------------------------------------

    /**
     * Robots.txt
     *
     */

    public function robots()
    {
        header('Content-type: text/plain');

        $url = site_url('sitemap.xml');

        echo "User-agent: *\nSitemap: $url";
    }

    // --------------------------------------------------------------------

    /**
     * URL entry for sitemap list
     *
     * @param $url
     * @param $date
     * @param string $freq
     * @param float $priority
     *
     * @return string
     */

    private function URLEntry($url, $date, $freq = 'daily', $priority = 0.5)
    {
        $priority = ct_number_format($priority, 1);
        return "<url><loc>$url</loc><lastmod>$date</lastmod><changefreq>$freq</changefreq><priority>$priority</priority></url>";
    }

    // --------------------------------------------------------------------

    /**
     * Sitemap.xml
     *
     */

    public function sitemap()
    {
        header('Content-type: text/xml');

        $date = date("Y-m-d");

        $entries = '';

        $built_in_pages = array(
            'market_page'       => CT_MARKET_PAGE,
            'press_page'        => CT_PRESS_PAGE,
            'mining_page'       => CT_MINING_PAGE,
            'converter_page'    => CT_CONVERTER_PAGE,
            'icos_page'         => CT_ICOS_PAGE,
            'exchanges_page'    => CT_EXCHANGES_PAGE,
            'services_page'     => CT_SERVICES_PAGE,
            'trends_page'       => CT_TRENDS_PAGE
        );

        // Built-In Pages
        foreach ($this->coin_table->getBuiltInPages() as $page) {




            $entries .= $this->URLEntry(site_url($built_in_pages[$page['id']]),$date,'always', 0.9);
        }

        // Custom Pages
        foreach ($this->coin_table->getCustomPages(1) as $page){
            $uri = empty($page['path']) ? $page['id'] : $page['path'];

            $entries .= $this->URLEntry(site_url($uri),$date,'daily', 0.7);
        }

        // CryptoCurrencies pages
        $list = $this->coins_model->listing();
        foreach ($list as $info){
            $entries .= $this->URLEntry(site_url(array('currency',$info['slug'])), $date,'always');
        }

        echo '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.$entries.'</urlset>';
    }

}
