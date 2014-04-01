<?php

namespace RESTful;

const CURLOPT_URL = 10002;
const CURLOPT_CUSTOMREQUEST = 10036;
const CURLOPT_CONNECTTIMEOUT = 13;
const CURLOPT_HTTPHEADER= 10023;
const CURLOPT_POSTFIELDS = 10015;

function extension_loaded() {
    return $GLOBALS['extensionLoaded'];
}

function curl_init()
{
    return new CurlMock();
}

function curl_setopt($ch, $opt, $val)
{
    if ($opt === CURLOPT_URL) {
        $ch->setUrl($val);
    } else if ($opt === CURLOPT_CUSTOMREQUEST) {
        $ch->setMethod($val);
    } else if ($opt === CURLOPT_CONNECTTIMEOUT) {
        \PHPUNIT_Framework_TestCase::assertEquals($val, 10);
    } else if ($opt === CURLOPT_POSTFIELDS) {
        $ch->setData($val);
    } else if ($opt === CURLOPT_HTTPHEADER) {
        $ch->setContentType($val[0]);
        $ch->setContentLength($val[1]);
        if (count($val) === 3) {
            $ch->setHeaderProperty($val[2]);
        }
    }
}

function curl_exec($ch)
{
    if (preg_match("/example\/path\/MqL0/", $ch->getUrl()) && $ch->getMethod() === "GET") {
        return DEFAULT_DATA;
    } else if (preg_match("/example\/path\/MqL0/", $ch->getUrl()) && $ch->getMethod() === "DELETE") {
        return null;
    } else if (preg_match("/error/", $ch->getUrl()) && ($ch->getMethod() === "PATCH" || $ch->getMethod() === 'GET')) {
        throw new \Exception('Bad Request');
    } else if (preg_match("/authentication/", $ch->getUrl()) && $ch->getMethod() === "PATCH") {
        $headerProperty = 'Authentication: MqL0af3';
        if ($ch->getHeaderProperty() !== $headerProperty) {
            return 'Header Property is wrong. Expected: ' . $headerProperty . ' Actual: ' . $ch->getHeaderProperty();
        }
        return $ch->getData();
    } else if (preg_match("/example\/path\/MqL0/", $ch->getUrl()) && $ch->getMethod() === "PATCH") {
        return $ch->getData();
    } else if (preg_match("/example\/path\/MqL0/", $ch->getUrl()) && $ch->getMethod() === "POST") {
        return $ch->getData();
    } else if (preg_match("/example\/path\/MqL0/", $ch->getUrl()) && $ch->getMethod() === "PUT") {
        return $ch->getData();
    } else if (preg_match("/example\/path.json\?auth=MqL0/", $ch->getUrl()) && $ch->getMethod() === "GET") {
        return DEFAULT_DATA_PATH_CONFIG;
    }
}

function curl_close($ch)
{
}

class CurlMock 
{
    private $url;
    private $method;
    private $contentType;
    private $contentLength;
    private $headerProperty;
    private $data;

    function __construct()
    {
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    public function getContentType()
    {
        return $this->contentType;
    }

    public function setContentLength($contentLength)
    {
        $this->contentLength = $contentLength;
    }

    public function getContentLength()
    {
        return $this->contentLength;
    }

    public function setHeaderProperty($headerProperty)
    {
        $this->headerProperty = $headerProperty;
    }

    public function getHeaderProperty()
    {
        return $this->headerProperty;
    }

    public function setData($data)
    {
        $this->data = preg_replace('/\\\"/', '"', $data);
        $this->data = preg_replace('/^"/', '', $this->data);
        $this->data = preg_replace('/"$/', '', $this->data);
    }

    public function getData()
    {
        return $this->data;
    }
}

