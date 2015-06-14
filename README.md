Guzzle WSSE Plugin [![Latest Stable Version](https://poser.pugx.org/eightpoints/guzzle-wsse-plugin/v/stable.png)](https://packagist.org/packages/eightpoints/guzzle-wsse-plugin) [![Total Downloads](https://poser.pugx.org/eightpoints/guzzle-wsse-plugin/downloads.png)](https://packagist.org/packages/eightpoints/guzzle-wsse-plugin) [![License](https://poser.pugx.org/eightpoints/guzzle-wsse-plugin/license.svg)](https://packagist.org/packages/eightpoints/guzzle-wsse-plugin)
==================
This plugin integrates [WSSE][1] funtionality into Guzzle, a PHP framework for building RESTful web service clients.


Requirements
------------
 - PHP 5.5 or above
 - [Guzzle PHP Framework][2]

 
Installation
------------
Using [composer][3]:

``` json
{
    "require": {
        "eightpoints/guzzle-wsse-plugin": "~3.0"
    }
}
```


Usage
-----
``` php
<?php 

$wsse  = new \EightPoints\Guzzle\WsseAuthMiddleware($username, $password);
$stack = \GuzzleHttp\HandlerStack::create();

// Add the wsse middleware to the handler stack.
$stack->push($wsse->attach());

$client   = new \GuzzleHttp\Client(['handler' => $stack]);
$response = $client->get('http://www.8points.de');
```


Authors
-------
 - Florian Preusner ([Twitter][4])

See also the list of [contributors][5] who participated in this project.


License
-------
This plugin is licensed under the MIT License - see the LICENSE file for details

[1]: http://www.xml.com/pub/a/2003/12/17/dive.html
[2]: http://guzzlephp.org/
[3]: https://getcomposer.org/
[4]: http://twitter.com/floeH
[5]: https://github.com/8p/guzzle-wsse-plugin/contributors
