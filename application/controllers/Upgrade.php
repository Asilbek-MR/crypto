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
 * @since	  Version 5.2.0
 *
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Upgrade
 *
 * @package		CoinTable
 * @subpackage	Controllers
 * @author		RunCoders
 */

class Upgrade extends CT_Controller
{
	/**
	 *
	 */
	public function __construct() {
		$this->set_timezone = false;
		$this->set_default_lang = false;
		parent::__construct();
	}

	// --------------------------------------------------------------------

	/**
	 * Upgrades database to v2.0 (which alters float type fields to double)
	 */
	public function index() {
		if ( ! $this->tableExists('options') || ! $this->tableExists('coins') || ! $this->tableExists('custom_assets') ) {
			if (file_exists(__DIR__ . '/Install.php')) {
				redirect('install');
			}
			show_error('Database is not correctly installed');
		}

		$this->load->dbforge();

		$current = $this->currentDBVersion();

		if (version_compare( $current, '2.0', '<' )) {
			$this->coinsV2();
			$this->customAssetsV2();
			$this->coin_table->settingsSave('currency_page',$this->coin_table->settingsGet('currency_page'));
			$this->coin_table->saveOption('db_version', '2.0');
		}

		if (version_compare( $current, '2.1', '<' )) {
			$this->fixEnginesV2_1();
			$this->coin_table->saveOption('db_version', '2.1');
		}

		redirect();
	}

	// --------------------------------------------------------------------

	/**
	 * Alters the "coins" table
	 */
	private function coinsV2() {
		$this->dbforge->modify_column('coins', [
			'circulating_supply' => array(
				'type' => 'DOUBLE',
				'unsigned' => true,
				'default' => 0
			),
			'total_supply' => array(
				'type' => 'DOUBLE',
				'unsigned' => true,
				'default' => 0
			),
			'price_usd' => array(
				'type' => 'DOUBLE',
				'unsigned' => true,
				'null' => TRUE
			),
			'market_cap_usd' => array(
				'type' => 'DOUBLE',
				'unsigned' => true,
				'null' => TRUE
			),
			'price_usd_change_24h' => array(
				'type' => 'DOUBLE',
				'null' => TRUE
			),
			'volume_24h_usd' => array(
				'type' => 'DOUBLE',
				'unsigned' => true,
				'null' => TRUE
			),
		]);
	}

	// --------------------------------------------------------------------

	/**
	 * Alters the "custom_assets" table
	 */
	private function customAssetsV2() {
		$this->dbforge->modify_column('custom_assets', [
			'circulating_supply' => array(
				'type' => 'DOUBLE',
				'unsigned' => TRUE
			),
			'total_supply' => array(
				'type' => 'DOUBLE',
				'unsigned' => TRUE,
				'null' => TRUE
			),
			'volume_24h_usd' => array(
				'type' => 'DOUBLE',
				'unsigned' => true,
				'null' => TRUE
			),
			'tracking_multiple' => array(
				'type' => 'DOUBLE',
				'unsigned' => TRUE
			),
		]);
	}

	// --------------------------------------------------------------------

	/**
	 * Fix tables engines (before db version 2.1 explicit engine definition was missing)
	 */
	private function fixEnginesV2_1() {
		$myisam = [ 'coins', 'options', 'custom_assets', 'custom_pages' ];
		$innodb = [ 'users', 'groups', 'users_groups', 'login_attempts' ];

		foreach ( $myisam as $table ) {
			$this->db->simple_query("ALTER TABLE `{$this->db->dbprefix}$table` ENGINE MyISAM");
		}

		foreach ( $innodb as $table ) {
			$this->db->simple_query("ALTER TABLE `{$this->db->dbprefix}$table` ENGINE InnoDB");
		}
	}
}