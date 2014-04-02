restful-API-wrapper-php
=======================

[![Build Status](https://drone.io/github.com/brianpilati/restful-api-wrapper-php/status.png)](https://drone.io/github.com/brianpilati/restful-api-wrapper-php/latest)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/brianpilati/restful-api-wrapper-php/badges/quality-score.png?s=15dcc5b665a72e9d2b930570c786c8dd79a120ff)](https://scrutinizer-ci.com/g/brianpilati/restful-api-wrapper-php/)

A simple php RESTful api wrapper.

RESTful API Wrapper PHP Stub
============================
A RESTful API Wrapper Stub has been created to allow for integration with phpunit without actually interacting with an API.

Code example
------------

```
  $restful = new RESTful('https://<yourcompany>.com/<path>/<to>/<resource>', '<token>');
  $restful->set($path, $value);
}
```

Default Content-Type
--------------------
The default Content-Type is ```application/json```

To change the default Content-Type
```
  $restful->setContentType(<newContentType>);
```

Default URL Path Configuration and Authentication
-------------------------------------------------
The default URL Path is ```<baseURI>/<paht_to_data>/<token>/```

If you need to add a special configuration to the URL path
```
  $restful->setPathConfiguration(<newPathConfiguration>);
```

#### Examples

Firebase needs a ```.json?auth=``` format.
```
  $restful = new RESTful('https://<yourcompany>.com/<path>/<to>/<resource>', '<token>');
  $restful->setPathConfiguration('.json?auth=');
  $restful->set($path, $value);
```

Header Authentication
---------------------
To set the authentication in the header request

```
  $restful->setHeaderProperty('<name_of_property>');
```

The full header property will be

```
  '<name_of_property>: <token>'
```

List of Library Methods
=======================

```
delete($path) 
get($path) 
getHeaderLocation() 
getHeaderResponseCode() 
patch($path, $data) 
post($path, $data) 
put($path, $data) 
setBaseURI($baseURI) 
setContentType($contentType) 
setHeaderProperty($headerProperty) 
setPathConfiguration($pathConfiguration) 
setTimeOut($seconds) 
setToken($token)
```

Unit Tests
==========

All the unit tests are found in the "/tests" directory. Due to the usage of an interface, the tests must run in isolation.

The RESTfulStub tests can be executed by running the following command:

All Tests
---------

```
$ phpunit --bootstrap tests/bootstrap.php --configuration tests/phpunit.xml --testsuite restful 
```

Test Groups
-----------
```
$ phpunit --bootstrap tests/bootstrap.php --configuration tests/phpunit.xml --group <groupName> 
```

Single Test 
-----------
```
$ phpunit --bootstrap tests/bootstrap.php --configuration tests/phpunit.xml tests/unit/<file_name.php>
```

PHPunit tests example
---------------------

```
<?php
  require_once '<path>/lib/RESTfulInterface.php';
  require_once '<path>/lib/RESTfulStub.php';

  class MyClass extends PHPUnit_Framework_TestCase
  {
    public function testSetRESTfulValue() {
      $myClass = new MyClass();
      $restfulStub = new RESTfulStub($uri, $token);
      $myClass->setRESTFulValue($path, $value);
    }
  }
?>
```

Based on heavily (okay, practically copied) on https://github.com/ktamas77/firebase-php. His code is simply amazing! I helped him write the tests and the test stub. I reached out to him to fork and abstract the code but I never received a response.

-- Firebase PHP Class & Client Library -- 
=========================================
https://github.com/ktamas77/firebase-php
@author Tamas Kalman <ktamas77@gmail.com>
