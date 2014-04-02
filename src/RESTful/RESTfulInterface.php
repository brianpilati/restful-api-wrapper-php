<?php
/**
 * Interface for PHP RESTful functions.
 **/

namespace RESTful;

interface RESTfulInterface 
{
    /**
     * @return void
     */
    public function setToken($token);

    /**
     * @return void
     */
    public function setBaseURI($baseURI);

    /**
     * @return void
     */
    public function setTimeOut($seconds);

    /**
     * @return void
     */
    public function setContentType($contentType);

    /**
     * @return void
     */
    public function setPathConfiguration($pathConfiguration);

    /**
     * @return void
     */
    public function setHeaderProperty($headerProperty);

    /**
     * @return string
     */
    public function getHeaderLocation();

    /**
     * @return string
     */
    public function put($path, $data);

    /**
     * @return string
     */
    public function post($path, $data);

    /**
     * @return string
     */
    public function patch($path, $data);

    /**
     * @return string
     */
    public function get($path);

    /**
     * @return string
     */
    public function delete($path);
}
