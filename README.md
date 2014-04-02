restful-API-wrapper-php
=======================

[![Build Status](https://drone.io/github.com/brianpilati/restful-api-wrapper-php/status.png)](https://drone.io/github.com/brianpilati/restful-api-wrapper-php/latest)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/brianpilati/restful-api-wrapper-php/badges/quality-score.png?s=15dcc5b665a72e9d2b930570c786c8dd79a120ff)](https://scrutinizer-ci.com/g/brianpilati/restful-api-wrapper-php/)

[![Code Coverage](https://scrutinizer-ci.com/g/brianpilati/restful-api-wrapper-php/badges/coverage.png?s=a5a439760442ca1b216163a432a1cb3243f53f0c)](https://scrutinizer-ci.com/g/brianpilati/restful-api-wrapper-php/)

A simple php RESTful api wrapper.

Code example
------------

```
  $restful = new RESTful('https://<yourcompany>.com/<path>/<to>/<resource>', '<token>');
  $restful->post($path, $data);
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
  $restful->post($path, $data);
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

All the unit tests are found in the "/tests" directory. 

The RESTful tests can be executed by running the following command:

All Tests
---------

```
$ phpunit 
```

Test Groups
-----------
```
$ phpunit --group <groupName> 
```

Single Test 
-----------
```
$ phpunit tests/unit/<file_name.php>
```

I started with the firebase-php library (https://github.com/ktamas77/firebase-php). Tamas' code is simply amazing! I helped him write the tests and the test stub. I reached out to him to fork and abstract the code but I never received a response.

-- Firebase PHP Class & Client Library -- 
=========================================
https://github.com/ktamas77/firebase-php
@author Tamas Kalman <ktamas77@gmail.com>
