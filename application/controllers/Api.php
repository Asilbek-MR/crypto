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
 * Class Api
 *
 * @package		CoinTable
 * @subpackage	Controllers
 * @author		RunCoders
 */

class Api extends CT_Controller
{
    /**
     * Will hold the HTTP request method (get/post/...)
     *
     * @var string
     */

    private $method;

    /**
     * Api constructor.
     *
     * Send CORS Headers
     * Load Ion Auth library
     *
     */

    public function __construct()
    {
        parent::__construct();
        $this->corsHeaders();
        $this->checkMethod();
        $this->loadAuth();
		$this->disallowCacheHeaders();
    }

    // --------------------------------------------------------------------

    /**
     * Is a CRUD standard pattern for model
     *
     * @param string $model
     * @param int|null $id
     */

    private function resources($model, $id = null)
    {

        if(!$this->ion_auth->is_admin())
            $this->forbidden();

        $method  = $this->method;
        $content = null;

        // reads single object or all collection
        if ($method === 'get') {
            if($id){
                $result = $this->$model->read($id, $content);
                $this->sendJson($result ? $content : array('message'=>'Not Found'), $result);
            }
            else {
                $result = $this->$model->readAll($content);
                $this->sendJson($result ? $content : array('message'=>'Not Found'), $result);
            }
        }

        // creates or updates object with id
        else if ($method === 'post') {
            if($id) {
                $result = $this->$model->update($id, $this->getJson(), $content);
                $this->sendJson($content, $result);
            }
            else {
                $result = $this->$model->create($this->getJson(), $content);
                $this->sendJson($content, $result);
            }
        }

        // removes object with id
        else if ($method === 'delete' && $id) {
            $result = $this->$model->remove($id, $content);
            $this->sendJson($content, $result);
        }

        // If not defined
        $this->notFound();
    }

    // --------------------------------------------------------------------

    /**
     * Updates method and exits on OPTION request (made by angularJS)
     *
     */

    private function checkMethod()
    {
        $this->method = $this->input->method(false);

        if($this->method === 'options') exit;
    }

    // --------------------------------------------------------------------

    /**
     * Forces 403 (Forbidden) response
     */

    private function forbidden()
    {
        $this->sendJson(null, 403);
    }

    // --------------------------------------------------------------------

    /**
     * Forces 404 (Not Found) response
     */

    private function notFound()
    {
        $this->sendJson(null, 404);
    }

    // --------------------------------------------------------------------

    /**
     * Silent root
     *
     */

    public function index()
    {
        $this->notFound();
    }

    // --------------------------------------------------------------------

    /**
     * Used to test login status
     */

    public function session()
    {
        $this->sendJson($this->ion_auth->is_admin());
    }

    // --------------------------------------------------------------------

    /**
     * Gets/sets option
     *
     * @param string $name
     */

    public function options($name)
    {
        if(!$this->ion_auth->is_admin()){
            $this->forbidden();
        }

        $method = $this->method;

        if($method === 'get') {
            // Not use cache
            return $this->sendJson($this->coin_table->getOption($name, null, false));
        }
        else if($method === 'post') {

            // Save option
            if($this->coin_table->saveOption($name, $this->getJson())) {
                // If saved, send it back
                return $this->sendJson($this->coin_table->getOption($name, null, false));
            }
            else {
                // if something
                return $this->sendJson(null, 500);
            }
        }

        // If not defined
        $this->notFound();
    }

    // --------------------------------------------------------------------

    /**
     * Pages (built-in & custom) information access
     *
     * @param string|null $type
     * @param int|null $id
     */

    public function pages($type = null, $id = null)
    {
        if(!$this->ion_auth->is_admin())
            $this->forbidden();

        // Sends all active pages basic info
        if($type === null && $this->method === 'get') {

            $pages = array(
                'built_in'  => $this->coin_table->getBuiltInPages(),
                'custom'    => $this->coin_table->getCustomPagesList(1)
            );

            return $this->sendJson($pages);
        }

        // Sends built-in pages
        else if($type === 'built_in') {
            return $this->sendJson($this->coin_table->getBuiltInPages(false));
        }

        // Sends custom pages
        else if($type === 'custom') {
            return $this->customPages($id);
        }

        // If not defined
        $this->notFound();
    }

    // --------------------------------------------------------------------

    /**
     * CRUD for custom pages
     *
     * @param int|null $id
     */

    private function customPages($id = null)
    {
        return $this->resources('custom_pages_model', $id);
    }

    // --------------------------------------------------------------------

    /**
     * Sends rates
     *
     * Could be with full info (type=null) or just pair currency code -> rate (type=simple)
     *
     * @param string|null $type
     */

    public function rates($type = null)
    {
		$this->allowCacheHeaders();

        $rates = $this->coin_table->getExchangeRates();

        if($type === 'fx') {
            $fx_rates = new stdClass();
            $fx_rates->base = 'USD';
            $fx_rates->rates = new stdClass();

            foreach ($rates as $code => $rate) {
                $fx_rates->rates->$code = $rate->value;
            }

            $this->sendJson($fx_rates);
        }
        else if($type === 'list') {
            $dd_rates = array();

            foreach ($rates as $code => $rate) {
                $name = $rate->type === 'crypto' ? "$rate->name ($rate->unit)" : $rate->name;
                $dd_rates[] = array('name' => $name, 'value' => $code);
            }

            $this->sendJson($dd_rates);
        }

        $this->sendJson($rates);
    }

    // --------------------------------------------------------------------

    /**
     * Images management
     *
     * @param string|null $name
     */

    public function images($name = null)
    {
        if(!$this->ion_auth->is_admin())
            $this->forbidden();

        $method = $this->method;

        // sends images list
        if($method === 'get') {

            // files on the images folder
            $files = scandir(IMAGEPATH, SCANDIR_SORT_DESCENDING);
            $images = array();
            $images_folder = base_url('images');

            // remove invalid entries
            foreach ($files as $file) {
                if($file[0] === '.' || $file === 'index.html') continue;

                $images[] = array(
                    'name' => $file,
                    'url'  => "$images_folder/$file"
                );
            }

            return $this->sendJson($images);
        }

        // uploads image
        elseif($method === 'post') {

            // load CI upload library
            // Use current time as image name
            $this->load->library('upload', array(
                'upload_path'   => IMAGEPATH,
                'allowed_types' => 'gif|jpg|png',
                'file_name'     => strval(time())
            ));

            // On success
            if ($this->upload->do_upload('image_file')) {
                return $this->sendJson($this->upload->data());
            }

            // On failure
            else {
                return $this->sendJson($this->upload->display_errors(), 500);
            }
        }

        // removes image
        elseif ($method === 'delete' && !empty($name)) {

            $filepath = IMAGEPATH.$name;

            // only removes if file exists
            return (file_exists($filepath) && @unlink($filepath)) ? $this->sendJson(true) : $this->sendJson(false);
        }

        // If not defined
        $this->notFound();
    }

    // --------------------------------------------------------------------

    /**
     * Removes private information from user
     *
     * @param array $user
     *
     * @return array
     */

    private function exportUser($user)
    {
        return array(
            'id'            => $user->id,
            'first_name'    => $user->first_name,
            'last_name'     => $user->last_name,
            'email'         => $user->email,
            'active'        => $user->active
        );
    }

    // --------------------------------------------------------------------

    /**
     * Checks/cleans user's data for creation or update
     *
     * @param array $data
     * @param bool $check_password
     */

    private function cleanUser($data, $check_password = false)
    {
        $user = array();

        if(empty($data['first_name'])){
            return null;
        }
        else {
            $user['first_name'] = $data['first_name'];
        }

        if(empty($data['last_name'])){
            return null;
        }
        else {
            $user['last_name'] = $data['last_name'];
        }

        if(empty($data['email'])){
            return null;
        }
        else {
            $user['email'] = strtolower($data['email']);
        }

        if(isset($data['active'])){
            $user['active'] = intval($data['active']);
        }
        else {
            return null;
        }

        $pass_exists = !empty($data['password']);

        // If password checking needed
        if($check_password || $pass_exists){

            if(!$pass_exists || !is_string($data['password']))
                return null;

            // Min & max length are defined in config file
            $min_len = $this->config->item('min_password_length','ion_auth');
            $max_len = $this->config->item('max_password_length','ion_auth');

            $len = strlen($data['password']);

            if($len < $min_len || $len > $max_len) {
                return null;
            }

            $user['password'] = $data['password'];
        }

        return $user;
    }

    // --------------------------------------------------------------------

    /**
     * CRUD for user model
     *
     * @param int|null $id
     */

    public function users($id = null)
    {
        if(!$this->ion_auth->is_admin())
            $this->forbidden();

        $method = $this->method;

        // reads user(s)
        if ($method === 'get') {
            // single user
            if($id){
                $user = $this->ion_auth->user(intval($id))->row();
                // send safe user data
                return $this->sendJson($user ? $this->exportUser($user) : null);
            }
            // all users
            else {
                $users = $this->ion_auth->users()->result();
                $clean = array();

                // prepare found users
                foreach ($users as $user) {
                    $clean[] = $this->exportUser($user);
                }

                // send safe user list
                return $this->sendJson($clean);
            }
        }

        // creates or updates an user
        else if ($method === 'post') {
            // update
            if($id) {

                // clean user's data
                $user = $this->cleanUser($this->getJson());

                // updates only if data is good
                if($user) {
                    $updated = $this->ion_auth->update(intval($id), $user);

                    // send result
                    return $this->sendJson($updated);
                }

                // sends false on bad data
                return $this->sendJson(false);
            }

            // creation
            else {
                // clean user's data
                $user = $this->cleanUser($this->getJson(), true);

                // creates only if data is good
                if($user) {

                    $additional_data = array(
                        'first_name' => $user['first_name'],
                        'last_name'  => $user['last_name']
                    );

                    $new_id = $this->ion_auth->register($user['email'], $user['password'], $user['email'], $additional_data);

                    // send new user's id
                    return $this->sendJson($new_id);
                }

                // sends false on bad data
                return $this->sendJson(false);
            }
        }

        // removes an user
        else if ($method === 'delete') {
            // Only removes if is not itself
            if($this->ion_auth->user()->row()->id !== $id) {
                // return the result
                return $this->sendJson($this->ion_auth->delete_user($id));
            }

            // sends false otherwise
            return $this->sendJson(false);
        }

        // If not defined
        $this->notFound();
    }

    // --------------------------------------------------------------------

    /**
     * CRUD for custom asset model
     *
     * @param int|null $id
     */

    public function custom_assets($id = null)
    {
        return $this->resources('custom_assets_model', $id);
    }

    // --------------------------------------------------------------------

    /**
     * Sends services listing
     *
     * @param null|string $request
     */

    public function services($request = null)
    {
        if($request === 'list') {
            $this->sendJson(array_map(function ($service) {
                return array(
                    'name'  => $service->name,
                    'value' => $service->slug
                );
            }, $this->coin_table->getServices(false, false, false)));
        }

        $this->sendJson($this->coin_table->getServices());
    }

    // --------------------------------------------------------------------

    /**
     * Sends mining equipment information
     */

    public function mining_equipment()
    {
       $this->sendJson($this->coin_table->getMiningEquipmentData());
    }

    // --------------------------------------------------------------------

    /**
     * Sends exchanges listing
     */

    public function exchanges()
    {
        $this->sendJson($this->coin_table->getExchanges());
    }

    // --------------------------------------------------------------------

    /**
     * CRUD for coin model
     *
     * @param string|null $request
     * @param mixed $arg1
     * @param mixed $arg2
     */

    public function coins($request = null, $arg1 = null, $arg2 = null)
    {
        if($this->method === 'get') {
            $data = null;

			$this->allowCacheHeaders();

            // sends all coins with market data
            if($request === null) {
                $select = 'slug,name,symbol,circulating_supply,total_supply,price_usd,market_cap_usd,price_usd_change_24h AS change_24h,volume_24h_usd,image_large AS image';
                $chart_7d = $this->input->get('chart') === 'true';

                if($chart_7d) $select .= ',chart_7d'; // include chart 7d

                if($this->coins_model->readAll($data, array('status' => 1), $select) && is_array($data)) {

                    if($chart_7d) {
                        foreach ($data as &$coin) {
                            if(is_array($coin['chart_7d'])) {
                                // shorting numbers for smaller JSON response
                                $coin['chart_7d'] = array_map(function ($val) {return priceFormat($val);}, $coin['chart_7d']);
                            }
                        }
                    }

	                $this->allowCacheHeaders();
                    $this->sendJson($data);
                }
            }
            elseif($request === 'trends') {
	            $this->allowCacheHeaders();
                $this->sendJson($this->coin_table->getCoinTrends($arg1 ? intval($arg1): 10));
            }
            elseif($request === 'list') { // sends all coin for selection dropdown
				$this->allowCacheHeaders();
                $this->sendJson(array_map(function ($coin) {
                    return array(
                        'name'  => "{$coin['name']} ({$coin['symbol']})",
                        'value' => $coin['slug']
                    );
                }, $this->coins_model->listing()));
            }
            elseif ($request === 'table') { // sends all coins in datatable format (check coins at admin panel)
                if(!$this->ion_auth->is_admin())
                    $this->forbidden();

                if($this->coins_model->readAll($data, null, 'slug,name,symbol,info_updated,prices_updated,status') && is_array($data)) {
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(array(
                            'data' => $data
                        ), JSON_NUMERIC_CHECK))
                        ->_display();
                    exit;
                }

            }
            elseif ($request === 'info' && $arg1) { // sends all information for one coin
                if(!$this->ion_auth->is_admin())
                    $this->forbidden();

                $this->sendJson($this->coin_table->getCoin($arg1));
            }
			elseif ($request === 'search') {
				$results = null;
				$limit = $arg1 ? intval($arg1): 10;

				$c = (array) $this->input->get('c');
				if (!empty($c)) {
					$results = $this->coins_model->listSearch($c, $limit);
				}

				if ($results === null) {
					$q = $this->input->get('q');
					if (isset($q)) {
						$results = $this->coins_model->listSearch((string) $q, $limit);
					}
				}

				if (is_array($results)) {
					$results = array_map(function ($item) {
						return [
							'name' => sprintf( '%s (%s)', $item['name'], $item['symbol'] ),
							'value' => $item['slug'],
						];
					}, $results);

					$this->sendJson([
						'success' => true,
						'results' => $results,
					]);
				}
			}
        }
        elseif ($this->method === 'post') {

            if(!$this->ion_auth->is_admin()) $this->forbidden();


            if ($request === 'update') { // full update
                if($this->coins_model->updateBySlug($arg1, $this->getJson())) {
                    $this->sendJson($this->coin_table->getCoin($arg1));
                }
            }
            elseif ($request === 'update_info') { // update information
                $this->sendJson($this->coin_table->syncCoinFullInfo($arg1, false));
            }
            elseif ($request === 'update_status') { // enable/disable
                $this->sendJson($this->coins_model->updateBySlug($arg1, array(
                    'status' => intval($arg2) === 1 ? 1 : 0
                )));
            }
        }


        $this->notFound();
    }

    // --------------------------------------------------------------------

    /**
     * Sends historical chart for given cryptocurrency
     *
     * @param string $slug
     * @param int|string $days
     * @param string $currency
     */

    public function chart_data($slug, $days, $currency = 'usd')
    {
		$chart_data = $this->coin_table->getChartData($slug, $days, $currency, $this->input->get('multiple'));
		if (!$chart_data) $this->notFound();

		$this->allowCacheHeaders();
        $this->sendJson($chart_data);
    }

	// --------------------------------------------------------------------

	/**
	 * Generates chart data for market page (7 days column)
	 *
	 * @since 5.2.0
	 */
	public function charts_7d() {
		$slugs = $this->input->get('slugs');
		if ( empty($slugs) || ! is_string($slugs) ) {
			$this->sendJson(null, 400);
		}

		$slugs = explode( ',', $slugs );
		$charts = [];

		foreach ( $slugs as $slug ) {
			$coin = $this->coin_table->getCoin($slug);
			if ( empty( $coin ) ) {
				$coin = $this->coin_table->getCustomAsset($slug);
			}
			if ( empty( $coin ) || empty($coin['chart_7d']) || count($coin['chart_7d']) < 3) {
				continue;
			}

			$start = $coin['chart_7d'][0];
			$sign  = null;
			$last  = count($coin['chart_7d']) -1;

			$chart_data = new stdClass();
			$chart_data->series = array();
			$chart_data->x = array();

			$series = new stdClass();
			$series->data = array();

			foreach ($coin['chart_7d'] as $i => $value) {
				$chart_data->x[] = $i;
				$v = $value - $start;

				if($i === 0)
					$series->data[] = $v;
				elseif($i === 1) {
					$series->data[] = $v;
					$sign = $v >= 0 ? 1 : -1;
					$series->color = $sign === 1 ? 'green' : 'red';
				}
				elseif($i === $last) {
					$series->data[] = $v;
					$chart_data->series[] = $series;
				}
				else {
					$i_sign = $v >= 0 ? 1 : -1;

					if($i_sign !== $sign) {
						$series->data[] = 0;
						$chart_data->series[] = $series;

						$series          = new stdClass();
						$series->data    = array_fill(0, $i, null);
						$series->data[]  = 0;
						$series->data[]  = $v;
						$series->color   = $i_sign === 1 ? 'green' : 'red';
						$sign           = $i_sign;
					}
					else $series->data[] = $v;
				}
			}

			$charts[$slug] = $chart_data;
		}

		$this->allowCacheHeaders();
		$this->sendJson($charts);
	}

	// --------------------------------------------------------------------

	/**
	 * Sends exchange tickers for given cryptocurrency
	 *
	 * @param string $slug
	 * @param int $page
	 * @param int $items
	 */
	public function tickers($slug, $page = 1, $items = 20) {
		$this->setPriceCurrency();

		$tickers = $this->coin_table->getTickers($slug, $page, $items);

		if ($tickers) {
			foreach ($tickers as $ticker) {
				$ticker->last_price = priceFormat($this->coin_table->fx('USD', $this->price_currency, $ticker->last_price_usd), $this->price_rate);
				$ticker->volume     = priceFormat($this->coin_table->fx('USD', $this->price_currency, $ticker->volume_usd), $this->price_rate);
			}

			$this->allowCacheHeaders();
			$this->sendJson($tickers);
		}

		$this->notFound();
	}

}
