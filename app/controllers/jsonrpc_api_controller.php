<?php
/**
 * Tatoeba Project, free collaborative creation of multilingual corpuses project
 * Copyright (C) 2010 Allan SIMON <allan.simon@supinfo.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Tatoeba
 * @author   HO Ngoc Phuong Trang <tranglich@gmail.com>
 * @license  Affero General Public License
 * @link     http://tatoeba.org
 */

/*
 * Wiki for this API can be found here:
 * https://github.com/trang/tatoeba-api/wiki/_pages
 */
class JsonrpcApiController extends AppController
{
    
    /**
     * Name of this controller
     * 
     * @var string
     */
    public $name = "JsonrpcApi";
    
    
    /**
     * Models will be loaded as needed by individial methods
     * 
     * @var array
     */
    public $uses = array();
    
    
    /**
     * Add helpers here if needed
     * 
     * @var array
     */
    public $helpers = array('Cache');
    
    
    /**
     * Initialize the jsonrpc component here by listing all the api methods
     * 
     * @var array
     */
    public $components = array(
        'Jsonrpc' => array(
            'listen' => array(
                'search',
                'getSentenceDetails',
                'getCommentDetails',
                'getUsers',
                'getUserDetails',
                'fetchWall',
                'fetchWallThread'
            )
        )
    );

    /**
     * Minify function, compress data
     * 
     * @param string $context     The mapping context (the method name)
     * @param array $jsonArray  The JSON data to compress
     * 
     * @return object compressed JSON data
     */
    private function _minifyCompress($context, $jsonArray)
    {
        
    }
    
    /**
     * Minify function, expand data
     * 
     * @param array $contex     The mapping context (the method name)
     * @param array $jsonArray  The JSON data to expand
     * 
     * @return array expanded JSON data
     */
    private function _minifyExpand($context, $jsonArray)
    {
        foreach($jsonArray as $letter=>$value) {
            if (array_key_exists($letter, $context)) {
                $jsonArray[$context[$letter]] = $jsonArray[$letter];
                unset($jsonArray[$letter]);
            }
        }
    }
    
    
    /**
     * Parent function for seach method
     * 
     * @param $jsonArray array The JSON request
     * 
     * @return array Search results
     */
    public function search($jsonRequest)
    {
        if (empty($jsonRequest['version'])) {
            throw new Exception("Method version not specified.", 0);
        } else if (!function_exists("_search_v{$jsonRequest['version']}")) {
            throw new Exception("Method version does not exist.", 0);
        }
        $version = $jsonRequest['version'];
        
        $context = array(
            'version_1' => array(
                'q' => 'query',
                't' => 'to',
                'f' => 'from',
                'p' => 'page',
                'o' => 'options'
            )
        );
        
        $jsonRequest = $this->_minifyExpand($context["version_{$version}"], $jsonRequest);
        
        call_user_func_array("_search_v{$jsonRequest['version']}", $jsonRequest);
    }
    
    /**
     * Parent function for sentence method
     * 
     * @param $jsonArray array JSON request
     * 
     * @return array Sentences
     */
    public function getSentenceDetails($jsonArray)
    {
        $jsonObject = $this->_minifyExpand("getSentenceDetails",$jsonObject);
    }
    
    
    /**
     * Parent function for comment method
     * 
     * @param $jsonArray array JSON request
     * 
     * @return array A single comment
     */
    public function getComments($jsonArray)
    {
        $jsonObject = $this->_minifyExpand("getComments",$jsonObject);
    }
    
    
    /**
     * Parent function for user profile method
     * 
     * @param $jsonArray array JSON request
     * 
     * @return array Details of a single user
     */
    public function getUserProfile($jsonArray)
    {
        $jsonObject = $this->_minifyExpand("getUserProfile",$jsonObject);
    }
    
    
    /**
     * Parent function for users method
     * 
     * @param $jsonArray array JSON request
     * 
     * @return array A List of users
     */
    public function getUsers($jsonArray)
    {
        $jsonObject = $this->_minifyExpand("getUsers",$jsonObject);
    }
    
    
    /**
     * Parent function for search users method
     * 
     * @param $jsonArray array JSON request
     * 
     * @return array A List of users
     */
    public function searchUsers($jsonArray)
    {
        $jsonObject = $this->_minifyExpand("searchUsers",$jsonObject);
    }
    
    
    /**
     * Parent function for fetch wall method
     * 
     * @param $jsonArray array JSON request
     * 
     * @return array Wall messages with reply structure
     */
    public function fetchWall($jsonArray)
    {
        $jsonObject = $this->_minifyExpand("fetchWall",$jsonObject);
    }
    
    
    /**
     * Parent function for fetch wall thread method
     * 
     * @param $jsonArray array JSON request
     * 
     * @return array Wall messages with reply structure
     */
    public function fetchWallThread($jsonArray)
    {
        $jsonObject = $this->_minifyExpand("fetchWallThread",$jsonObject);
    }
    
    
    /**
     * Parent function for fetch wall replies method
     * 
     * @param $jsonArray array JSON request
     * 
     * @return array Wall messages with reply structure
     */
    public function fetchWallReplies($jsonArray)
    {
        $jsonObject = $this->_minifyExpand("fetchWallReplies",$jsonObject);
    }
    
    
    /**
     * Search sentences
     * 
     * @param  $query    string  The query string
     * @param  $from     string  The source language
     * @param  $to       string  The target language
     * @param  $page     string  Pagination details
     * @param  $options  array   Options for query
     * @version 1
     * 
     * @return array
     */
    private function _search_v1($query, $from, $to, $page, $options)
    {
        // throw an error if the correct arguments are not supplied
        $this->cacheAction = true;
        $results = null;
    }
    
    
    /**
     * Find sentence
     * 
     * @param  $id       int    Id of sentence
     * @param  $options  array  Options for query
     * @version 1
     * 
     * @return array
     */
    private function _getSentenceDetails_v1($id, $options)
    {
        $this->cacheAction = true;
        $results = null;
    }
    
    
    /**
     * Get list of comment
     * 
     * @param  $id  array Id's of comments
     * @version 1
     * 
     * @return array
     */
    private function _getComments_v1($ids)
    {
        $this->cacheAction = true;
        $results = null;
    }
    
    
    /**
     * Find comment
     * 
     * @param  $id  int Id of comment
     * @version 1
     * 
     * @return array
     */
    private function _getCommentDetails_v1($id)
    {
        $this->cacheAction = true;
        $results = null;
    }
    
    
    /**
     * Get list of users or single user
     * 
     * @param  $query   mixed   Either a search string or array of id's
     * @version 1
     * 
     * @return array
     */
    private function _getUsers_v1($query)
    {
        $this->cacheAction = true;
        $results = null;
    }
    
    
    /**
     * Get a User's profile
     * 
     * @param   $query   mixed   Either a search string or array of id's
     * @version 1
     * 
     * @return array
     */
    private function _getUserDetails_v1($query)
    {
        $this->cacheAction = true;
        $results = null;
    }
    
    
    /**
     * Get wall messages
     * 
     * @param  $page     array  Pagination options
     * @param  $options  array  Options for query 
     * @version 1
     * 
     * @return array
     */
    private function _fetchWall_v1($page, $options)
    {
        $this->cacheAction = true;
        $results = null;
    }
    
    /**
     * Get message and replies
     * 
     * @param   $id   Id of wall message
     * @version 1
     * 
     * @return array
     */
    private function _fetchWallThread_v1($id)
    {
        $this->cacheAction = true;
        $results = null;
    }
}

?>