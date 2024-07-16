<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/


$route['default_controller']                = 'frontend';

$route[CT_MARKET_PAGE]                      = 'frontend/market';
$route[CT_MARKET_PAGE.'/(:any)']            = 'frontend/market/$1';

$route[CT_PRESS_PAGE]                       = 'frontend/press';
$route[CT_PRESS_PAGE.'/(:any)']             = 'frontend/press/$1';

$route[CT_MINING_PAGE]                      = 'frontend/mining';
$route[CT_MINING_PAGE.'/(:any)']            = 'frontend/mining/$1';

$route[CT_CONVERTER_PAGE]                   = 'frontend/converter';

$route[CT_ICOS_PAGE]                        = 'frontend/icos';
$route[CT_ICOS_PAGE.'/(:any)']              = 'frontend/icos/$1';
$route[CT_ICOS_PAGE.'/(:any)/(:any)']       = 'frontend/icos/$1/$2';

$route[CT_CURRENCY_PAGE.'/(:any)']          = 'frontend/currency/$1';

$route['page/(:any)']                       = 'frontend/pages/$1';
$route[CT_CUSTOM_PAGES.'/(:any)']           = 'frontend/pages/$1';

$route['go/(:any)/(:any)']                  = 'frontend/go/$1/$2';

$route[CT_EXCHANGES_PAGE]                   = 'frontend/exchanges';
$route[CT_EXCHANGES_PAGE.'/(:any)']         = 'frontend/exchanges/$1';

$route[CT_SERVICES_PAGE]                    = 'frontend/services';
$route[CT_SERVICES_PAGE.'/(:any)']          = 'frontend/services/$1';

$route[CT_TRENDS_PAGE]                      = 'frontend/trends';

$route['robots.txt']                        = 'frontend/robots';
$route['sitemap.xml']                       = 'frontend/sitemap';


$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
