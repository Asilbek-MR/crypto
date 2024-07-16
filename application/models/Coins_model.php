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
 * Class Coins_model
 *
 * @package		CoinTable
 * @subpackage	Models
 * @author		RunCoders
 */

class Coins_model extends CT_Model
{

    const ORDER_FIELD_TO_COLUMN = array(
        'name'                  => 'name',
        'market_cap'            => 'market_cap_usd',
        'volume_24h'            => 'volume_24h_usd',
        'price'                 => 'price_usd',
        'change_24h'            => 'price_usd_change_24h',
        'circulating_supply'    => 'circulating_supply',
        'total_supply'          => 'total_supply'
    );

    // --------------------------------------------------------------------

    /**
     * Restrict to enabled & recent prices updated cryptocurrencies
     */

    private function activeConditions()
    {
        $this->db->where(array(
            'status'            => 1,
            'prices_updated >'  => time() - 24*60*60
        ));
    }

    // --------------------------------------------------------------------

    protected function prepareData(&$data, &$errors)
    {
        if(isset($data['info']))
            $data['info'] = serialize($data['info']);
        if(isset($data['chart_7d']))
            $data['chart_7d'] = serialize($data['chart_7d']);
        if(isset($data['page_content']))
            $data['page_content'] = empty($data['page_content']) ? null : serialize((object) $data['page_content']);

        return true;
    }

    // --------------------------------------------------------------------

    private function unserializeData(&$data)
    {
        if(isset($data['info']))
            $data['info'] = unserialize($data['info']);
        if(isset($data['chart_7d']))
            $data['chart_7d'] = unserialize($data['chart_7d']);
        if(isset($data['page_content']))
            $data['page_content'] = unserialize($data['page_content']);
    }

    // --------------------------------------------------------------------

    public function read($id, &$data, $where = array(), $select = null, $cache = false)
    {
        if(parent::read($id, $data, $where, $select, $cache)) {
            $this->unserializeData($data);
            return true;
        }

        return false;
    }

    // --------------------------------------------------------------------

    public function readBy($prop, $value, &$data, $where = array(), $select = null)
    {
        if(parent::readBy($prop, $value, $data, $where, $select)) {
            $this->unserializeData($data);
            return true;
        }

        return false;
    }

    // --------------------------------------------------------------------

    public function readAll(&$data, $where = array(), $select = null, $cache = false)
    {
        if(parent::readAll($data, $where, $select, $cache)) {
            foreach ($data as &$entry) {
                $this->unserializeData($entry);
            }

            return true;
        }

        return false;
    }

    // --------------------------------------------------------------------

    /**
     * Returns all cryptocurrencies in database (only slug)
     *
     * @return array
     */

    public function allSlugs()
    {
        $this->db->select('slug');
        $query = $this->db->get($this->table_name);

        return array_map(function ($coin) {return $coin['slug'];}, $query->result_array());
    }

    // --------------------------------------------------------------------

    /**
     * Returns earlier price updated cryptocurrencies
     *
     * @param int $limit
     *
     * @return array
     */

    public function earlierUpdatedSlugs($limit = 300)
    {
        $this->db->select('slug');
        $this->db->order_by('prices_updated', 'ASC');
        $query = $this->db->get_where($this->table_name, array('status' => 1), $limit);
        return array_column($query->result_array(), 'slug');
    }

	// --------------------------------------------------------------------

	/**
	 * Returns top cryptocurrencies
	 *
	 * @param int $limit
	 *
	 * @return array
	 */
	public function top($limit = 100, $select = null) {
		if (isset($select)) $this->db->select($select);
		$this->db->order_by('market_cap_usd', 'DESC');
		$query = $this->db->get_where($this->table_name, array('status' => 1), $limit);
		return $query->result();
	}

    // --------------------------------------------------------------------

    /**
     * Updates cryptocurrency by slug
     *
     * @param string $slug
     * @param array $data
     *
     * @return bool
     */

    public function updateBySlug($slug, $data)
    {
        $errors = null;

        if($this->prepareData($data, $errors)){ // if data valid

            $this->db->where('slug', $slug); // constraint slug

            // update query
            return $this->db->update($this->table_name, $data, null, 1);
        }

        return false; // failure
    }

    // --------------------------------------------------------------------

    /**
     * Sets conditions
     *
     * @param $params
     *
     */

    private function searchConditions($params)
    {
        if(isset(self::ORDER_FIELD_TO_COLUMN[$params->order]))
            $order_col = self::ORDER_FIELD_TO_COLUMN[$params->order];
        else {
            $order_col = self::ORDER_FIELD_TO_COLUMN['market_cap'];
            $params->order = 'market_cap';
        }

        $this->db->order_by($order_col, $params->desc ? 'DESC' : 'ASC');

        $this->activeConditions();

        if(empty($params->c)) { // only apply filters if none cryptocurrency was selected

            if($params->mcf !== null)
                $this->db->where('market_cap_usd >=', $params->mcf);
            if($params->mct !== null)
                $this->db->where('market_cap_usd <=', $params->mct);

            if($params->pf !== null)
                $this->db->where('price_usd >=', $params->pf);
            if($params->pt !== null)
                $this->db->where('price_usd <=', $params->pt);

            if($params->vf !== null)
                $this->db->where('volume_24h_usd >=', $params->vf);
            if($params->vt !== null)
                $this->db->where('volume_24h_usd <=', $params->vt);

        }
        else $this->db->where_in('slug', $params->c); // restrict to selected cryptocurrencies

        // get only table's display fields
        $this->db->select('slug,symbol,name,circulating_supply,total_supply,price_usd,market_cap_usd,price_usd_change_24h,volume_24h_usd,image_small,info');
    }

    // --------------------------------------------------------------------

    /**
     * Search active cryptocurrencies by search params
     *
     * @param array $params
     * @param int $offset
     * @param int $limit
     *
     * @return stdClass
     */

    public function search($params, $offset, $limit)
    {
        $this->searchConditions($params);

        $search             = new stdClass();
        $search->total      = $this->db->count_all_results($this->table_name);
        $search->coins      = array();
        $search->extremes   = $this->extremes(array(
            'market_cap_usd'    => 'market_cap',
            'price_usd'         => 'price',
            'volume_24h_usd'    => 'volume'
        ));

        if($search->total) {
            $this->searchConditions($params);
            $this->db->limit($limit);
            $this->db->offset($offset);
            $this->readAll($search->coins);
        }

        return $search;
    }

    // --------------------------------------------------------------------

    /**
     * Returns columns' min/max values
     *
     * @param $cols
     *
     * @return stdClass
     */

    public function extremes($cols)
    {
        $this->activeConditions();

        foreach ($cols as $col => $field) {
            $this->db->select_min($col, $field.'_min');
            $this->db->select_max($col, $field.'_max');
        }

        $query  = $this->db->get($this->table_name);
        $row    = $query->row();
        $data   = new stdClass();

        foreach ($cols as $col => $field) {
            $_field = $data->$field = new stdClass();
            $_field->min = floatval($row->{$field.'_min'});
            $_field->max = floatval($row->{$field.'_max'});
        }

        return $data;
    }

    // --------------------------------------------------------------------

    /**
     * Returns top prices gainers
     *
     * @param int $limit
     *
     * @return array
     */

    public function gainers($limit)
    {
        $data = null;
        $this->db->order_by('price_usd_change_24h', 'DESC');
        $this->db->limit($limit);
	    $this->activeConditions();
	    $this->db->where('price_usd >', 0);
		$this->db->where('market_cap_usd >', 100000);
	    $this->db->where('volume_24h_usd >', 1000);
	    $this->db->where('price_usd_change_24h >', 0);
        $this->readAll($data, array(), 'id,slug,symbol,name,price_usd,price_usd_change_24h,volume_24h_usd,image_small,info');

        return $data;
    }

    // --------------------------------------------------------------------

    /**
     * Returns top prices losers
     *
     * @param $limit
     *
     * @return array
     */

    public function losers($limit)
    {
        $data = null;
        $this->db->order_by('price_usd_change_24h', 'ASC');
        $this->db->limit($limit);
        $this->activeConditions();
	    $this->db->where('price_usd >', 0);
	    $this->db->where('market_cap_usd >', 100000);
	    $this->db->where('volume_24h_usd >', 1000);
	    $this->db->where('price_usd_change_24h <', 0);
        $this->readAll($data, array(), 'id,slug,symbol,name,price_usd,price_usd_change_24h,volume_24h_usd,image_small,info');

        return $data;
    }

    // --------------------------------------------------------------------

    /**
     * Returns all cryptocurrencies with simple list ordered by market cap
     *
     * @return array
     */

    public function listing()
    {
        $this->db->order_by('market_cap_usd', 'DESC');

        $data = null;

        return $this->readAll($data, array('status' => 1), array('slug','name','symbol')) ?
            $data : array();
    }

    // --------------------------------------------------------------------

    /**
     * Returns active cryptocurrencies stats (total market cap, total 24h volume & count)
     *
     * @param int $dominance_length
     *
     * @return stdClass
     */

    public function stats($dominance_length = 4)
    {
        $this->activeConditions();
        $this->db->select('SUM(market_cap_usd) AS total_market_cap_usd, SUM(volume_24h_usd) AS total_volume_24h_usd, COUNT(*) AS total_cryptocurrencies');
        $query  = $this->db->get($this->table_name);
        $stats  = $query->row();

        if(!$stats) {
            $stats = new stdClass();
            $stats->total_market_cap_usd = 0;
            $stats->total_volume_24h_usd = 0;
            $stats->total_cryptocurrencies = 0;
        }

        $this->activeConditions();
        $this->db->select('symbol, market_cap_usd');
        $this->db->order_by('market_cap_usd', 'DESC');
        $query = $this->db->get($this->table_name, $dominance_length);

        $stats->market_cap_percentage = array();

        foreach ($query->result() as $row) {
            $stats->market_cap_percentage[$row->symbol] = $stats->total_market_cap_usd > 0 ?
                ct_number_format($row->market_cap_usd * 100 / $stats->total_market_cap_usd, 2) :
                0;
        }


        return $stats;
    }

    // --------------------------------------------------------------------

    /**
     * Removes one cryptocurrency by slug
     *
     * @param string $slug
     *
     * @return bool
     */

    public function removeBySlug($slug)
    {
        return $this->db->delete($this->table_name, array('slug' => $slug), 1);
    }

	// --------------------------------------------------------------------

	/**
	 * @param string|string[] $data
	 * @param int $limit
	 *
	 * @return array[]
	 */
	public function listSearch($data, $limit = 10) {
		$sql = "SELECT id, slug, symbol, name FROM `$this->table_name`";
		$sql .= ' WHERE status = 1 AND prices_updated > ' . (time() - 24*60*60);

		if (is_string($data)) {
			$q = preg_replace('/[^\d\w -]+/', '', trim($data));
			$q = preg_replace('/\s\s+/', ' ', $q);
			$sql .= " AND (symbol LIKE '%$q%' OR slug LIKE '%$q%' OR name LIKE '%$q%')";
			$sql .= ' ORDER BY market_cap_usd DESC';
			$sql .= " LIMIT $limit;";
		} elseif (is_array($data)) {
			$slugs = [];
			foreach ($data as $slug) {
				$slug = preg_replace('/[^\d\w-]+/', '', $slug);
				if (strlen($slug)) {
					$slugs[] = "'" . $slug . "'";
				}
			}

			if (!empty($slugs)) {
				$sql .= ' AND slug IN (' . implode(',', $slugs) . ');';
			}
		} else {
			return [];
		}

		$query = $this->db->query($sql);
		return $query->result_array();
	}

}
