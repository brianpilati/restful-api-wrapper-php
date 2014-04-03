<?php

namespace RESTful;

class RESTful extends CurlWrapper implements RESTfulInterface 
{
    /**
     * Constructor
     *
     * @param String $baseURI Base URI
     * @param String $token token 
     *
     * @return void
     */
    function __construct($baseURI, $token = '') 
    {
        parent::__construct();
        if (!extension_loaded('curl')) {
            throw new \Exception('Extension CURL is not loaded.');
        }

        $this->setBaseURI($baseURI);
        $this->setToken($token);
        $this->setTimeOut(10);
        $this->setContentType('application/json');
    }

    /**
     * Sets Token
     *
     * @param String $token Token
     *
     * @return void
     */
    public function setToken($token) 
    {
        $this->_token = $token;
    }

    /**
       * Sets Base URI, ex: http://yourcompany.com/<path>/<to>/<resource>
     *
     * @param String $baseURI Base URI
     *
     * @return void
     */
    public function setBaseURI($baseURI) 
    {
        $baseURI .= (substr($baseURI, -1) == '/' ? '' : '/');
        $this->_baseURI = $baseURI;
    }

    /**
     * Sets REST call timeout in seconds
     *
     * @param Integer $seconds Seconds to timeout
     *
     * @return void
     */
    public function setTimeOut($seconds) 
    {
        $this->_timeout = $seconds;
    }

    /**
     * Sets REST call Content-Type
     *
     * @param String $contentType Content-Type
     *
     * @return void
     */
    public function setContentType($contentType) 
    {
        $this->_contentType = $contentType;
    }

    /**
     * Sets REST call Path Configuration
     *
     * @param String $pathConfiguration Path Configuration
     *
     * @return void
     */
    public function setPathConfiguration($pathConfiguration) 
    {
        $this->_pathConfiguration = $pathConfiguration;
    }

    /**
     * Sets REST call Header Authentication Property
     *
     * @param String $headerProperty Header Property
     *
     * @return void
     */
    public function setHeaderProperty($headerProperty) 
    {
        $headerProperty = rtrim($headerProperty, ':');
        $this->_headerProperty = $headerProperty;
    }

    /**
     * Get REST call Header Location Property
     *
     * @return "string"
     */
    public function getHeaderLocation() 
    {
        return $this->_headerLocation;
    }

    /**
     * Get REST call Response Body 
     *
     * @return "string"
     */
    public function getResponseBody() 
    {
        return $this->_responseBody;
    }

    /**
     * Get REST call Header Status Code Property
     *
     * @return "string"
     */
    public function getHeaderResponseCode() 
    {
        return $this->_headerResponseCode;
    }

    /**
     * Writing data into RESTful with a PUT request
     * HTTP 200: Ok
     *
     * @param String $path Path
     * @param Mixed  $data Data
     *
     * @return String Response
     */
    public function put($path, $data) 
    {
      return $this->_writeData($path, $data, 'PUT');
    }

    /**
     * Pushing data into RESTful with a POST request
     * HTTP 200: Ok
     *
     * @param String $path Path
     * @param Mixed  $data Data
     *
     * @return String Response
     */
    public function post($path, $data) 
    {
      return $this->_writeData($path, $data, 'POST');
    }

    /**
     * Updating data into RESTful with a PATH request
     * HTTP 200: Ok
     *
     * @param String $path Path
     * @param Mixed  $data Data
     *
     * @return String Response
     */
    public function patch($path, $data) 
    {
      return $this->_writeData($path, $data, 'PATCH');
    }

    /**
     * Reading data from RESTful
     * HTTP 200: Ok
     *
     * @param String $path Path
     *
     * @return String Response
     */
    public function get($path) 
    {
        return $this->_noData($path);
    }

    /**
     * Deletes data from RESTful
     * HTTP 204: Ok
     *
     * @param type $path Path
     *
     * @return String Response
     */
    public function delete($path) 
    {
        return $this->_noData($path, 'DELETE');
    }
}
