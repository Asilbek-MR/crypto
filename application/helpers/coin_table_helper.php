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
 * CoinTable Helper
 *
 * @package		CoinTable
 * @subpackage	Helpers
 * @author		RunCoders
 */

/**
 * Makes a HTTP GET request for JSON response
 *
 * @param string $url
 * @param array|null $params
 * @param array|null $headers
 * @param bool $response_array
 *
 * @return mixed
 */

function requestJSON($url, $params = null, $headers = null, $response_array = true)
{
    $curl = new \Curl\Curl();


    $_headers = array(
        'Accept'        => 'application/json',
        'Content-Type'  => 'application/json'
    );
    if(is_array($headers)) $_headers = array_merge($_headers, $headers);
    $curl->setHeaders($_headers);

    // Parsers settings

    $curl->setXmlDecoder(false);

    $parser = function ($body) { return json_decode($body, true); };
    if($response_array) $curl->setJsonDecoder($parser);
    $curl->setDefaultDecoder($parser);

    $curl->get($url, $params);

    return $curl->error ? null : $curl->response;
}

// --------------------------------------------------------------------

/**
 * Check if color is in hexadecimal format
 * examples: #000 or #ffffff
 *
 * @param mixed $color
 *
 * @return bool
 */

function is_color($color)
{
    if(is_string($color)) {
        $len = strlen($color);
        return ($len === 4 || $len === 7) && $color[0] === '#' && ctype_xdigit(substr($color, 1));
    }

    return false;
}

// --------------------------------------------------------------------

/**
 * Check if user is in twitter format
 *
 * @param string $user
 *
 * @return bool
 */

function is_twitter_user($user)
{
    return is_string($user) && strlen($user) > 1 && $user[0] === '@';
}

// --------------------------------------------------------------------

/**
 * Makes timezone better for display
 *
 * @param $timezone
 *
 * @return string
 */

function timeZoneBeautify($timezone)
{
    $parts = explode('/', str_replace('_', ' ', $timezone));
    $count = count($parts);

    if($count === 3) {
        return "{$parts[1]} ({$parts[2]})"; // example: Argentina (Buenos Aires)
    }
    else if($count === 2) {
        return $parts[1]; // example: London
    }
    else {
        return $parts[0]; // example: UTC
    }
}

// --------------------------------------------------------------------

/**
 * Timezones list for admin panel selection
 *
 * @return array
 */

function timeZonesValues()
{
    $values = array();

    foreach (DateTimeZone::listIdentifiers(DateTimeZone::ALL) as $timezone) {
        $values[] = array(
            'value' => $timezone,
            'name' => timeZoneBeautify($timezone)
        );
    }

    return $values;
}

// --------------------------------------------------------------------

/**
 * Associative array of timezones by region
 *
 * @return array
 */

function timeZoneListByRegions()
{
    $regions = array(
        'Africa'        => DateTimeZone::AFRICA,
        'America'       => DateTimeZone::AMERICA,
        'Arctic'        => DateTimeZone::ARCTIC,
        'Antarctica'    => DateTimeZone::ANTARCTICA,
        'Asia'          => DateTimeZone::ASIA,
        'Atlantic'      => DateTimeZone::ATLANTIC,
        'Australia'     => DateTimeZone::AUSTRALIA,
        'Europe'        => DateTimeZone::EUROPE,
        'Indian'        => DateTimeZone::INDIAN,
        'Pacific'       => DateTimeZone::PACIFIC
    );

    $all = array();

    foreach ($regions as $name => $const) {
        $all[$name] = array();
        $timezones = DateTimeZone::listIdentifiers($const);

        foreach ($timezones as $timezone) {
            $all[$name][] = array($timezone, timeZoneBeautify($timezone));
        }
    }

    $all['UTC'] = array(
        array('UTC','Coordinated Universal Time')
    );

    return $all;
}

// --------------------------------------------------------------------

/**
 * Checks if timezones exists
 *
 * @param string $timezone
 *
 * @return bool
 */

function is_timezone($timezone)
{
    return in_array($timezone, timeZoneList());
}

// --------------------------------------------------------------------

/**
 * Checks if rule is none, exclude or include
 *
 * @param $rule
 *
 * @return bool
 */

function is_rule($rule)
{
    return $rule === 'none' || $rule === 'exclude' || $rule === 'include';
}

// --------------------------------------------------------------------

/**
 * Check if is string or int
 *
 * @param string|int $var
 *
 * @return bool
 */

function is_string_or_int($var)
{
    return is_string($var) || is_int($var);
}

// --------------------------------------------------------------------

/**
 * Custom number formatter
 * allows decimal point and thousands separation customization
 *
 * @param float|int $number
 * @param int $decimals
 * @param string $dec_point
 * @param string $thousands_sep
 *
 * @return string
 */

function ct_number_format($number , $decimals = 0 , $dec_point = "." , $thousands_sep = "," )
{
    return $number >= 10000 ? // only add separator for numbers larger then 10000
        number_format($number, $decimals, $dec_point, $thousands_sep) :
        number_format($number, $decimals, $dec_point, '');
}

// --------------------------------------------------------------------

/**
 * Display price format
 *
 * @param $value
 * @param $rate
 * @param bool $show_unit
 *
 * @return string
 */

function priceFormat($value, $rate = null, $thousands_sep = ',')
{
    if(!is_numeric($value))
        return $rate ? "{$rate->unit} ?" : '?';

    $number = floatval($value);
    $exp = log10($number); // get log10 of number for decimal places control

    if($exp >= 4) $price = ct_number_format($number, 0, '.', $thousands_sep);
    elseif ($exp >= 3) $price = ct_number_format($number, 1);
    elseif ($exp >= 2) $price = ct_number_format($number, 2);
    elseif ($exp >= 1) $price = ct_number_format($number, 2);
    elseif ($exp >= 0) $price = ct_number_format($number, 3);
    elseif ($exp >= -1) $price = ct_number_format($number, 4);
    elseif ($exp >= -2) $price = ct_number_format($number, 5);
    elseif ($exp >= -3) $price = ct_number_format($number, 6);
    elseif ($exp >= -4) $price = ct_number_format($number, 7);
    elseif ($exp >= -5) $price = ct_number_format($number, 8);
    elseif ($exp >= -6) $price = ct_number_format($number, 8);
    elseif ($exp >= -7) $price = ct_number_format($number, 8);
    elseif ($exp >= -8) $price = ct_number_format($number, 8);
    else $price = 0;

    return $rate ? "{$rate->unit} $price" : $price;
}

// --------------------------------------------------------------------

/**
 * Prints menu items
 *
 * @param string $position
 * @param array $items
 * @param string $style
 *
 */

function printItems($position, $items, $style = 'text')
{
    $CI =& get_instance(); // get CodeIgniter instance

    foreach ($items as $i => $item) {

        $type = $item['type'];
        $extra_classes = '';
        $url = null;
        $content = null;

        if ($type === 'link') {
            $url = site_url(array('go', $position, $i));
            $content = _t($item['text']);
        }
        elseif ($type === 'page') {
            $page = $item['data'];

            if($page === 'market_page') $url = site_url(CT_MARKET_PAGE);
            elseif ($page === 'press_page') $url = site_url(CT_PRESS_PAGE);
            elseif ($page === 'mining_page') $url = site_url(CT_MINING_PAGE);
            elseif ($page === 'converter_page') $url = site_url(CT_CONVERTER_PAGE);
            elseif ($page === 'icos_page') $url = site_url(CT_ICOS_PAGE);
            elseif ($page === 'exchanges_page') $url = site_url(CT_EXCHANGES_PAGE);
            elseif ($page === 'services_page') $url = site_url(CT_SERVICES_PAGE);
            elseif ($page === 'trends_page') $url = site_url(CT_TRENDS_PAGE);
            else continue;

            $settings = $CI->coin_table->settingsGet($page);

            if($page !== 'market_page' && !$settings['enabled']) continue;

            $content = _t($settings['title']);
        }
        elseif ($type === 'custom_page') {
            $page = null;

            if ($CI->custom_pages_model->read($item['data'], $page) && is_array($page)) { // search for custom page
                $url = empty($page['path']) ? // determines the URL
                    site_url(array(CT_CUSTOM_PAGES, $page['id'])) : // use id
                    site_url(array(CT_CUSTOM_PAGES, $page['path'])); // use custom path

                $content = _t($page['title']);
            }
            else continue;
        }
        elseif ($type === 'social') {
            $network = $CI->config->item($item['data'], 'social_networks'); // get social network details

            if ($network) { // if found
                $url = $CI->coin_table->settingsGet('social', $item['data']); // fetch social network URL

                if ($url) { // if defined
                    if ($style === 'both') { // show social network name & icon
                        $content = "{$network[1]}&nbsp;<i class=\"{$network[0]} icon\"></i>";
                    }
                    elseif ($style === 'icon') { // show social network icon
                        $content = "<i class=\"{$network[0]} icon\"></i>";
                    }
                    else { // show social network name
                        $content = $network[1];
                    }
                }
                else continue;
            }
            else continue;
        }
        elseif ($type === 'donation') {
            $extra_classes .= 'donation-item';
            $text = t('donate');

            $content = $style === 'both' ?
                "{$text}&nbsp;<i class=\"hand peace icon\"></i>" : $text;
        }

        echo "<a class=\"item menu-item $extra_classes\"" . ($url ? " href=\"{$url}\">" : '>') . "$content</a>";
    }
}

// --------------------------------------------------------------------

/**
 * Show brand item for top menu
 *
 * @param string $type
 * @param string $logo
 * @param string $name
 *
 */

function printHeaderBrand($type, $logo, $name = '')
{
    $url = site_url();

    if($type === 'name') {
        echo "<a href=\"$url\" class=\"brand item\">$name</a>";
    }
    else if($type === 'logo') {
        echo "<a href=\"$url\" class=\"brand item\"><img id=\"header-logo\" src=\"$logo\"></a>";
    }
}

// --------------------------------------------------------------------

/**
 * Prints price currency select
 *
 * @param string $classes
 *
 */

function priceCurrencySelect($classes = '')
{
    ?>
    <select class="<?php echo $classes; ?> price-currency ui search dropdown"></select>
    <?php
}

// --------------------------------------------------------------------

/**
 * Prints language select
 *
 * @param $selected
 * @param string $classes
 *
 */

function languageSelect($selected, $classes = '')
{
    $CI =& get_instance(); // get CodeIgniter instance

    $languages = $CI->config->item('languages_available'); // get rates
    ?>
    <div class="<?php echo $classes; ?> language ui compact selection dropdown">
        <input type="hidden">
        <i class="dropdown icon"></i>
        <div class="text"><?php echo strtoupper($selected); ?></div>
        <div class="menu">
            <?php foreach ($languages as $code => $details) :?>
                <div class="item" data-value="<?php echo $code; ?>">
                    <i class="<?php echo $details['flag']; ?> flag"></i>
                    <?php echo strtoupper($code); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

// --------------------------------------------------------------------

/**
 * Market table details
 *
 * @param float|null $change
 *
 * @return object
 */

function tableChangeDetails($change)
{
    $details = new stdClass();

    if(is_null($change)) {
        $details->icon = 'help';
        $details->text = '';
        $details->class = 'disabled';
    }
    elseif($change === 0) {
        $details->icon = null;
        $details->text = '0.00';
        $details->class = '';
    }
    elseif ($change > 0) {
        $details->icon = 'arrow up';
        $details->text = ct_number_format($change, 2);
        $details->class = 'positive';
    }
    else {
        $details->icon = 'arrow down';
        $details->text = ct_number_format(abs($change), 2);
        $details->class = 'negative';
    }

    return $details;
}

// --------------------------------------------------------------------

/**
 * Currency page change details
 *
 * @param float|null $change
 *
 * @return object
 */

function changeDetails($change)
{
    $details = new stdClass();

    if(is_null($change)) {
        $details->icon = 'help';
        $details->text = '';
        $details->color = 'orange';
    }
    elseif($change === 0) {
        $details->icon = 'circle outline';
        $details->text = '0.00';
        $details->color = '';
    }
    elseif ($change > 0) {
        $details->icon = 'caret up';
        $details->text = ct_number_format($change, 2);
        $details->color = 'green';
    }
    else {
        $details->icon = 'caret down';
        $details->text = ct_number_format(abs($change), 2);
        $details->color = 'red';
    }

    return $details;
}

// --------------------------------------------------------------------

/**
 * Replace or merge entries to data
 *
 * @param stdClass $data
 * @param stdClass|array $extra
 *
 */

function joinToData($data, $extra)
{
    if(!is_object($data) || !(is_object($extra) || is_array($extra)))
        return;

    foreach ($extra as $prop => $value) {
        if(property_exists($data, $prop)) { // if data param is an array
            if(is_array($data->$prop)) {
                is_array($value) ?
                    $data->$prop = array_merge($data->$prop, $value) : // merge if extra param is array too
                    $data->{$prop}[] = $value;
            }
            elseif (is_object($data->$prop) && (is_array($value) || is_object($value))) {
                foreach ($value as $k => $v) {
                    $data->$prop->$k = $v;
                }
            }
            else $data->$prop = $value;
        }
        else $data->$prop = $value; // if not data param is not array, replace it
    }
}

// --------------------------------------------------------------------

/**
 * Shorter for frontend asset URL
 *
 * @param string $folder
 * @param string $name
 *
 * @return string
 */

function frontendAsset($folder, $name)
{
    return base_url(array('assets', 'frontend', $folder, $name));
}

// --------------------------------------------------------------------

/**
 * Shorter for admin asset URL
 *
 * @param string $folder
 * @param string $name
 *
 * @return string
 */

function adminAsset($folder, $name)
{
    return base_url(array('assets', 'admin', $folder, $name));
}

// --------------------------------------------------------------------

/**
 * Shorter for Coin's images URL
 *
 * @param string $folder
 * @param string $name
 *
 * @return string
 */

function coinImageUrl($slug, $size)
{
    return base_url(array('coins_images', $slug, $size));
}

// --------------------------------------------------------------------

/**
 * Number comparision
 *
 * @param float|int $a
 * @param float|int $b
 *
 * @return int
 */

function numbercmp($a, $b)
{
    return $a === $b ? 0 : ($a > $b ? 1 : -1);
}

// --------------------------------------------------------------------

/**
 * Number minimum representation from 2 decimal places
 *
 * examples:
 *   2.105 -> 2.1
 *   10.001 -> 10
 *
 * @param float $number
 *
 * @return string
 */

function minNumberRep($number)
{
    $float = ct_number_format($number, 2); // 2 decimals places
    $length = strlen($float);

    $remove = 0;

    if($float[$length-1] === '0') { // test if last if 0
        $remove = $float[$length-2] === '0' ? 3 : 1; // if penultimate is 0 too, remove .00 part otherwise just the last 0
    }

    return $remove === 0 ? $float : substr($float,0, $length - $remove);
}

// --------------------------------------------------------------------

/**
 * Hashrate display formatter
 *
 * examples:
 *   1250000 -> 1.25 kH/s
 *   3005000000 -> 3 MH/s
 *
 * @param float $hashrate
 *
 * @return string
 */

function hashRateFormat($hashrate)
{
    if($hashrate < 1000) {
        $h = minNumberRep($hashrate);
        $u = '';
    }
    elseif ($hashrate < 1000*1000) {
        $h = minNumberRep($hashrate/1000);
        $u = 'k';
    }
    elseif ($hashrate < 1000*1000*1000) {
        $h = minNumberRep($hashrate/1000/1000);
        $u = 'M';
    }
    elseif ($hashrate < 1000*1000*1000*1000) {
        $h = minNumberRep($hashrate/1000/1000/1000);
        $u = 'G';
    }
    else {
        $h = minNumberRep($hashrate/1000/1000/1000/1000);
        $u = 'T';
    }

    return "$h {$u}H/s";
}

// --------------------------------------------------------------------

/**
 * Check if values is number and is between 0 and 1 inclusive
 *
 * @param mixed $value
 *
 * @return bool
 */

function is_throttle($value)
{
    return is_numeric($value) && $value >= 0 && $value <= 1;
}

// --------------------------------------------------------------------

/**
 * URL data encode
 *
 * @param stdClass|array $params
 *
 * @return string
 */

function encodeUrlParams($params)
{
    $params_str = '';

    if(is_array($params) || is_object($params)) {
        $encoded_params = array();

        foreach ($params as $param => $values) {
            if(is_array($values)) $encoded_params[] = "$param=" . rawurlencode(implode(',', $values));
            elseif(is_bool($values)) $encoded_params[] = "$param=" . ($values ? 1:0);
            elseif($values !== null)  $encoded_params[] = "$param=" . rawurlencode($values);
        }

        if(count($encoded_params))
            $params_str = '?' . implode('&', $encoded_params);
    }

    return $params_str;
}

// --------------------------------------------------------------------

/**
 * Creates a pagination screen
 *
 * @param int|array $items
 * @param int $page
 * @param int $page_size
 *
 * @return stdClass
 */

function pagination($items, $page, $page_size)
{
    $p_items        = array();
    $p_pages        = 0;
    $p_page         = 0;
    $menu           = new stdClass();
    $menu->first    = false;
    $menu->last     = false;
    $menu->pages    = array();

    $total_size = is_array($items) ? count($items) : $items;

    if($total_size > 0) {
        $p_page   = abs(intval($page)) ?: 1;
        $p_pages  = intval(ceil($total_size / $page_size));

        if($p_page > $p_pages) {
            $p_page = $p_pages;
        }

        if(is_array($items)) {
            $p_items = array_slice($items, ($p_page - 1) * $page_size, $page_size);
        }

        if($p_pages > 1) {

            if($p_page > 1) {
                $menu->first = 1;
            }

            if($p_page < $p_pages) {
                $menu->last = $p_pages;
            }

            $end = $p_page + 2;
            $begin = $p_page - 2;

            if($begin < 1) {
                $end += 1 - $begin;
                $begin = 1;
            }

            if($end > $p_pages) {
                if(($begin += $p_pages - $end) < 1) {
                    $begin = 1;
                }
                $end = $p_pages;
            }


            for ($i = $begin; $i <= $end; $i++) {
                $menu->pages[] = $i;
            }

        }
    }

    $pagination         = new stdClass();
    $pagination->items  = $p_items;
    $pagination->page   = $p_page;
    $pagination->pages  = $p_pages;
    $pagination->menu   = $menu;

    return $pagination;
}

// --------------------------------------------------------------------

/**
 * Displays pagination menu
 *
 * @param stdClass $pagination
 * @param string $redirect
 * @param null|stdClass|array $params
 * @param bool $right
 *
 */

function paginationMenu($pagination, $redirect, $params = null, $right = true)
{
    $params_str = encodeUrlParams($params);
    ?>

    <div class="ui <?php if($right) echo 'right floated '; ?>secondary menu">

        <?php if($pagination->menu->first === false) :?>
            <div class="disabled icon item"><i class="angle double left icon"></i></div>
        <?php else :?>
            <a href="<?php echo "$redirect/{$pagination->menu->first}$params_str"; ?>" class="icon item"><i class="angle double left icon"></i></a>
        <?php endif; ?>


        <?php foreach($pagination->menu->pages as $page) : ?>
            <a href="<?php echo "$redirect/$page$params_str"; ?>" class="<?php if($pagination->page === $page) echo 'active '; ?>item"><?php echo $page; ?></a>
        <?php endforeach; ?>


        <?php if($pagination->menu->last === false) :?>
            <div class="disabled icon item"><i class="angle double right icon"></i></div>
        <?php else :?>
            <a href="<?php echo "$redirect/{$pagination->menu->last}$params_str"; ?>" class="icon item"><i class="angle double right icon"></i></a>
        <?php endif; ?>

    </div>
<?php
}

// --------------------------------------------------------------------

/**
 * Displays a sortable table column header
 *
 * @param string $column
 * @param string $content
 * @param string $redirect
 * @param null|stdClass|array $params
 * @param null|string $classes
 *
 */

function columnHeaderOrder($column, $content, $redirect, $params = null, $classes = null)
{
    if(is_object($params)) {
        $desc = $params->desc;
        $order = $params->order;
        $params = clone $params;
        $params->desc = !$desc;
        $params->order = $column;
    }
    elseif(is_array($params)) {
        $desc = $params['desc'];
        $order = $params['order'];
        $params['desc'] = !$desc;
        $params['order'] = $column;
    }
    else return;

    $icon = $column === $order ?
        ($desc ? 'caret down' : 'caret up') :
        'not-sorted sort';

    $url = $redirect . encodeUrlParams($params);

    echo "<th class=\"$classes\"><a href=\"$url\"><i class=\"$icon icon\"></i> $content</a></th>";
}

// --------------------------------------------------------------------

/**
 * Translation pack field
 *
 * @param $field
 *
 * @return string
 */

function t($field)
{
    $CI =& get_instance(); // get CodeIgniter instance
    return $CI->lang->line($field);
}

// --------------------------------------------------------------------

/**
 * Displays a translation pack field
 *
 * @param $field
 *
 */

function et($field)
{
    echo t($field);
}

// --------------------------------------------------------------------

/**
 * User defined translation field
 *
 * @param stdClass|array $data
 * @param string $default_text
 *
 * @return string
 */

function _t($data, $default_text = '', $first_default_lang = false)
{
    $CI             =& get_instance(); // get CodeIgniter instance
    $value          = null;
    $default_lang   = $CI->coin_table->default_lang;
    $visitor_lang   = $CI->coin_table->visitor_lang;

    // try to translate

    if(is_array($data)) {
        if($first_default_lang && !empty($data[$default_lang])) return $data[$default_lang];
        if(!empty($data[$visitor_lang])) return $data[$visitor_lang];
        if(!$first_default_lang &&!empty($data[$default_lang])) return $data[$default_lang];
        if(!empty($data['en'])) return $data['en'];
    }
    elseif (is_object($data)) {
        if($first_default_lang && !empty($data->$default_lang)) return $data->$default_lang;
        if(!empty($data->$visitor_lang)) return $data->$visitor_lang;
        if(!$first_default_lang && !empty($data->$default_lang)) return $data->$default_lang;
        if(!empty($data->en)) return $data->en;
    }

    return $default_text;
}

// --------------------------------------------------------------------

/**
 * Displays user defined translation field
 *
 * @param stdClass|array $data
 *
 * @param string $default_text
 */

function _et($data, $default_text = '')
{
    echo _t($data, $default_text);
}

// --------------------------------------------------------------------

/**
 * Extracts a property value from object, if not exists, returns a default value
 *
 * @param object $obj
 * @param string $prop
 * @param mixed $default
 *
 * @return mixed
 */

function objProp($obj, $prop, $default = null)
{
    return is_object($obj) && property_exists($obj, $prop) ? $obj->$prop : $default;
}

// --------------------------------------------------------------------

/**
 * Ion.RangeSlider values
 *
 * @param float|int $min
 * @param float|int $max
 *
 * @return array
 */

function sliderValues($min, $max)
{
    $values = array();
    $min    = floor($min);
    $max    = ceil($max);

    $min_exp = log10($min);

    if($min_exp === -INF) {
        $values[] = 0;
        $min_exp = 0;
    }
    else $min_exp = floor($min_exp);

    $max_exp = log10($max);

    if($max_exp === -INF) return $values;

    $max_exp = ceil($max_exp);

    for($i = $min_exp; $i <= $max_exp; $i++) {
        $value = pow(10, $i);

        if($value < $min) {
            $values[] = $min;
        }
        elseif($value > $max) {
            $values[] = $max;
            break;
        }
        else {
            $values[] = $value;
        }
    }

    return $values;
}

// --------------------------------------------------------------------

/**
 * Index selection for Ion.RangeSlider
 *
 * @param stdClass $slider
 * @param int $value
 * @param string $side
 *
 */

function sliderValueIndex($slider, $value, $side)
{
    if($value !== null) {
        if(($index = array_search($value, $slider->values)) !== false) $slider->$side = $index;
    }
}




