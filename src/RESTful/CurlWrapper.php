<?php
/**
 * Curl Wrapper for PHP RESTful functions.
 **/

namespace RESTful;

class CurlWrapper
{
    protected $_headerLocation = null;
    private $_contentType;
    protected $_headerProperty = null; 
    protected $_headerResponseCode = null;

    /**
     * Constructor
     *
     * @return void
     */
    function __construct() 
    {

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
    protected function _writeData($path, $data, $method = 'PUT') 
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


    /**
     * Handles non-data write RESTful calls
     *
     * @param String $path Path
     * @param String $method Method
     *
     * @return String Response
     */
    protected function _noData($path, $method = 'GET')
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
     * Returns with Initialized CURL Handler
     *
     * @param String $mode Mode
     * @param string $path
     *
     * @return resource Curl Handler
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

    /**
     * @param string[] $headerArray
     */
    private function _addHeaderAuthentication(&$headerArray) 
    {
        if ($this->_isHeaderAuthentication()) {
            array_push($headerArray, $this->_headerProperty . ': ' . $this->_token);
        }
    }

    /**
     * @param string $location
     */
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
