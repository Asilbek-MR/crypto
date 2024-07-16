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
 * Class Options_model
 *
 * @package		CoinTable
 * @subpackage	Models
 * @author		RunCoders
 */

class Options_model extends CT_Model
{

    /**
     * Reads an option content by name
     *
     * @param string $name
     * @param mixed $content
     * @param bool $cache
     *
     * @return bool
     */

    public function readOption($name, &$content, $cache = true)
    {
        if(!$cache || !$this->readCache($name, $content)) { // if not cached
            $query = $this->db->get_where($this->table_name, array('name' => $name)); // database query
            $result = $query->row(); // result as object

            if(isset($result)) { // if exists
                $content = unserialize($result->content); // unserialize content
                if($cache) $this->cached[$name] = $content; // cache it if enabled
            }
            else return false; // failure
        }

        return true; // success
    }

    // --------------------------------------------------------------------

    /**
     * Saves an option content
     *
     * @param string $name
     * @param mixed $content
     * @param bool $cache
     *
     * @return bool
     */

    public function saveOption($name, $content, $cache = true)
    {
        // prepare data for database query
        $entry = array(
            'name'      => $name,
            'content'   => serialize($content)
        );

        // execute replace query
        if($this->db->replace($this->table_name, $entry)) { // was inserted
            if($cache) $this->cached[$name] = $content; // cache it if enabled
            return true; // success
        }

        return false; // failure
    }

    // --------------------------------------------------------------------

    /**
     * Removes an option by name
     *
     * @param string $name
     *
     * @return bool
     */

    public function dropOption($name)
    {
        return $this->db->delete($this->table_name, array('name' => $name));
    }

	// --------------------------------------------------------------------

	/**
	 * Removes expired datasets
	 *
	 * @return bool
	 */

	public function deleteExpiredDatasets()
	{
		$interval = 24 * 3600;
		$expiration = time() - $interval;
		$expired_names = [];
		$n = 0;
		$limit = 10;

		$this->db->like('name', 'dataset_last_update_', 'after');
		$query = $this->db->get_where($this->table_name);

		foreach ($query->result() as $row) {
			$last_update = (int) unserialize($row->content);
			if ($expiration > $last_update) {
				$expired_names[] = $row->name;
				$expired_names[] = str_replace('dataset_last_update_', 'dataset_data_', $row->name);
				$n++;
			}

			if ($n === $limit) break;
		}

		if (empty($expired_names)) {
			return false;
		}

		$this->db->where_in('name', $expired_names);
		return $this->db->delete($this->table_name);
	}

}
