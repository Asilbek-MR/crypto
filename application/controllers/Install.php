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
 * Class Install
 *
 * @package		CoinTable
 * @subpackage	Controllers
 * @author		RunCoders
 */

/************************************************
 *                  WARNING
 *
 * This file should be removed after installation
 *
 ************************************************/

class Install extends CT_Controller
{
    /**
     * Install constructor.
     *
     * avoid set locale & timezone (database queries)
     */

    private $was_installed = false;

    public function __construct()
    {
        $this->set_timezone = false;
        $this->set_default_lang = false;
        parent::__construct();
    }

    // --------------------------------------------------------------------

    /**
     * Install destructor.
     *
     * try to remove this file
     */

    public function __destruct()
    {
		if($this->was_installed && is_writable(__FILE__)) unlink(__FILE__);
    }

    // --------------------------------------------------------------------

    /**
     * Check if options table already exists on database
     *
     * @return bool
     */

    private function checkInstallation()
    {
        return $this->tableExists('coins');
    }

    // --------------------------------------------------------------------

    /**
     * Installation script
     */

    public function index()
    {
        // load database manipulation class
        $this->load->dbforge();

        // checks URL params for forced installation
        $forced = $this->input->post('force') === 'true';

        if($this->checkInstallation() && !$forced) { // if installed and not forced
            $url = site_url('install');
            $url .= '?force=true';

            // show reset page
            $this->load->view('installation/reset', array(
                'version' => COINTABLE,
                'forced_url' => $url
            ));
        }
        else { // will install table on database

            // tables creation
            $this->ionAuthCreation();

            $this->optionsCreation();
            $this->optionsData();

            $this->customPagesCreation();

            $this->customAssets();

            $this->coinsCreation();

			$this->options_model->saveOption('db_version', CT_DB_VERSION);

            // show success page
            // if files/folder have bad permission warnings will appear
            $this->load->view('installation/success', array(
                'version'       => COINTABLE,
                'message_image' => !is_writable(IMAGEPATH),
                'message_file'  => !is_writable(__FILE__),
                'file_path'     => __FILE__,
                'images_folder' => IMAGEPATH,
                'homepage_url'  => site_url(),
                'login_url'     => site_url('auth')
            ));

            $this->was_installed = true;
        }
    }

    // --------------------------------------------------------------------

    private function optionsCreation()
    {
        // Table structure for table 'options'

        $this->dbforge->drop_table('options', TRUE);

        $this->dbforge->add_field(array(
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '101',
            ),
            'content' => array(
                'type' => 'MEDIUMTEXT'
            )
        ));
        $this->dbforge->add_key('name', TRUE);
        $this->dbforge->create_table('options', FALSE, array( 'ENGINE' => 'MyISAM' ));
    }

    // --------------------------------------------------------------------

    private function optionsData()
    {
        // Dumping data for table 'options'
        // will save default for all system options
        foreach ($this->config->item('option_defaults') as $name => $info) {
            $this->coin_table->saveOption($name, null);
        }
    }

    // --------------------------------------------------------------------

    private function customPagesCreation()
    {
        // Table structure for table 'custom_pages'

        $this->dbforge->drop_table('custom_pages', TRUE);

        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'MEDIUMINT',
                'constraint' => '8',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'path' => array(
                'type' => 'VARCHAR',
                'constraint' => '201',
                'null' => TRUE
            ),
            'title' => array(
                'type' => 'MEDIUMTEXT'
            ),
            'subtitle' => array(
                'type' => 'MEDIUMTEXT',
                'null' => TRUE
            ),
            'content' => array(
                'type' => 'MEDIUMTEXT'
            ),
            'public' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'unsigned' => TRUE
            ),
            'seo_enabled' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'unsigned' => TRUE,
                'default' => 0
            ),
            'seo_title' => array(
                'type' => 'VARCHAR',
                'constraint' => '101',
                'null' => TRUE
            ),
            'seo_description' => array(
                'type' => 'VARCHAR',
                'constraint' => '201',
                'null' => TRUE
            ),
            'seo_og_image_url' => array(
                'type' => 'VARCHAR',
                'constraint' => '501',
                'null' => TRUE
            ),
            'seo_twitter_image_url' => array(
                'type' => 'VARCHAR',
                'constraint' => '501',
                'null' => TRUE
            )

        ));

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('custom_pages', FALSE, array( 'ENGINE' => 'MyISAM' ));
    }

    // --------------------------------------------------------------------

    private function ionAuthCreation()
    {
        // Drop table 'groups' if it exists
        $this->dbforge->drop_table('groups', TRUE);

        // Table structure for table 'groups'
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'MEDIUMINT',
                'constraint' => '8',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
            ),
            'description' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('groups', FALSE, array( 'ENGINE' => 'InnoDB' ));

        // Dumping data for table 'groups'
        $data = array(
            array(
                'id' => '1',
                'name' => 'admin',
                'description' => 'Administrator'
            )
        );
        $this->db->insert_batch('groups', $data);


        // Drop table 'users' if it exists
        $this->dbforge->drop_table('users', TRUE);

        // Table structure for table 'users'
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'MEDIUMINT',
                'constraint' => '8',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'ip_address' => array(
                'type' => 'VARCHAR',
                'constraint' => '16'
            ),
            'username' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'password' => array(
                'type' => 'VARCHAR',
                'constraint' => '80',
            ),
            'salt' => array(
                'type' => 'VARCHAR',
                'constraint' => '40'
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => '100'
            ),
            'activation_code' => array(
                'type' => 'VARCHAR',
                'constraint' => '40',
                'null' => TRUE
            ),
            'forgotten_password_code' => array(
                'type' => 'VARCHAR',
                'constraint' => '40',
                'null' => TRUE
            ),
            'forgotten_password_time' => array(
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
                'null' => TRUE
            ),
            'remember_code' => array(
                'type' => 'VARCHAR',
                'constraint' => '40',
                'null' => TRUE
            ),
            'created_on' => array(
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
            ),
            'last_login' => array(
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
                'null' => TRUE
            ),
            'active' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'unsigned' => TRUE,
                'null' => TRUE
            ),
            'first_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'last_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'company' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE
            ),
            'phone' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE
            )

        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('users', FALSE, array( 'ENGINE' => 'InnoDB' ));

        // Dumping data for table 'users'
        $data = array(
            'id' => '1',
            'ip_address' => '127.0.0.1',
            'username' => 'administrator',
            'password' => '$2a$07$SeBknntpZror9uyftVopmu61qg0ms8Qv1yV6FG.kQOSM.9QhmTo36',
            'salt' => '',
            'email' => 'admin@admin.com',
            'activation_code' => '',
            'forgotten_password_code' => NULL,
            'created_on' => '1268889823',
            'last_login' => '1268889823',
            'active' => '1',
            'first_name' => 'Admin',
            'last_name' => 'istrator',
            'company' => 'ADMIN',
            'phone' => '0',
        );
        $this->db->insert('users', $data);


        // Drop table 'users_groups' if it exists
        $this->dbforge->drop_table('users_groups', TRUE);

        // Table structure for table 'users_groups'
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'MEDIUMINT',
                'constraint' => '8',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'user_id' => array(
                'type' => 'MEDIUMINT',
                'constraint' => '8',
                'unsigned' => TRUE
            ),
            'group_id' => array(
                'type' => 'MEDIUMINT',
                'constraint' => '8',
                'unsigned' => TRUE
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('users_groups', FALSE, array( 'ENGINE' => 'InnoDB' ));

        // Dumping data for table 'users_groups'
        $data = array(
            array(
                'id' => '1',
                'user_id' => '1',
                'group_id' => '1',
            ),
            array(
                'id' => '2',
                'user_id' => '1',
                'group_id' => '2',
            )
        );
        $this->db->insert_batch('users_groups', $data);


        // Drop table 'login_attempts' if it exists
        $this->dbforge->drop_table('login_attempts', TRUE);

        // Table structure for table 'login_attempts'
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'MEDIUMINT',
                'constraint' => '8',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'ip_address' => array(
                'type' => 'VARCHAR',
                'constraint' => '16'
            ),
            'login' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE
            ),
            'time' => array(
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
                'null' => TRUE
            )
        ));

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('login_attempts', FALSE, array( 'ENGINE' => 'InnoDB' ));

    }

    // --------------------------------------------------------------------

    private function customAssets()
    {
        // Table structure for table 'options'

        $this->dbforge->drop_table('custom_assets', TRUE);

        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'MEDIUMINT',
                'constraint' => '8',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'slug' => array(
                'type' => 'VARCHAR',
                'constraint' => '101',
            ),
            'symbol' => array(
                'type' => 'VARCHAR',
                'constraint' => '101',
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '101',
            ),
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
            'image_thumb' => array(
                'type' => 'VARCHAR',
                'constraint' => '501',
                'null' => TRUE
            ),
            'image_small' => array(
                'type' => 'VARCHAR',
                'constraint' => '501',
                'null' => TRUE
            ),
            'image_large' => array(
                'type' => 'VARCHAR',
                'constraint' => '501',
                'null' => TRUE
            ),
            'tracking_multiple' => array(
                'type' => 'DOUBLE',
                'unsigned' => TRUE
            ),
            'tracking_slug' => array(
                'type' => 'VARCHAR',
                'constraint' => '101'
            ),
            'page_content' => array(
                'type' => 'MEDIUMTEXT',
                'null' => TRUE
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('custom_assets', FALSE, array( 'ENGINE' => 'MyISAM' ));
    }

    // --------------------------------------------------------------------

    private function coinsCreation()
    {
        // Table structure for table 'coins'

        $this->dbforge->drop_table('coins', TRUE);

        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'MEDIUMINT',
                'constraint' => '8',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'slug' => array(
                'type' => 'VARCHAR',
                'constraint' => '101',
                'unique' => true
            ),
            'symbol' => array(
                'type' => 'VARCHAR',
                'constraint' => '101'
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '101'
            ),
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
            'image_thumb' => array(
                'type' => 'VARCHAR',
                'constraint' => '201',
                'null' => TRUE
            ),
            'image_small' => array(
                'type' => 'VARCHAR',
                'constraint' => '201',
                'null' => TRUE
            ),
            'image_large' => array(
                'type' => 'VARCHAR',
                'constraint' => '201',
                'null' => TRUE
            ),
            'chart_7d' => array(
                'type' => 'MEDIUMTEXT',
                'null' => TRUE
            ),
            'info' => array(
                'type' => 'MEDIUMTEXT',
                'null' => TRUE
            ),
            'page_content' => array(
                'type' => 'MEDIUMTEXT',
                'null' => TRUE
            ),
            'prices_updated' => array(
                'type' => 'INT',
                'unsigned' => true,
                'default' => 0
            ),
            'info_updated' => array(
                'type' => 'INT',
                'unsigned' => true,
                'default' => 0
            ),
            'status' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'unsigned' => true,
                'default' => 1
            )
        ));

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('coins', FALSE, array( 'ENGINE' => 'MyISAM' ));
    }
}
