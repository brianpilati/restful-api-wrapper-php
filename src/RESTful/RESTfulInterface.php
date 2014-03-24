<?php
/**
 * Interface for PHP RESTful functions.
 **/

namespace RESTful;

interface RESTfulInterface 
{
      public function setToken($token);
      public function setBaseURI($baseURI);
      public function setTimeOut($seconds);
      public function put($path, $data);
      public function post($path, $data);
      public function patch($path, $data);
      public function get($path);
      public function delete($path);
}
