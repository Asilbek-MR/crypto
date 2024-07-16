<?php
/**
 * CoinTable
 *
 * A content management system for cryptocurrency related information.
 *
 * This content is released under the CodeCanyon Standard Licenses.
 *
 * Copyright (c) 2017 - 2018, RunCoders
 *
 *
 * @package   CoinTable
 * @link	  https://runcoders.org/cointable
 * @author    RunCoders
 * @license	  https://codecanyon.net/licenses/standard?ref=RunCoders
 * @copyright Copyright (c) 2017 - 2018, RunCoders (https://runcoders.org)
 * @since	  Version 2.0
 *
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Custom_pages_model
 *
 * @package		CoinTable
 * @subpackage	Models
 * @author		RunCoders
 */

class Custom_pages_model extends CT_Model
{
    /**
     * Cleans and validates custom page data
     *
     * @param array $data
     * @param mixed $errors
     *
     * @return bool
     */

    protected function prepareData(&$data, &$errors)
    {
        $test = true;
        $errors = array();

        if(!is_array($data)) return false;

        unset($data['id']);

        if(!empty($data['path']) && !is_numeric($data['path'])){
            $path = $data['path'];
            $clean_path = preg_replace('/[^a-zA-Z\d-]/','',$path);
            $data['path'] = strtolower($clean_path);
        }
        else $data['path'] = null;

        if(empty($data['title']) || !is_array($data['title'])){
            $errors[] = 'title';
            $test = false;
        }
        else {
            $has_title = false;

            foreach ($data['title'] as $lang => $text) {
                if(!empty($text)) {
                    $has_title = true;
                    break;
                }
            }

            if(!$has_title) {
                $errors[] = 'title';
                $test = false;
            }
            else $data['title'] = serialize($data['title']);
        }

        if(!empty($data['subtitle']) && is_array($data['subtitle'])) {
            $data['subtitle'] = serialize($data['subtitle']);
        }
        else $data['subtitle'] = null;

        if(empty($data['content']) || !is_array($data['content'])){
            $errors[] = 'content';
            $test = false;
        }
        else {
            $has_content = false;

            foreach ($data['content'] as $lang => $text) {
                if(!empty($text)) {
                    $has_content = true;
                    break;
                }
            }

            if(!$has_content) {
                $errors[] = 'content';
                $test = false;
            }
            else $data['content'] = serialize($data['content']);
        }

        $data['public'] = empty($data['public']) ? 0 : 1;


        if(empty($data['seo_enabled']))
            $data['seo_enabled'] = empty($data['seo_enabled']) ? 0 : 1;
        if(empty($data['seo_title']))
            $data['seo_title'] = null;
        if(empty($data['seo_description']))
            $data['seo_description'] = null;
        if(empty($data['seo_og_image_url']))
            $data['seo_og_image_url'] = null;
        if(empty($data['seo_twitter_image_url']))
            $data['seo_twitter_image_url'] = null;

        return $test;
    }

    private function unserializeData(&$data)
    {
        if(isset($data['title']))
            $data['title'] = unserialize($data['title']);
        if(isset($data['subtitle']))
            $data['subtitle'] = unserialize($data['subtitle']);
        if(isset($data['content']))
            $data['content'] = unserialize($data['content']);

        if(isset($data['seo_enabled']))
            $data['seo_enabled'] = !!$data['seo_enabled'];
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
