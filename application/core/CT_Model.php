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
 * Class CT_Model
 *
 * @package		CoinTable
 * @subpackage	Core
 * @category    Models
 * @author		RunCoders
 */

class CT_Model extends CI_Model {

    /**
     * Holds table name
     *
     * @var string
     */
    public $table_name;

    // --------------------------------------------------------------------

    /**
     * Where conditions
     *
     * @var array
     */
    protected $where = array();

    // --------------------------------------------------------------------

    /**
     * Id of previous single row query
     *
     * @var int|string
     */
    protected $id = null;

    // --------------------------------------------------------------------

    /**
     * Cached memory map
     *
     * @var array
     */
    public $cached = array();

    // --------------------------------------------------------------------

    /**
     * List of table columns
     *
     * @var array
     */
    const COLUMNS = null;

    // --------------------------------------------------------------------

    /**
     * CT_Model constructor.
     *
     * Sets the table name based on model class name
     */

    public function __construct()
    {
        parent::__construct();

        $this->table_name = $this->db->dbprefix(str_replace('_model','', strtolower(get_class($this))));
    }

    // --------------------------------------------------------------------

    /**
     * reads from cache
     *
     * @param string $key
     * @param mixed $value
     * @param bool $use
     *
     * @return bool
     */

    protected function readCache($key, &$value)
    {

        if(array_key_exists($key, $this->cached)) { // is cache use and key found
            $value = $this->cached[$key]; // pass value by reference
            return true; // return true on success
        }

        return false; // return false otherwise
    }

    // --------------------------------------------------------------------

    /**
     * Prepare data for create/update objects
     *
     * @param mixed $data
     * @param mixed $errors
     *
     * @return bool
     */

    protected function prepareData(&$data, &$errors)
    {
        $errors = array();

        if(is_array(static::COLUMNS)) {
            foreach ($data as $key => $value) {
                if(!in_array($key, static::COLUMNS)) {
                    unset($data[$key]);
                }
            }
        }

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Validates id
     *
     * @param int $id
     * @return int
     */

    protected function _id($id)
    {
        return intval($id);
    }

    // --------------------------------------------------------------------

    /**
     * Checks if is possible to cache result
     *
     * @param bool $cache
     * @param mixed $select
     * @param array|null $where
     *
     * @return bool
     */

    protected function isCacheValid($cache, $select, $where)
    {
        return $cache && ( $select === null || !is_array($where) || !count($where) );
    }

    // --------------------------------------------------------------------

    /**
     * Reads a model object by id
     *
     * @param int $id
     * @param array $data
     * @param array $where
     * @param string $select
     * @param bool $cache
     *
     * @return bool
     */

    public function read($id, &$data, $where = array(), $select = null, $cache = false)
    {
        $id = $this->_id($id);

        $cache_valid = $this->isCacheValid($cache, $select, $where);

        if(empty($id)) { // avoiding id = 0
            $data = null;
            return false; // failure
        }

        $this->id = $id;

        if(!$cache_valid || !$this->readCache($id, $data)) { // when cache not providing value
            $this->where = ['id' => $id]; // constraint id

            if($select !== null) $this->db->select($select); // restricts exported properties

            $query = $this->db->get_where($this->table_name, is_array($where) ?
                array_merge($this->where, $where) : $this->where
            ); // database query

            $data  = $query->row_array(); // get result of query as array

            if($cache_valid) $this->cached[$id] = $data; // save it to cache if enabled and is a simple query

            return is_array($data); // if result is array return success, failure otherwise
        }

        return true; // success
    }

    // --------------------------------------------------------------------

    /**
     * Reads model object by custom property value
     *
     * @param string $prop
     * @param mixed $value
     * @param mixed $data
     * @param array $where
     * @param string $select
     *
     * @return bool
     */

    public function readBy($prop, $value, &$data, $where = array(), $select = null)
    {
        $this->where = [$prop => $value]; // constraint property with value

        if($select !== null) $this->db->select($select); // restricts exported properties

        $query = $this->db->get_where($this->table_name, is_array($where) ?
            array_merge($this->where, $where) : $this->where
        ); // database query

        $data  = $query->row_array(); // get result of query as array

        // if result is array return success, failure otherwise
        if(is_array($data)) {

            if(isset($data['id'])) {
                $this->id = $data['id'];
            }

            return true;
        }

        return false;
    }

    // --------------------------------------------------------------------

    /**
     * Reads model objects collection
     *
     * @param mixed $data
     * @param array $where
     * @param string $select
     * @param bool $cache
     *
     * @return bool
     */

    public function readAll(&$data, $where = array(), $select = null, $cache = false)
    {
        $cache_valid = $this->isCacheValid($cache, $select, $where);

        if(!$cache_valid || !$this->readCache(0, $data)) {
			$this->where = [];

            if($select !== null) $this->db->select($select); // restricts exported properties

            $query = $this->db->get_where($this->table_name, is_array($where) ?
                array_merge($this->where, $where) : $this->where
            );

            $data = $query->result_array();

            if($cache_valid) $this->cached[0] = $data; // save it to cache if enabled and is a simple query

            return is_array($data); // if result is array return success, failure otherwise
        }

        return true; // success
    }

    // --------------------------------------------------------------------

    /**
     * Creates model object
     *
     * @param array $data
     * @param mixed $result
     * @param bool $read
     *
     * @return bool
     */

    public function create($data, &$result = null, $read = true)
    {
        // validate data and make a replace query
        if($this->prepareData($data, $result) && $this->db->insert($this->table_name, $data)){ // if valid and inserted
            $this->id = $this->db->insert_id();

            if($read) return $this->read($this->id, $result); // read back saved object
            else return true;
        }

        return false; // failure
    }

    // --------------------------------------------------------------------

    /**
     * Updates model object by id
     *
     * @param int $id
     * @param array $data
     * @param mixed $result
     *
     * @return bool
     */

    public function update($id, $data, &$result = null, $read = true)
    {
        $id = $this->_id($id);

        if(empty($id)) { // avoiding id = 0
            $result = null;
            return false; // failure
        }

        $this->id = $id;

        if($this->prepareData($data, $result)){ // if data valid
            $this->db->where('id', $id); // constraint id

            // update query
            if($this->db->update($this->table_name, $data)) { // if data updated
                if($read) return $this->read($id, $result); // read back saved object
                else return true;
            }
        }

        return false; // failure
    }

    // --------------------------------------------------------------------

    /**
     * Removes model object by id
     *
     * @param int $id
     *
     * @return bool
     */

    public function remove($id)
    {
        $id = $this->_id($id);

        if(empty($id)) { // avoiding id = 0
            return false; // failure
        }

        return $this->db->delete($this->table_name, array('id' => $id), 1);
    }

	/**
	 * @see https://dev.mysql.com/doc/refman/8.0/en/optimize-table.html
	 */
	public function optimize() {
		$this->db->simple_query("OPTIMIZE LOCAL TABLE `$this->table_name`;");
	}

}
