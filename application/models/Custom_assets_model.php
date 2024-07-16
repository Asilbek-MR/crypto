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
 * Class Custom_assets_model
 *
 * @package		CoinTable
 * @subpackage	Models
 * @author		RunCoders
 */

class Custom_assets_model extends CT_Model
{
    /**
     * Cleans and validates custom asset data
     *
     * @param array $data
     * @param mixed $errors
     *
     * @return bool
     */

    protected function prepareData(&$data, &$errors)
    {
        $test = true;
        // erros will contain a list of bad properties
        $errors = array();

        if(!is_array($data)) return false;

        unset($data['id']);

        if(empty($data['name'])) {
            $errors[] = 'name';
            $test = false;
        }

        if(empty($data['symbol'])) {
            $errors[] = 'symbol';
            $test = false;
        }
        else $data['symbol'] = strtoupper($data['symbol']);

        if(!empty($data['slug'])){
            $slug = $data['slug'];
            $regex = '/[^a-zA-Z\d-]/';
            $clean_id = preg_replace($regex,'',$slug);
            $data['slug'] = strtolower($clean_id);
        }
        else {
            $errors[] = 'slug';
            $test = false;
        }


        if(isset($data['circulating_supply'])) {
            $data['circulating_supply'] = abs(intval($data['circulating_supply']));
        }
        else {
            $errors[] = 'circulating_supply';
            $test = false;
        }

        $data['volume_24h_usd'] = empty($data['volume_24h_usd']) || !is_numeric($data['volume_24h_usd']) ?
            0 : floatval($data['volume_24h_usd']);

        $data['total_supply'] = isset($data['total_supply']) ?
            abs(intval($data['total_supply'])) : null;


        if(empty($data['image_thumb'])) {
            $errors[] = 'image_thumb';
            $test = false;
        }

        if(empty($data['image_small'])) {
            $errors[] = 'image_small';
            $test = false;
        }

        if(empty($data['image_large'])) {
            $errors[] = 'image_large';
            $test = false;
        }

        if(isset($data['tracking_multiple'])) {
            $data['tracking_multiple'] = abs(floatval($data['tracking_multiple']));
        }
        else {
            $errors[] = 'tracking_multiple';
            $test = false;
        }

        if(empty($data['tracking_slug'])) {
            $errors[] = 'tracking_slug';
            $test = false;
        }

        $data['page_content'] = empty($data['page_content']) ?
            null : serialize($data['page_content']);

        return $test;
    }

    private function unserializeData(&$data)
    {
        if(isset($data['page_content']))
            $data['page_content'] = unserialize($data['page_content']);
    }

    public function read($id, &$data, $where = array(), $select = null, $cache = false)
    {
        if(parent::read($id, $data, $where, $select, $cache)) {
            $this->unserializeData($data);
            return true;
        }

        return false;
    }

    public function readBy($prop, $value, &$data, $where = array(), $select = null)
    {
        if(parent::readBy($prop, $value, $data, $where, $select)) {
            $this->unserializeData($data);
            return true;
        }

        return false;
    }

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


}
