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
 * Class Coin_table
 *
 * @package		CoinTable
 * @subpackage	Libraries
 * @author		RunCoders
 */


class Coin_table
{
    /**
     * timezones offsets for ICO Watch List API
     * somehow it sends strange negative offsets
     */

    const ICO_OFFSETS = array(
        'UTC+0'   => '+0000',
        'UTC+1'   => '+0100',
        'UTC+2'   => '+0200',
        'UTC+3'   => '+0300',
        'UTC+4'   => '+0400',
        'UTC+5'   => '+0500',
        'UTC+6'   => '+0600',
        'UTC+7'   => '+0700',
        'UTC+8'   => '+0800',
        'UTC+9'   => '+0900',
        'UTC+10'  => '+1000',
        'UTC+11'  => '+1100',
        'UTC--1'  => '-0100',
        'UTC--2'  => '-0200',
        'UTC--3'  => '-0300',
        'UTC--4'  => '-0400',
        'UTC--5'  => '-0500',
        'UTC--6'  => '-0600',
        'UTC--7'  => '-0700',
        'UTC--8'  => '-0800',
        'UTC--9'  => '-0900',
        'UTC--10' => '-1000',
        'UTC--11' => '-1100',
    );

    // --------------------------------------------------------------------

    /**
     * CoinGecko Coin links paths
     */

    const COINGECKO_LINK_PATHS = array(
        'blockchain_site'       => 'blockchain_explorers',
        'announcement_url'      => 'announcement',
        'chat_url'              => 'chats',
        'homepage'              => 'websites',
        'official_forum_url'    => 'forums'
    );

    // --------------------------------------------------------------------

    /**
     * Maximum execution seconds during synchronisation
     */

    const MAX_SECS_PASSED = 55;

    // --------------------------------------------------------------------

    /**
     * Currency code alias for price conversion
     */

    const FX_CODE_ALIAS = array(
        'BTC' => 'bitcoin',
        'ETH' => 'ethereum',
        'XRP' => 'ripple',
        'BCH' => 'bitcoin-cash',
        'XLM' => 'stellar',
        'LTC' => 'stellar',
        'BNB' => 'binancecoin'
    );

    // --------------------------------------------------------------------

    /**
     * Execution start time seconds (UNIX)
     * @var int
     */
    private $_start_time;

    // --------------------------------------------------------------------

    /**
     * Visitor's selected language code
     *
     * @var string
     */

    public $visitor_lang;

    // --------------------------------------------------------------------

    /**
     * Default language code
     *
     * @var string
     */

    public $default_lang;

    // --------------------------------------------------------------------

    /**
     * Coin_table constructor.
     *
     * sets start time
     */
    public function __construct()
    {
        $this->_start_time = time();
    }

    // --------------------------------------------------------------------

    /**
     * Access to CodeIgniter properties
     *
     * @param $var
     *
     * @return mixed
     */
    public function __get($var)
    {
        $CI =& get_instance();
        return $CI->$var;
    }

    // --------------------------------------------------------------------

    /**
     * Sets PHP timezone & default language
     *
     */

    public function setTimeZone()
    {
        date_default_timezone_set($this->settingsGet('general', 'timezone'));
    }

    // --------------------------------------------------------------------

    /**
     * Sets user's default language
     *
     */

    public function setDefaultLang()
    {
        $this->default_lang = $this->settingsGet('general','language');
    }

    // --------------------------------------------------------------------

    /**
     * Seconds of current script execution
     *
     * @return int
     */

    public function executionTime()
    {
        return time() - $this->_start_time;
    }

    // --------------------------------------------------------------------

    /**
     * User's selected datetime format
     *
     * @return string
     */

    public function dateTimeFormat()
    {
        $general = $this->settingsGet('general');
        return $general['date_format'].' '.$general['time_format']; // date & time display format
    }

    // --------------------------------------------------------------------

    /**
     * Get data from options model
     * can return just one property instead of full option
     *
     * @param string $name
     * @param string|null $prop
     * @param bool $cache
     *
     * @return mixed
     */

    public function getOption($name, $prop = null, $cache = true)
    {
        $content = null;
        $this->options_model->readOption($name, $content, $cache); // read option to content

        if($prop !== null && is_array($content) && array_key_exists($prop, $content)) { // if data is array and only property needed
            return $content[$prop]; // return property value
        }

        return $content; // return option fully
    }

    // --------------------------------------------------------------------

    /**
     * Saves data as option object model
     *
     * @param string $name
     * @param mixed $content
     * @param bool $cache
     *
     * @return bool
     */

    public function saveOption($name, $content, $cache = true)
    {

        $default = $this->config->item($name, 'option_defaults'); // get default for option

        if(isset($default)) {
            if(isset($default[0]) && is_callable($default[0])) { // simple property option
                if(!call_user_func($default[0], $content)) { // call test function
                    $content = $default[1]; // on failure, set to default
                }
            }
            else { // multiple property option
                if(!is_array($content)) // force content to be an array
                    $content = array();

                // for each property
                foreach ($default as $key => $args){
                    if(!isset($content[$key]) || !call_user_func($args[0],$content[$key])){ // call test function
                        $content[$key] = $args[1]; // on failure, set to default
                    }
                }
            }
        }

        return $this->options_model->saveOption($name, $content, $cache); // return result of saving
    }

    // --------------------------------------------------------------------

    /**
     * Remove option
     *
     * @param string $name
     *
     * @return mixed
     */
    public function removeOption($name)
    {
        return $this->options_model->dropOption($name); // return result of removing
    }

    // --------------------------------------------------------------------

    /**
     * Gets settings option
     *
     * @param $name
     * @param null|string $prop
     * @param bool $cache
     *
     * @return mixed
     */

    public function settingsGet($name, $prop = null, $cache = true)
    {
        return $this->getOption("{$name}_settings", $prop, $cache);
    }

    // --------------------------------------------------------------------

    /**
     * Sets settings option
     *
     * @param $name
     * @param $content
     * @param bool $cache
     *
     * @return bool
     */

    public function settingsSave($name, $content, $cache = true)
    {
        return $this->saveOption("{$name}_settings", $content, $cache);
    }

    // --------------------------------------------------------------------

    /**
     * Dataset timeout checker (used in sync functions)
     *
     * @param string $name
     * @param int $timeout
     *
     * @return bool
     */

    private function datasetTimeout($name, $timeout = 60)
    {
        $last_update = $this->getOption("dataset_last_update_$name", null, false);
        return time() > $last_update + $timeout;
    }

    // --------------------------------------------------------------------

    /**
     * Saves datasets' data & update time
     *
     * @param string $name
     * @param mixed $data
     *

     */

    private function datasetSave($name, $data)
    {
        if($data !== null) $this->saveOption("dataset_data_$name", $data, false);

        $this->saveOption("dataset_last_update_$name", time(), false);
    }

    // --------------------------------------------------------------------

    /**
     * Get a dataset
     *
     * @param string $name
     * @param mixed $default
     *
     * @return mixed
     */

    private function datasetGet($name, $default = null)
    {
        $value = $this->getOption("dataset_data_$name", null, false);

        return isset($value) ? $value : $default;
    }

    // --------------------------------------------------------------------

    /**
     * BTCInHere services list request
     *
     * @return mixed
     */

    public function requestBTCInHereServices()
    {
        return requestJSON('https://btcinhere.com/json/services.json', null, null, false);
    }

    // --------------------------------------------------------------------

    /**
     * CryptoCompare mining equipment request
     *
     * @return mixed
     */

    public function requestCCMiningEquipments()
    {
        return requestJSON('https://www.cryptocompare.com/api/data/miningequipment',null,null,false);
    }

    // --------------------------------------------------------------------

    /**
     * CoinGecko exchange list request
     * @param int $page
     * @param int $per_page
     *
     * @return mixed
     */

    public function requestCoinGeckoExchanges($page = 1, $per_page = 100)
    {
        $params = [
            'page' => $page,
            'per_page' => $per_page,
        ];
        return requestJSON('https://api.coingecko.com/api/v3/exchanges',$params,null,false);
    }

    // --------------------------------------------------------------------

    /**
     * CoinGecko coins (id,name,symbol) listing
     *
     * @param null|array $params
     *
     * @return mixed
     */

    public function requestCoinGeckoCoinsList($params = null)
    {
        return requestJSON("https://api.coingecko.com/api/v3/coins/list", $params);
    }

    // --------------------------------------------------------------------

    /**
     * CoinGecko coin full info
     *
     * @param string $id
     * @param null|array $params
     *
     * @return mixed
     */

    public function requestCoinGeckoCoin($id, $params = null)
    {
        return requestJSON("https://api.coingecko.com/api/v3/coins/$id", $params, null, false);
    }

    // --------------------------------------------------------------------

    /**
     * CoinGecko coins market info listing
     *
     * @param string $vs_currency
     * @param null|array $extra_params
     *
     * @return mixed
     */

    public function requestCoinGeckoCoinsMarket($vs_currency = 'usd', $extra_params = null)
    {
        $params = array('vs_currency' => $vs_currency);

        if(is_array($extra_params)) $params = array_merge($params, $extra_params);


        return requestJSON('https://api.coingecko.com/api/v3/coins/markets', $params);
    }

    // --------------------------------------------------------------------

    /**
     * CoinGecko exchange rates (BTC base)
     *
     * @param null|array $params
     *
     * @return mixed
     */

    public function requestCoinGeckoExchangeRates($params = null)
    {
        return requestJSON('https://api.coingecko.com/api/v3/exchange_rates', $params, null, null);
    }

    // --------------------------------------------------------------------

    /**
     * CoinGecko market chart data
     *
     * @param null|array $params
     *
     * @return mixed
     */

    public function requestCoinGeckoExchangeMarketChart($slug, $vs_currency, $days)
    {
        $vs_currency = strtolower($vs_currency);
        return requestJSON("https://api.coingecko.com/api/v3/coins/$slug/market_chart", array('vs_currency' => $vs_currency, 'days' => $days), null, false);
    }

	// --------------------------------------------------------------------

	/**
	 * CoinGecko cryptocurrency tickers data
	 *
	 * @param string $slug
	 * @param int $page
	 *
	 * @return mixed|null
	 */
	public function requestCoinGeckoCoinTickers($slug, $page)
	{
		return requestJSON("https://api.coingecko.com/api/v3/coins/$slug/tickers", array('page' => $page, 'order' => 'volume_desc', 'include_exchange_logo' => 'true'), null, false);
	}

    // --------------------------------------------------------------------

    /**
     * ICO Watch List all request
     *
     * @return mixed
     */

    public function requestICOWatchList()
    {
        return requestJSON('https://api.icowatchlist.com/public/v1/');
    }

    // --------------------------------------------------------------------

    /**
     * Fetches and saves cryptocurrency information
     *
     * @param $slug
     * @param bool $create
     *
     * @return bool
     */

    public function syncCoinFullInfo($slug, $create = true)
    {
        if(empty($slug) || !is_object($data = $this->requestCoinGeckoCoin($slug)) || empty($data->symbol)) return false;

        $genesis_date           = objProp($data,'genesis_date');
        $genesis_unix           = $genesis_date ? strtotime($genesis_date) : null;

        $info                   = new stdClass();
        $info->genesis_unix     = $genesis_unix;
        $info->localization     = objProp($data,'localization');
        $info->description      = objProp($data,'description');

        if ( ! empty( $info->description ) ) {
            foreach ($info->description as $lang => $description) {
                $info->description->$lang = strip_tags($description);
            }
        }

        // Links

        $links = $info->links = new stdClass();

        foreach (self::COINGECKO_LINK_PATHS as $key => $type) {
            $links->$type = array();

            foreach (objProp($data->links, $key, array()) as $entry) {
                if(!empty($entry) && ($domain = parse_url($entry, PHP_URL_HOST)) !== false) {
                    $domain = str_replace('www.','', $domain);
                    $links->$type[$domain] = $entry;
                }
            }
        }

        $links->bitcointalk = empty($data->links->bitcointalk_thread_identifier) ?
            null : 'https://bitcointalk.org/index.php?topic='. $data->links->bitcointalk_thread_identifier;

        $links->facebook = empty($data->links->facebook_username) ?
            null : 'https://www.facebook.com/'. $data->links->facebook_username;

        $links->twitter = empty($data->links->twitter_screen_name) ?
            null : 'https://twitter.com/'. $data->links->twitter_screen_name;

        $links->reddit = empty($data->links->subreddit_url) ?
            null : 'https://twitter.com/'. $data->links->subreddit_url;


        // download all images to coins_images/{slug} folder
        $multi_curl = new \Curl\MultiCurl();

        $images = new stdClass();
        $images->thumb = null;
        $images->small = null;
        $images->large = null;

        $coin_images_dir = COINS_IMAGES_PATH . $data->id . DIRECTORY_SEPARATOR;

        @mkdir($coin_images_dir, 0755);

        foreach ($data->image as $key => $image_url) {
            $image_path     = parse_url($image_url, PHP_URL_PATH);
            $image_ext      = pathinfo($image_path, PATHINFO_EXTENSION);
            $images->$key   = "$key.$image_ext";

            $multi_curl->addDownload($image_url,$coin_images_dir . $images->$key);
        }

        $multi_curl->start();


        // data for insert/update
        $insert_data = array(
            'symbol'                => strtoupper($data->symbol),
            'name'                  => $data->name,
            'circulating_supply'    => floatval($data->market_data->circulating_supply),
            'total_supply'          => floatval($data->market_data->total_supply),
            'info'                  => $info,
            'image_thumb'           => $images->thumb,
            'image_small'           => $images->small,
            'image_large'           => $images->large,
            'info_updated'          => time()
        );

        if($create) {
            $dummy = null;

            $insert_data['slug'] = $data->id;
            return $this->coins_model->create($insert_data, $dummy, false) ?
                $insert_data['info_updated'] : false;
        }
        else {
            return $this->coins_model->updateBySlug($slug, $insert_data) ?
                $insert_data['info_updated'] : false;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Inserts new cryptocurrencies
     *
     * @param int $max_insertions
     *

     */

    private function syncCoinsList()
    {
        if(!is_array($list = $this->requestCoinGeckoCoinsList())) return;

        $slugs = array_map(function ($coin) { return strtolower($coin['id']); }, $list);

        // remove existing coins and try to insert as many as possible
        foreach (array_diff($slugs, $this->coins_model->allSlugs()) as $slug) {
            $this->syncCoinFullInfo($slug);
            if($this->executionTime() > self::MAX_SECS_PASSED) exit; // exit and wait for the next cron job
	        else sleep(1);
        }

    }

    // --------------------------------------------------------------------

	/**
	 * Updates cryptocurrencies prices & exchange rates
	 *
	 * @param bool $update_rates
	 * @param bool $top
	 */

    public function syncMarketData($update_rates = true, $top = false)
    {
	    $coingecko_ids = '';
	    $slugs = [];

		if ($top) {
			if (empty($top = $this->coins_model->top(200, 'slug')))
				return;
			$_slugs = array_column($top, 'slug');
		} elseif (empty($_slugs = $this->coins_model->earlierUpdatedSlugs(250))) {
			return;
		}

	    foreach ($_slugs as $slug) {
		    $len = strlen($coingecko_ids) + strlen($slug);
		    if ($len < 1900) {
			    $coingecko_ids .= (strlen($coingecko_ids) ? ',' : '') . $slug;
			    $slugs[] = $slug;
		    } else {
			    break;
		    }
	    }

        if(empty($coingecko_ids) || empty($data = $this->requestCoinGeckoCoinsMarket('usd', array('per_page' => 250, 'ids' => $coingecko_ids, 'sparkline' => 'true'))))
            return;

        foreach ($data as $coin_data) {

            // CoinGecko sparklines are inconsistent
            // they send between 180 and 130 entries ???
            $chart_7d = empty($coin_data['sparkline_in_7d']['price']) ?
                null : $coin_data['sparkline_in_7d']['price'];

            $slug = $coin_data['id'];

            $this->coins_model->updateBySlug($slug, array(
                'price_usd'             => floatval($coin_data['current_price']),
                'market_cap_usd'        => floatval($coin_data['market_cap']),
                'price_usd_change_24h'  => ct_number_format(floatval($coin_data['price_change_percentage_24h']), 2),
                'volume_24h_usd'        => floatval($coin_data['total_volume']),
                'circulating_supply'    => floatval($coin_data['circulating_supply']),
                'chart_7d'              => $chart_7d,
                'prices_updated'        => time(),
                'status'                => 1
            ));

            unset($slugs[array_search($slug, $slugs)]); // remove the coin from slugs list
        }


        if(count($slugs)) { // if coins were not sent back, probably they were removed by CoinGecko
            foreach ($slugs as $slug) {
                $this->coins_model->removeBySlug($slug);
            }
        }


        if($update_rates && is_object($cg_rates = $this->requestCoinGeckoExchangeRates())) { // coingecko exchanges rates (only keeping fiat)
            $exchange_rates                 = $this->getCoinsPriceUSD() ?: new stdClass(); // get all coins prices
            $cg_rates->rates->btc->value    = 1;
            $btcusd_rate                    = $cg_rates->rates->usd->value;

            foreach ($cg_rates->rates as $code => $rate) {
                $code = strtoupper($code);

                if($rate->type === 'fiat') {
                    $rate->value           = ct_number_format($rate->value / $btcusd_rate, 8, '.', ''); // only 8 decimal places
                    $exchange_rates->$code = $rate;
                }
            }

            $this->saveOption('exchange_rates', $exchange_rates,false); // save exchange rates object
        }
    }

    // --------------------------------------------------------------------

    /**
     * Mining equipment synchronisation
     *
     */

    private function syncMiningEquipment()
    {
        if(!$this->datasetTimeout('mining_equipments', 12*60*60)
            || !$this->settingsGet('mining_page', 'enabled')
            || !is_object($data = $this->requestCCMiningEquipments())
            || empty($data->MiningData))
            return;


        $mining_data                    = new stdClass();
        $mining_data->equipments        = array();
        $mining_data->sources           = array();
        $mining_data->cryptocurrencies  = array();
        $mining_data->types             = array();


        $cc_url = 'https://www.cryptocompare.com';

        // parse each item information
        foreach ($data->MiningData as $item) {

            $equipment                  = new stdClass();
            $equipment->name            = $item->Name;
            $equipment->source_code     = str_replace(' ', '-', strtolower($item->Company));
            $equipment->algorithm       = $item->Algorithm;
            $equipment->type_code       = str_replace(' ', '-', strtolower($item->EquipmentType));
            $equipment->hashrate        = empty($item->HashesPerSecond) ? 0 : floatval($item->HashesPerSecond);
            $equipment->hash_format     = hashRateFormat($equipment->hashrate);
            $equipment->power           = intval($item->PowerConsumption);
            $equipment->cost            = $this->fx($item->Currency, 'USD', empty($item->Cost) ? 0 : floatval($item->Cost));
            $equipment->mined_code      = $item->CurrenciesAvailable;
            $equipment->mined_name      = $item->CurrenciesAvailableName;
            $equipment->image           = $cc_url . $item->LogoUrl;
            $equipment->url             = $cc_url . $item->Url;

            $mining_data->equipments[] = $equipment;


            if(!array_key_exists($equipment->source_code, $mining_data->sources)) { // collect all company sources
                $mining_data->sources[$equipment->source_code] = $item->Company;
            }

            if(!array_key_exists($equipment->mined_code, $mining_data->cryptocurrencies)) {  // collect all cryptocurrencies
                $mining_data->cryptocurrencies[$equipment->mined_code] = array(
                    $equipment->mined_name, $cc_url . $item->CurrenciesAvailableLogo
                );
            }

            if(!array_key_exists($equipment->type_code, $mining_data->types)) { // collect all equipment types
                $mining_data->types[$equipment->type_code] = $item->EquipmentType;
            }
        }

        ksort($mining_data->sources);
        ksort($mining_data->cryptocurrencies);
        ksort($mining_data->types);

        $this->datasetSave('mining_equipments', $mining_data);
    }

    // --------------------------------------------------------------------

    /**
     * RSS feeds synchronisation
     *
     */

    private function syncRSS()
    {
        if(!$this->datasetTimeout('rss_items', 5*60))
            return;

        $press_page = $this->settingsGet('press_page');

        if(!$press_page['enabled']) return;

        $general    = $this->settingsGet('general');
        $dt_format  = $general['date_format'].' '.$general['time_format']; // date & time display format
        $items      = array();

        $this->load->library('rss'); // load SimplePie
        // fetch all feeds (check SimplePie documentation)
        $feed = new SimplePie();
        $feed->set_feed_url($press_page['feeds']);
        $feed->enable_cache( false );
        $feed->init();
        $feed->handle_content_type();

        foreach ($feed->get_items() as $item) { // grab items details
            $rss_item           = new stdClass();
            $rss_item->link     = $item->get_permalink();
            $rss_item->title    = $item->get_title();
            $rss_item->content  = $item->get_content();
            $rss_item->date     = $item->get_date($dt_format);

            $items[] = $rss_item;
        }

        if(empty($items)) $items = null;

        $this->datasetSave('rss_items', $items);
    }

    // --------------------------------------------------------------------

    /**
     * Parses all ICO Watch List entries
     *
     * @return array
     */

    private function mapICOsList($list, $type)
    {
        $general    = $this->settingsGet('general');
        $dt_format  = $general['date_format'].' '.$general['time_format']; // date & time display format

        return array_map(function ($entry) use ($dt_format, $type) {

            $timezone   = $entry['timezone'];
            $offset     = isset(self::ICO_OFFSETS[$timezone]) ? self::ICO_OFFSETS[$timezone] : '+0000';
            $start_time = strtotime($entry['start_time'].' '.$offset); // get start time
            $end_time   = strtotime($entry['end_time'].' '.$offset); // get end time
            $time_now   = time();
            $timeline   = 0;
            $days       = null;
            $hours      = null;

            if($type === 'live') {
                $timeline = round(($time_now - $start_time) * 100 / ($end_time - $start_time)); // percentage of timeline

                if($timeline < 0) $timeline = 0;
                if($timeline > 100) $timeline = 100;
            }

            if($type === 'upcoming') {
                $diff = date_diff(new DateTime("@$time_now"), new DateTime("@$start_time"));
                $days = $diff->days;
                $hours = $diff->h;
            }

            $ico                = new stdClass();
            $ico->name          = $entry['name'];
            $ico->description   = $entry['description'];
            $ico->image         = $entry['image'];
            $ico->website       = $entry['website_link'];
            $ico->start_date    = date($dt_format, $start_time);
            $ico->start_time    = $start_time;
            $ico->end_date      = date($dt_format, $end_time);
            $ico->end_time      = $end_time;
            $ico->timeline      = $timeline;
            $ico->missing_days  = $days;
            $ico->missing_hours = $hours;

            return $ico;

        }, $list);
    }

    // --------------------------------------------------------------------

    /**
     * ICO Watch List synchronisation
     *
     */

    private function syncICOs()
    {
        if(!$this->datasetTimeout('icos_lists', 30*60) // every 30 min
            || !$this->settingsGet('icos_page','enabled')
            || !is_array($data = $this->requestICOWatchList())
            || empty($data['ico']))
            return;

        $lists              = new stdClass();
        $lists->live        = $this->mapICOsList($data['ico']['live'], 'live');
        $lists->upcoming    = $this->mapICOsList($data['ico']['upcoming'], 'upcoming');
        $lists->finished    = array_reverse($this->mapICOsList($data['ico']['finished'], 'finished'));

        $this->datasetSave('icos_lists', $lists);
    }

    // --------------------------------------------------------------------

    /**
     * CoinGecko exchanges synchronisation
     *
     */

    private function syncExchanges()
    {
        if(!$this->datasetTimeout('exchanges_list', 30*60) // every 30 min
            || !$this->settingsGet('exchanges_page','enabled'))
            return;

        $exchanges = [];

        for ( $page = 1; $page <= 2; $page++ ) {
            $data = $this->requestCoinGeckoExchanges($page, 250);
            if (empty($data)) return;

            foreach ($data as $exchange) {
                if ( empty( $exchanges[$exchange->id] ) ) {
					if (empty($exchange->trade_volume_24h_btc)) {
						$exchange->trade_volume_24h_btc = 0;
					} else {
						$exchange->trade_volume_24h_btc = round((float) $exchange->trade_volume_24h_btc, 2);
					}

	                if (empty($exchange->trade_volume_24h_btc_normalized)) {
		                $exchange->trade_volume_24h_btc_normalized = 0;
	                } else {
		                $exchange->trade_volume_24h_btc_normalized = round((float) $exchange->trade_volume_24h_btc_normalized, 2);
	                }

                    $exchange->description = empty( $ex->description ) ? '' : strip_tags( $ex->description );

                    $exchanges[$exchange->id] = $exchange;
                }
            }
        }

        $exchanges = array_values($exchanges);

        $this->datasetSave('exchanges_list', $exchanges);
    }

    // --------------------------------------------------------------------

    /**
     * BTCInHere services synchronisation
     *
     */

    private function syncServices()
    {
        if(!$this->datasetTimeout('services_list', 12*60*60) // every 12h
            || !$this->settingsGet('services_page','enabled')
            || empty($data = $this->requestBTCInHereServices()))
            return;

        // save data and sync info
        $this->datasetSave('services_list', $data);
    }

	// --------------------------------------------------------------------

	/**
	 * Frees unused space and improves lookup speed after many operations on tables
	 */
	private function optimizeTables() {
		$last_time = $this->getOption('optimize_tables_last_time', null, false);
		if (!$last_time || $last_time < (time() - 24*60*60)) {
			$this->coins_model->optimize();
			$this->options_model->optimize();

			$this->saveOption('optimize_tables_last_time', time(), false);
		}
	}

    // --------------------------------------------------------------------

    /**
     * Synchronization calls
     *
     */

    public function sync()
    {
	    $this->syncExchanges();

        if($this->executionTime() < self::MAX_SECS_PASSED) $this->syncMiningEquipment();
        else return;

        if($this->executionTime() < self::MAX_SECS_PASSED) $this->syncServices();
        else return;

	    if($this->executionTime() < self::MAX_SECS_PASSED) {
			// update top coins (as many as possible in one request)
		    $this->syncMarketData(false, true);
			// plus one round of earlier updated coins
		    $this->syncMarketData();
	    } else return;

	    if($this->executionTime() < self::MAX_SECS_PASSED) {
		    // create missing coins
		    $this->syncCoinsList();
	    } else return;

        if($this->executionTime() < self::MAX_SECS_PASSED) {
	        // 4x update earlier updated coins
			for ($i = 0; $i < 3; $i++) {
				$this->syncMarketData(false);
				sleep(1);
			}
	        $this->syncMarketData();
        } else return;

        if($this->executionTime() < self::MAX_SECS_PASSED) $this->syncRSS();
        else return;

		$this->deleteExpiredDatasets();

		$this->optimizeTables();
    }

    // --------------------------------------------------------------------

    /**
     * Get all custom assets
     *
     * @return array
     */

    public function getCustomAssets()
    {
        $slugs = null;

        if($this->custom_assets_model->readAll($slugs, null, 'slug')) {
            $assets = array();

            foreach ($slugs as $slug) {
                if(is_array($asset = $this->getCustomAsset($slug['slug'])))
                    $assets[] = $asset;
            }

            return $assets;
        }

        return array(); // always an array
    }

    // --------------------------------------------------------------------

    /**
     * Get custom asset information
     *
     * @param string $slug
     *
     * @return null
     */

    public function getCustomAsset($slug)
    {
        $asset = null;

        if($this->custom_assets_model->readBy('slug', $slug, $asset)) {
            $tracking_coin = $this->getCoin($asset['tracking_slug']); // tracking coin for market data reference

            if(is_array($tracking_coin)) {
                $asset['price_usd']             = $tracking_coin['price_usd'] * $asset['tracking_multiple'];
                $asset['price_usd_change_24h']  = $tracking_coin['price_usd_change_24h'];
                $asset['market_cap_usd']        = $asset['circulating_supply'] * $asset['price_usd'];
                $asset['chart_7d']              = $tracking_coin['chart_7d'];

                return $asset;
            }
        }

        return null; // not valid
    }

    // --------------------------------------------------------------------

    /**
     * Get cryptocurrency by slug
     *
     * @param string $slug
     * @param int|null $status
     *
     * @return array|null
     */

    public function getCoin($slug, $status = null)
    {
        $coin = null;
        $where = is_numeric($status) ? array('status' => $status) : null;

        return $this->coins_model->readBy('slug', $slug, $coin, $where) ?
            $coin : null;
    }

    // --------------------------------------------------------------------

    /**
     * Returns cryptocurrencies usd prices for exchange rates merge
     *
     * @return null|stdClass
     */

    public function getCoinsPriceUSD()
    {
        $rates = null;

        // only active and with price defined coins
        // prices with 8 decimal places
        if($this->coins_model->readAll($rates, array('status' => 1, 'price_usd >' => 0), 'name, symbol as unit, slug, TRUNCATE(1/price_usd, 8) AS value')) {
            $rates_obj = new stdClass();

            foreach ($rates as $rate) {
                $rate = (object) $rate;
                $rate->type = 'crypto';
                $rates_obj->{$rate->slug} = $rate;
                unset($rate->slug);
            }

            return $rates_obj;
        }
        else return null;
    }

    // --------------------------------------------------------------------

    /**
     * Searching cryptocurrencies with pagination screen
     *
     * @param null|array $search_params
     * @param int $page_size
     * @param int $page
     *
     * @return stdClass
     *
     * @since 5.2.0 Chart data was removed
     */

    public function searchCoins($search_params = null, $page_size = 100, $page = 1)
    {
        if(!is_array($search_params)) $search_params = array();

        // parse params
        $params                 = new stdClass();
        $params->order          = empty($search_params['order']) ? 'market_cap' : $search_params['order'] ;
        $params->desc           = array_key_exists('desc', $search_params) ? !empty($search_params['desc']) : true;

        $params->c              = array_key_exists('c', $search_params) ? explode(',', $search_params['c']) : null;
        $params->mcf            = array_key_exists('mcf', $search_params) ? floatval($search_params['mcf']) : null;
        $params->mct            = array_key_exists('mct', $search_params) ? floatval($search_params['mct']) : null;
        $params->pf             = array_key_exists('pf', $search_params) ? floatval($search_params['pf']) : null;
        $params->pt             = array_key_exists('pt', $search_params) ? floatval($search_params['pt']) : null;
        $params->vf             = array_key_exists('vf', $search_params) ? floatval($search_params['vf']) : null;
        $params->vt             = array_key_exists('vt', $search_params) ? floatval($search_params['vt']) : null;

        $page                   = abs(intval($page)) ?: 1;
        $limit                  = intval($page_size);
        $offset                 = ($page - 1) * $limit;
        $search                 = $this->coins_model->search($params, $offset, $limit);

        $results                = new stdClass();
        $results->total         = $search->total;
        $results->extremes      = $search->extremes;
        $results->pagination    = pagination($search->total, $page, $limit); // pagination screen
        $results->params        = $params;
        //$results->charts        = new stdClass();

        // join custom assets on top
        $custom_assets = $this->getCustomAssets();

        if(!empty($custom_assets)) {
            $search->coins = array_merge($custom_assets, $search->coins);
        }

        $results->pagination->items = $search->coins;

        return $results;
    }

    // --------------------------------------------------------------------

    /**
     * Returns top price gainers & losers in 24h
     *
     * @param int $size
     *
     * @return stdClass
     */

    public function getCoinTrends($size)
    {
        $trends             = new stdClass();
        $trends->gainers    = $this->coins_model->gainers($size);
        $trends->losers     = $this->coins_model->losers($size);

        return $trends;
    }

    // --------------------------------------------------------------------

    /**
     * Returns exchange rates, use code for specific currency
     *
     * @param null|string $code
     *
     * @return null|stdClass
     */

    public function getExchangeRates($code = null)
    {
        $rates = $this->getOption('exchange_rates');

        if($code) {
            if(array_key_exists($code,self::FX_CODE_ALIAS)) // could use alias 'BTC' == 'bitcoin'
                $code = self::FX_CODE_ALIAS[$code];

            return objProp($rates, $code);
        }

        return $rates;
    }

    // --------------------------------------------------------------------

    /**
     * Returns global stats
     *
     * @return stdClass
     */

    public function getGlobalStats()
    {
        $stats = $this->coins_model->stats();
        $stats->total_exchanges = count($this->getExchanges());

        return $stats;
    }

    // --------------------------------------------------------------------

    /**
     * Returns RSS feeds items
     *
     * @return array|null
     */

    public function getRSSData()
    {
        return $this->datasetGet('rss_items');
    }

    // --------------------------------------------------------------------

    /**
     * Returns mining equipments data
     *
     * @return stdClass
     */

    public function getMiningEquipmentData()
    {
        $mining_data = $this->datasetGet('mining_equipments');

        if(!$mining_data) {
            $mining_data                    = new stdClass();
            $mining_data->equipments        = array();
            $mining_data->sources           = array();
            $mining_data->cryptocurrencies  = array();
            $mining_data->types             = array();
        }

        return $mining_data;
    }

    // --------------------------------------------------------------------

    /**
     * Searching mining equipments with pagination screen
     *
     * @param object|null $params
     * @param int $page_size
     * @param int $page
     *
     * @return stdClass
     */

    public function searchMiningEquipment($search_params = null, $page_size = 25, $page = 1)
    {
        $mining_data = $this->getMiningEquipmentData();

        if(!is_array($search_params)) $search_params = array();

        // parse params

        $param_names = array('cryptocurrencies','types','sources');

        $params         = new stdClass();
        $params->order  = empty($search_params['order']) ? 'relevance' : $search_params['order'] ;
        $params->desc   = !empty($search_params['desc']);

        $params_defined = false;
        $empty_results = false;

        foreach ($param_names as $param) {
            if(array_key_exists($param, $search_params)) {

                if(empty($search_params[$param])) {
                    $params->$param = null;
                    $empty_results = true;
                }
                else {
                    $params->$param = array_filter(explode(',', $search_params[$param]), function ($value) use ($mining_data, $param) {
                        return array_key_exists($value, $mining_data->$param);
                    });
                }

                $params_defined = true;
            }
        }

        // filter items based on search params
        if($empty_results)
            $mining_data->equipments = array();
        elseif($params_defined)
            $mining_data->equipments = array_filter($mining_data->equipments, function ($equipment) use ($params) {

                if(property_exists($params, 'cryptocurrencies')
                    && ($params->cryptocurrencies === null || !in_array($equipment->mined_code, $params->cryptocurrencies)))
                    return false;

                if(property_exists($params, 'types')
                    && ($params->types === null || !in_array($equipment->type_code, $params->types)))
                    return false;

                if(property_exists($params, 'sources')
                    && ($params->sources === null || !in_array($equipment->source_code, $params->sources)))
                    return false;

                return true;
            });

        $total_equipments = count($mining_data->equipments);

        // sort resulting set
        if($total_equipments) {

            $field  = $params->order;
            $desc   = $params->desc;
            $cmp    = null;

            if($field === 'name' || $field === 'cryptocurrency' || $field === 'type') {
                if($field === 'cryptocurrency') $attr = 'mined_name';
                elseif ($field === 'type') $attr = 'type_code';
                else $attr = $field;

                // string sort
                $cmp = $desc ?
                    function($a, $b) use($attr) { return 0-strcasecmp($a->$attr, $b->$attr); } :
                    function($a, $b) use($attr) { return strcasecmp($a->$attr, $b->$attr); };

            }
            elseif ($field === 'price' || $field === 'hashrate' || $field === 'power') {
                if($field === 'price') $attr = 'cost';
                else $attr = $field;

                // numeric sort
                $cmp = $desc ?
                    function($a, $b) use($attr) { $av = $a->$attr; $bv = $b->$attr; return $av === $bv ? 0 : ( $av > $bv ? -1 : 1 ); } :
                    function($a, $b) use($attr) { $av = $a->$attr; $bv = $b->$attr; return $av === $bv ? 0 : ( $av > $bv ? 1 : -1 ); };

            }
            else $params->order = 'relevance'; // do not sort

            if($cmp) usort($mining_data->equipments, $cmp);
        }


        $mining_data->total         = $total_equipments;
        $mining_data->pagination    = pagination($mining_data->equipments, $page, $page_size); // pagination screen
        $mining_data->params        = $params;

        return $mining_data;
    }

    // --------------------------------------------------------------------

    /**
     * Returns ICOWatchList ICOs lists, use 'status_type' for specific list
     *
     * @param false|string $status_type
     *
     * @return null|stdClass|array
     */

    public function getICOWatchListData($status_type = false)
    {
        if(!($lists = $this->datasetGet('icos_lists'))) return null;

        if($status_type !== false) {
            return empty($lists->$status_type) ? null : $lists->$status_type;
        }

        return $lists;
    }

    // --------------------------------------------------------------------

    /**
     * Returns user defined ICOs lists, use 'status_type' for specific list
     *
     * @param false|string $status_type
     *
     * @return null|stdClass|array
     */

    public function getCustomICOs($status_type = false)
    {
        $icos_page = $this->settingsGet('icos_page');
        $dt_format = $this->dateTimeFormat();

        if(empty($icos_page['custom_icos'])) return null;

        $lists              = new stdClass();
        $lists->finished    = array();
        $lists->live        = array();
        $lists->upcoming    = array();


        foreach ($icos_page['custom_icos'] as $ico) {

            $start_time = strtotime($ico['start_date']); // get start time
            $end_time   = strtotime($ico['end_date']); // get end time

            if($start_time === false || $end_time === false) continue; // dates must be defined

            $time_now   = time();
            $timeline   = 0;
            $days       = null;
            $hours      = null;


            if($time_now <= $end_time) {
                if($time_now >= $start_time) { // live
                    $type       = 'live';
                    $timeline   = round(($time_now - $start_time) * 100 / ($end_time - $start_time)); // timeline percentage

                    if($timeline < 0) $timeline = 0;
                    if($timeline > 100) $timeline = 100;
                }
                else { // upcoming
                    $type   = 'upcoming';
                    $diff   = date_diff(new DateTime("@$time_now"), new DateTime("@$start_time")); // days to go
                    $days   = $diff->days;
                    $hours  = $diff->h;
                }
            }
            else $type = 'finished';

            $ico_obj                = new stdClass();
            $ico_obj->name          = $ico['name'];
            $ico_obj->description   = $ico['description'];
            $ico_obj->website       = $ico['website'];
            $ico_obj->image         = $ico['image'];
            $ico_obj->featured      = $ico['featured'];
            $ico_obj->start_time    = $start_time;
            $ico_obj->start_date    = date($dt_format, $start_time);
            $ico_obj->end_time      = $end_time;
            $ico_obj->end_date      = date($dt_format, $end_time);
            $ico_obj->timeline      = $timeline;
            $ico_obj->missing_days  = $days;
            $ico_obj->missing_hours = $hours;

            $lists->$type[] = $ico_obj;
        }

        return $status_type ? $lists->$status_type : $lists;
    }

    // --------------------------------------------------------------------

    /**
     * Sorts ICO list
     *
     * @param array $list
     * @param bool $desc
     *
     * @return array
     */

    private function sortICOList(&$list, $desc = true)
    {
        usort($list, function($a, $b) use ($desc) {
            // featured always on top
            if(!empty($a->featured)) return -1;
            if(!empty($b->featured)) return 1;


            $a_start = $a->start_time;
            $b_start = $b->start_time;

            if($a_start === $b_start) return 0;

            if($desc) return $a_start > $b_start ? 1 : -1;

            return $a_start > $b_start ? -1 : 1;
        });

        return $list;
    }

    // --------------------------------------------------------------------

    /**
     * Returns all ICOs lists, use 'status_type' for specific list
     *
     * @param false|string $status_type
     *
     * @return stdClass|array
     */

    public function getICOs($status_type = false)
    {
        $ico_watch  = $this->getICOWatchListData($status_type);
        $custom     = $this->getCustomICOs($status_type);

        // specific list
        if($status_type) {
            $list = array_merge(empty($ico_watch) ? array() : $ico_watch, empty($custom) ? array() : $custom);
            $this->sortICOList($list, $status_type !== 'finished');
            return $list;
        }

        $lists = new stdClass();

        // all lists
        foreach (array('finished','live','upcoming') as $type) {
            $list = array_merge(empty($ico_watch->$type) ? array() : $ico_watch->$type, empty($custom->$type) ? array() : $custom->$type);
            $this->sortICOList($list, $type !== 'finished');
            $lists->$type = $list;
        }

        return $lists;
    }

    // --------------------------------------------------------------------

    /**
     * Returns exchanges list
     *
     * @return array
     */

    public function getExchanges()
    {
        return $this->datasetGet('exchanges_list', array());
    }

    // --------------------------------------------------------------------

    /**
     * Returns services list
     *
     * @param bool $block
     * @param bool $add_custom
     * @param bool $overriden_urls
     *
     * @return array
     */

    public function getServices($block = true, $add_custom = true, $overriden_urls = true)
    {

        $data       = $this->datasetGet('services_list');
        $settings   = $this->settingsGet('services_page');
        $services   = array();

        if(!$data) {
            $data           = new stdClass();
            $data->services = array();
            $data->tags     = array();
        }

        foreach ($data->services as $service) {
            $slug = $service->slug;

            if($block && in_array($slug, $settings['blocked_list'])) continue; // blocked

            // user defined url replacing
            if($overriden_urls && !empty($settings['overridden_urls'][$slug]))
                $service->url = $settings['overridden_urls'][$slug];

            $services[] = $service;
        }

        // add all user's services
        if($add_custom && !empty($settings['custom_services'])) {

            $custom_services = array_map(function ($service) {
                $service = (object) $service;
                $service->custom = true;

                if($service->tags)
                    $service->tags = array_map('trim', explode(',', $service->tags));

                return $service;
            }, $settings['custom_services']);

            $services = array_merge($custom_services, $services);
        }

        return $services;
    }

    // --------------------------------------------------------------------

    /**
     * Returns built-in pages, use 'just_enabled' to get only active pages
     *
     * @param bool $just_enabled
     *
     * @return array
     */

    public function getBuiltInPages($just_enabled = true)
    {
        $pages = array(
            'press_page'        => 'Press',
            'mining_page'       => 'Mining',
            'converter_page'    => 'Converter',
            'icos_page'         => 'ICOs',
            'exchanges_page'    => 'Exchanges',
            'services_page'     => 'Services',
            'trends_page'       => 'Trends'
        );

        $list = array(
            array(
                'id' => 'market_page',
                'title' => _t($this->settingsGet('market_page','title'), 'Market', true) // try to translate
            )
        );


        foreach ($pages as $id => $title) {
            $settings = $this->settingsGet($id);

            if(!$just_enabled || $settings['enabled'])
                $list[] = array('id' => $id, 'title' => _t($settings['title'], $title, true)); // try to translate
        }

        return $list;
    }

    // --------------------------------------------------------------------

    /**
     * Returns custom pages, use 'public' to restrict by visibility
     *
     * @param int|null $public
     *
     * @return array
     */

    public function getCustomPagesList($public = null)
    {
        $pages = null;
        $where = array();

        // restrict visibility
        if($public !== null) $where['public'] = intval($public);

        if($this->custom_pages_model->readAll($pages, $where, 'id,title,path')) {

            foreach ($pages as &$page) {
                $page['title'] = _t($page['title'], '', true); // try to translate
            }

            return $pages;
        }

        return array();
    }

    // --------------------------------------------------------------------

    /**
     * Return custom pages by visibility
     *
     * @param null|int $public
     *
     * @return array
     */

    public function getCustomPages($public = null)
    {
        $pages = null;
        $where = array();

        // restrict visibility
        if($public !== null) $where['public'] = intval($public);

        return $this->custom_pages_model->readAll($pages, $where, 'id,title,path') ?
            $pages : array();
    }

    // --------------------------------------------------------------------

    /**
     * Returns custom page by id or path, use 'public' to restrict by visibility
     *
     * @param int|string $requested
     * @param int|null $public
     *
     * @return null|array
     */

    public function getCustomPage($requested, $public = null)
    {
        $page = null;
        $where = array();

        // restrict visibility
        if($public !== null) $where['public'] = intval($public);

        if(!empty($requested)) {
            if(is_numeric($requested)) {
                $this->custom_pages_model->read(intval($requested), $page, $where); // get by id
            }
            else  {
                $this->custom_pages_model->readBy('path', $requested, $page, $where); // get by path
            }
        }

        return $page;
    }

    // --------------------------------------------------------------------

    /**
     * Price conversion
     *
     * @param string $from
     * @param string $to
     * @param float $value
     *
     * @return null|float
     */

    public function fx($from, $to, $value)
    {
        if(!($from_rate = $this->getExchangeRates($from))
            || !($to_rate = $this->getExchangeRates($to))
            || !is_numeric($value))
            return null;

        $rate = $from === 'USD' ?
            $to_rate->value : // direct rate
            $to_rate->value / $from_rate->value; // indirect rate

        return $rate * $value;
    }

	// --------------------------------------------------------------------

	/**
	 * Returns tickers for given cryptocurrency with pagination
	 *
	 * @param string $slug
	 * @param int $page
	 * @param int $items
	 *
	 * @return mixed
	 */
	public function getTickers($slug, $page, $items)
	{
		$items = abs(intval($items)) ?: 20;
		if ($items > 100) $items = 100;

		$page = abs(intval($page)) ?: 1;

		$cg_page = ceil($page * $items / 100);

		$dataset = "{$slug}_tickers_{$items}_{$cg_page}";


		if($this->datasetTimeout($dataset, 60)) {
			$tickers_entries = array();

			$exchanges = $this->getExchanges();
			if ($exchanges) {
				$exchanges_urls = array_column($exchanges, 'url', 'id');
			} else {
				$exchanges_urls = [];
			}

			$data = $this->requestCoinGeckoCoinTickers($slug, $cg_page);

			if(isset($data->tickers)) {
				$c = 1;

				foreach ($data->tickers as $t) {
					if (!empty($t->is_stale)) continue;

					$entry = new stdClass();

					$entry->rank            = ($cg_page-1) * 100 + $c++;
					$entry->base            = $t->base;
					$entry->base_slug       = $t->coin_id;
					$entry->quote           = $t->target;
					$entry->quote_slug      = isset($t->target_coin_id) ? $t->target_coin_id : null;
					$entry->url             = isset($t->trade_url) ? $t->trade_url : null;
					$entry->last_price_usd  = $t->converted_last->usd;
					$entry->volume_usd      = $t->converted_volume->usd;
					$entry->exchange_id     = $t->market->identifier;
					$entry->exchange_name   = $t->market->name;
					$entry->exchange_image  = $t->market->logo;
					$entry->exchange_url    = isset($exchanges_urls[$entry->exchange_id]) ? $exchanges_urls[$entry->exchange_id] : null;
					$entry->hash            = md5($entry->exchange_id . $entry->base . $entry->quote);

					$tickers_entries[] = $entry;
				}

				$this->datasetSave($dataset, $tickers_entries);
			}

		}

		$tickers = $this->datasetGet($dataset, array());
		if (!empty($tickers)) {
			$pages_group = (int) 100 / $items;
			$tickers = array_slice($tickers, (($page - 1) % $pages_group) * $items, $items);
		}

		return $tickers;
	}

	// --------------------------------------------------------------------

	/**
	 * Removes expired cache data from database
	 */
	public function deleteExpiredDatasets() {
		$this->options_model->deleteExpiredDatasets();
	}

	// --------------------------------------------------------------------

	/**
	 * Returns chart data for given cryptocurrency
	 *
	 * @param string $slug
	 * @param int $days
	 * @param string $currency
	 * @param int $multiple
	 *
	 * @return array|null
	 */
	public function getChartData($slug, $days, $currency, $multiple = 1)
	{
		$multiple = abs((float) $multiple);
		if (empty($multiple)) {
			$multiple = 1;
		}

		$dataset = "{$slug}_chart_{$days}_$currency";

		if ($this->datasetTimeout($dataset, 60) || empty($data = $this->datasetGet($dataset))) {
			$data = $this->requestCoinGeckoExchangeMarketChart($slug, $currency, $days);
			$was_requested = true;
		}

		if(empty($data) || empty($data->prices) || empty($data->total_volumes) || empty($data->market_caps))
			return null;

		if (!empty($was_requested)) {
			$this->datasetSave($dataset, $data);
		}

		// CoinGecko send inconsistent data lengths
		// remove the older values to uniform the datasets
		$prices_length      = count($data->prices);
		$volumes_length     = count($data->total_volumes);
		$market_caps_length = count($data->market_caps);
		$min_length         = min($prices_length, $volumes_length, $market_caps_length);
		$prices_remove      = $prices_length - $min_length;
		$volumes_remove     = $volumes_length - $min_length;
		$market_caps_remove = $market_caps_length - $min_length;

		if($prices_remove > 0) array_splice($data->prices, 0, $prices_remove);
		if($volumes_remove > 0) array_splice($data->total_volumes, 0, $volumes_remove);
		if($market_caps_remove > 0) array_splice($data->market_caps, 0, $market_caps_remove);

		$chart_data = array();

		foreach ($data->prices as $i => $values) {
			$chart_data[] = array(
				date('Y-m-d H:i',(int) $values[0]/1000),
				priceFormat($values[1] * $multiple, null, ''),
				round($data->market_caps[$i][1]), // another multiple for market cap ?
				round($data->total_volumes[$i][1]), // another multiple for volume ?
			);
		}

		return $chart_data;
	}

}
