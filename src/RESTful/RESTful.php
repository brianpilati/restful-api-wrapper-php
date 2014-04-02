<?php

namespace RESTful;

use RESTful\RESTfulInterface;

class RESTful implements RESTfulInterface 
{
    private $_baseURI;
    private $_timeout;
    private $_token;
    private $_contentType;
    private $_pathConfiguration = '';
    private $_headerProperty = null; 
    private $_headerLocation = null;
    private $_headerResponseCode = null;

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
     * Returns with the normalized JSON absolute path
     *
     * @param String $path to data
     */
    private function _getJsonPath($path) 
    {
        $url = $this->_baseURI;
        $path = ltrim($path, '/');
        $auth = ($this->_isAuthInPath()) ? $this->_token : '';
        $pathConfiguration = ($this->_isPathConfiguration()) ? $this->_pathConfiguration : '/';
        return $url . $path . $pathConfiguration . $auth;
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

    /**
     * Returns with Initialized CURL Handler
     *
     * @param String $mode Mode
     *
     * @return CURL Curl Handler
     */
    private function _getCurlHandler($path, $mode) 
    {
        $url = $this->_getJsonPath($path);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->_timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->_timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $mode);
        curl_setopt($ch, CURLOPT_HEADER, true);
        return $ch;
    }

    /**
     * Handles non-data write RESTful calls
     *
     * @param String $path Path
     * @param String $method Method
     *
     * @return String Response
     */
    private function _noData($path, $method = 'GET')
    {
        try {
            $ch = $this->_getCurlHandler($path, $method);
            $return = $this->_parseCurlResponse(curl_exec($ch));
            curl_close($ch);
        } catch (\Exception $e) {
            $return = null;
        }
        return $return;
    }

    /**
     * Handles data write RESTful calls
     *
     * @param String $path Path
     * @param Mixed  $data Data
     * @param String $method Method
     *
     * @return String Response
     */
    private function _writeData($path, $data, $method = 'PUT') 
    {
        $jsonData = json_encode($data);
        $header = array(
            'Content-Type: ' . $this->_contentType,
            'Content-Length: ' . strlen($jsonData)
        );
        $this->_addHeaderAuthentication($header);

        try {
            $ch = $this->_getCurlHandler($path, $method);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            $return = $this->_parseCurlResponse(curl_exec($ch));
            curl_close($ch);
        } catch (\Exception $e) {
            $return = null;
        }
        return $return;
    }

    private function _parseCurlResponse($content)
    {
        list($headers, $body) = explode("\r\n\r\n", $content, 2);
        $this->_parseCurlHeader($headers);
        return $body;
    }

    private function _parseCurlHeader($headers)
    {
        $headers = explode("\n", $headers);
        foreach($headers as $header) {
            preg_match("/^(.*):\s(.*)/", $header, $property);
            if (count($property) === 3) {
                if($property[1] === 'Location') {
                    $this->_setHeaderLocation($property[2]);
                }
            } else {
                $this->_setHeaderResponseCode($header);
            }
        }
    }

    private function _isAuthInPath()
    {
        return ($this->_token !== '' && !$this->_isHeaderAuthentication());
    }

    private function _isPathConfiguration()
    {
        return ($this->_pathConfiguration !== '' && !$this->_isHeaderAuthentication());
    }

    private function _isHeaderAuthentication() 
    {
        return $this->_headerProperty !== null;
    }

    private function _addHeaderAuthentication(&$headerArray) 
    {
        if ($this->_isHeaderAuthentication()) {
            array_push($headerArray, $this->_headerProperty . ': ' . $this->_token);
        }
    }

    private function _setHeaderLocation($location)
    {
        $this->_headerLocation = $location;
    }

    private function _setHeaderResponseCode($response)
    {
        preg_match("/.*\s(\d{3})\s(.*)/", $response, $responseCode);
        $this->_headerResponseCode = $responseCode[1];
    }
}
