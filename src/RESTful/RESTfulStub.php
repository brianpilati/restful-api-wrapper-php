<?php
/**
 * Stubs the RESTful interface without issuing any cURL requests.
 **/

namespace RESTful;

use RESTful\RESTfulInterface;

class RESTfulStub implements RESTfulInterface
{
    private $_response = null;
    public $_token = null;
    public $_baseURI = null;
    public $_timeout = null;

    function __construct($baseURI = '', $token = '') 
    {
        if (!extension_loaded('curl')) {
            trigger_error('Extension CURL is not loaded.', E_USER_ERROR);
        }

        $this->setBaseURI($baseURI);
        $this->setTimeOut(10);
        $this->setToken($token);
    }

    public function setToken($token) 
    {
        $this->_token = $token;
    }

    public function setBaseURI($baseURI) 
    {
        $baseURI .= (substr($baseURI, -1) == '/' ? '' : '/');
        $this->_baseURI = $baseURI;
    }

    public function setTimeOut($seconds) 
    {
        $this->_timeout = $seconds;
    }

    public function setContentType($contentType) 
    {
        $this->_contentType = $contentType;
    }

    public function setPathConfiguration($pathConfiguration) 
    {
        $this->_pathConfiguration = $pathConfiguration;
    }

    public function setHeaderProperty($headerProperty) 
    {
        $headerProperty = rtrim($headerProperty, ':');
        $this->_headerProperty = $headerProperty;
    }

    public function getHeaderLocation() 
    {
        curl_setopt($CURL, CURLOPT_HEADER, TRUE);
    }

    public function put($path, $data) 
    {
      return $this->_getSetResponse($data);
    }

    public function post($path, $data) 
    {
      return $this->put($path, $data);
    }

    public function patch($path, $data) 
    {
      return $this->put($path, $data);
    }

    public function get($path) 
    {
      return $this->_getGetResponse();
    }

    public function delete($path) 
    {
      return $this->_getDeleteResponse();
    }

    public function setResponse($expectedResponse) 
    {
        $this->_response = $expectedResponse;
    }

    private function _isBaseURIValid() 
    {
      $error = preg_match('/^https:\/\//', $this->_baseURI);
      return new Error(($error == 0 ? true : false), 'RESTful does not support non-ssl traffic. Please try your request again over https.');
    }

    private function _isDataValid($data) 
    {
      if ($data == "" || $data == null) {
        return new Error(true, "Missing data; Perhaps you forgot to send the data.");
      }

      $error = json_decode($data);
      return new Error((is_a($error, 'stdClass') ? false : true), "Invalid data; couldn't parse JSON object, array, or value. Perhaps you're using invalid characters in your key names.");
    }

    private function _getSetResponse($data) 
    {
      $validBaseUriObject = $this->_isBaseURIValid();
      if ($validBaseUriObject->getError()) 
      {
        return $validBaseUriObject->getMessage();
      }

      $validDataObject = $this->_isDataValid($data);
      if ($validDataObject->getError()) 
      {
        return $validDataObject->getMessage();
      }

      return $this->_response;
    }

    private function _getGetResponse() 
    {
      $validBaseUriObject = $this->_isBaseURIValid();
      if ($validBaseUriObject->getError()) {
        return $validBaseUriObject->getMessage();
      }
      return $this->_response;
    }

    private function _getDeleteResponse() { return $this->_getGetResponse(); }
}

Class Error 
{
  private $error = null;
  private $message = null;

  function __construct($error, $message) 
  {
    $this->error = $error;
    $this->message = $message;
  }

  public function getError() 
  {
    return $this->error;
  }

  public function getMessage() 
  {
    return $this->message;
  }
}
