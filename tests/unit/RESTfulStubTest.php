<?php

class RESTfulStubTest extends PHPUnit_Framework_TestCase
{
  /**
   * @var RESTfulStub
   */
  protected $_restfulStub;

  const DEFAULT_URL = 'https://restfulStub.yourcompany.com/';
  const DEFAULT_TOKEN = 'MqL0c8tKCtheLSYcygBrIanhU8Z2hULOFs9OKPdEp';
  const DEFAULT_TIMEOUT = 10;
  const DEFAULT_PATH = 'example/path';
  const DEFAULT_DATA = '{"firstName": "Howdy", "lastName": "Doody"}';
  const DEFAULT_PUSH_DATA = '{"firstName": "1skdSDdksdlisS"}';

  const UPDATED_URI= 'https://myrestfulStub.yourupdatedcompany.com/';
  const UPDATED_TOKEN = 'MqL0c8tEmBeRLSYcygBrIanhU8Z2hULOFs9OKPdEp';
  const UPDATED_TIMEOUT = 30;

  const INSECURE_URL = 'http://insecure.yourcompany.com';
  const INVALID_DATA = '"firstName" "Howdy", "lastName": "Doody" "": ';
  const MISSING_DATA = '';
  const NULL_DATA = null;

  public function setUp() {
      $this->_restfulStub = new RESTfulStub(self::DEFAULT_URL, self::DEFAULT_TOKEN);
  }

  public function testBaseURIInitializationOnInstantiation() {
      $this->assertEquals(self::DEFAULT_TOKEN, $this->_restfulStub->_token);
  }

  public function testSetBaseURI() {
      $actualResponse = $this->_restfulStub->setBaseURI(self::UPDATED_URI);
      $this->assertEquals(null, $actualResponse);

      $this->assertEquals(self::UPDATED_URI, $this->_restfulStub->_baseURI);
  }

  public function testTokenInitializationOnInstantiation() {
      $this->assertEquals(self::DEFAULT_TOKEN, $this->_restfulStub->_token);
  }

  public function testSetToken() {
      $actualResponse = $this->_restfulStub->setToken(self::UPDATED_TOKEN);
      $this->assertEquals(null, $actualResponse);

      $this->assertEquals(self::UPDATED_TOKEN, $this->_restfulStub->_token);
  }

  public function testTimeoutInitializationOnInstantiation() {
      $this->assertEquals(self::DEFAULT_TIMEOUT, $this->_restfulStub->_timeout);
  }

  public function testSetTimeout() {
      $actualResponse = $this->_restfulStub->setTimeout(self::UPDATED_TIMEOUT);
      $this->assertEquals(null, $actualResponse);

      $this->assertEquals(self::UPDATED_TIMEOUT, $this->_restfulStub->_timeout);
  }

  public function testSet() {
      $this->_restfulStub->setResponse(self::DEFAULT_DATA);
      $actualResponse = $this->_restfulStub->set(self::DEFAULT_PATH, self::DEFAULT_DATA);
      $this->assertEquals(self::DEFAULT_DATA, $actualResponse);
  }

  public function testPush() {
      $this->_restfulStub->setResponse(self::DEFAULT_PUSH_DATA);
      $actualResponse = $this->_restfulStub->push(self::DEFAULT_PATH, self::DEFAULT_DATA);
      $this->assertEquals(self::DEFAULT_PUSH_DATA, $actualResponse);
  }

  public function testUpdate() {
      $this->_restfulStub->setResponse(self::DEFAULT_DATA);
      $actualResponse = $this->_restfulStub->update(self::DEFAULT_PATH, self::DEFAULT_DATA);
      $this->assertEquals(self::DEFAULT_DATA, $actualResponse);
  }

  public function testDelete() {
      $actualResponse = $this->_restfulStub->delete(self::DEFAULT_PATH, self::DEFAULT_DATA);
      $this->assertEquals(null, $actualResponse);
  }

  public function testInvalidBaseUri() {
    $restful = new RESTfulStub(self::INSECURE_URL);
    $response = $restful->set(self::DEFAULT_PATH, self::DEFAULT_DATA);
    $this->assertEquals($this->_getErrorMessages('INSECURE_URL'), $response);
  }

  public function testInvalidData() {
    $response = $this->_restfulStub->set(self::DEFAULT_PATH, self::INVALID_DATA);
    $this->assertEquals($this->_getErrorMessages('INVALID_JSON'), $response);
  }

  public function testMissingData() {
    $response = $this->_restfulStub->set(self::DEFAULT_PATH, self::MISSING_DATA);
    $this->assertEquals($this->_getErrorMessages('NO_DATA'), $response);
  }

  public function testNullData() {
    $response = $this->_restfulStub->set(self::DEFAULT_PATH, self::NULL_DATA);
    $this->assertEquals($this->_getErrorMessages('NO_DATA'), $response);
  }

  private function _getErrorMessages($errorCode) {
    $errorMessages = Array(
      'INSECURE_URL' => 'RESTful does not support non-ssl traffic. Please try your request again over https.',
      'INVALID_JSON' => "Invalid data; couldn't parse JSON object, array, or value. Perhaps you're using invalid characters in your key names.",
      'NO_DATA' => 'Missing data; Perhaps you forgot to send the data.'
    );

    return $errorMessages[$errorCode];
  }
}
