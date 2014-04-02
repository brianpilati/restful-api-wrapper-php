<?php

include_once __DIR__ . '/mocks/CurlMocks.php';

const DEFAULT_DATA = '{"firstName": "Howdy", "lastName": "Doody"}';
const DEFAULT_DATA_PATH_CONFIG = '{"firstName": "Brian", "lastName": "Orton"}';
$GLOBALS['extensionLoaded'] = true;

class RESTfulTest extends \PHPUnit_Framework_TestCase 
{
    /**
    * @var RESTfulStub
    */
    protected $_RESTful;

    const DEFAULT_URL = 'https://restfulStub.yourcompany.com/';
    const DEFAULT_TOKEN = 'MqL0';
    const DEFAULT_TIMEOUT = 10;
    const DEFAULT_PATH = 'example/path';
    const DEFAULT_ERROR_PATH = 'error';
    const DEFAULT_POST_DATA = '{"firstName": "Jake"}';
    const DEFAULT_PATCH_DATA = '{"lastName": "Baker-Boy"}';

    public function setUp() 
    {
        $this->_RESTful = new \RESTful\RESTful(self::DEFAULT_URL, self::DEFAULT_TOKEN);
    }

    public function testGet() 
    {
        $actualResponse = $this->_RESTful->get(self::DEFAULT_PATH);
        $this->assertEquals(DEFAULT_DATA, $actualResponse);
    }

    public function testGetPathConfiguration() 
    {
        $RESTful = new \RESTful\RESTful(self::DEFAULT_URL, self::DEFAULT_TOKEN);
        $RESTful->setPathConfiguration('.json?auth=');
        $actualResponse = $RESTful->get(self::DEFAULT_PATH, DEFAULT_DATA_PATH_CONFIG);
        $this->assertEquals(DEFAULT_DATA_PATH_CONFIG, $actualResponse);
    }

    public function testDelete() 
    {
        $actualResponse = $this->_RESTful->delete(self::DEFAULT_PATH);
        $this->assertEquals(null, $actualResponse);
    }

    public function testPut() 
    {
        $actualResponse = $this->_RESTful->put(self::DEFAULT_PATH, DEFAULT_DATA);
        $this->assertEquals(DEFAULT_DATA, $actualResponse);
    }

    public function testPost() 
    {
        $actualResponse = $this->_RESTful->post(self::DEFAULT_PATH, self::DEFAULT_POST_DATA);
        $this->assertEquals(self::DEFAULT_POST_DATA, $actualResponse);
    }

    public function testPatch() 
    {
        $actualResponse = $this->_RESTful->patch(self::DEFAULT_PATH, self::DEFAULT_PATCH_DATA);
        $this->assertEquals(self::DEFAULT_PATCH_DATA, $actualResponse);
    }

    /**
    * @dataProvider headerAuthenticationProvider
    */
    public function testPatchWithHeaderAuthentication($headerProperty) 
    {
        $RESTful = new \RESTful\RESTful(self::DEFAULT_URL, self::DEFAULT_TOKEN);
        $RESTful->setHeaderProperty($headerProperty);
        $RESTful->setToken('MqL0af3');
        $actualResponse = $RESTful->patch('authentication', DEFAULT_DATA_PATH_CONFIG);
        $this->assertEquals(DEFAULT_DATA_PATH_CONFIG, $actualResponse);
    }

    public function testWriteDataError() 
    {
        $actualResponse = $this->_RESTful->patch(self::DEFAULT_ERROR_PATH, DEFAULT_DATA);
        $this->assertEquals(null, $actualResponse);
    }

    public function testNoDataError() 
    {
        $actualResponse = $this->_RESTful->get(self::DEFAULT_ERROR_PATH);
        $this->assertEquals(null, $actualResponse);
    }

    /**
    * @expectedException Exception
    */
    public function testNoCurlError() 
    {
        $GLOBALS['extensionLoaded'] = false;
        $RESTful = new \RESTful\RESTful(self::DEFAULT_URL, self::DEFAULT_TOKEN);
    }

    public function testGetHeaderLocation()
    {
        $this->_RESTful->patch(self::DEFAULT_PATH, self::DEFAULT_PATCH_DATA);
        $actualResponse = $this->_RESTful->getHeaderLocation();
        $this->assertEquals('https://www.mysweetexample.com', $actualResponse);
    }

    public function testGetHeaderLocationNull()
    {
        $actualResponse = $this->_RESTful->getHeaderLocation();
        $this->assertNull($actualResponse);
    }

    public function testGetHeaderResponseCode()
    {
        $this->_RESTful->patch(self::DEFAULT_PATH, self::DEFAULT_PATCH_DATA);
        $actualResponse = $this->_RESTful->getHeaderResponseCode();
        $this->assertEquals(200, $actualResponse);
    }

    public function testGetHeaderResponseCodeNull()
    {
        $actualResponse = $this->_RESTful->getHeaderResponseCode();
        $this->assertNull($actualResponse);
    }

    /************ Providers ************/

    public function headerAuthenticationProvider() 
    {
        return array (
            array('Authentication'),
            array('Authentication:')
        );
    }
}
