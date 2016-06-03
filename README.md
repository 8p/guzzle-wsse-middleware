Guzzle WSSE Middleware<br />[![Latest Stable Version](https://poser.pugx.org/eightpoints/guzzle-wsse-middleware/v/stable.png)](https://packagist.org/packages/eightpoints/guzzle-wsse-middleware) [![Total Downloads](https://poser.pugx.org/eightpoints/guzzle-wsse-middleware/downloads.png)](https://packagist.org/packages/eightpoints/guzzle-wsse-middleware) [![License](https://poser.pugx.org/eightpoints/guzzle-wsse-middleware/license.svg)](https://packagist.org/packages/eightpoints/guzzle-wsse-middleware)
==================
This middleware integrates [WSSE][1] funtionality into Guzzle, a PHP framework for building RESTful web service clients.


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
        "eightpoints/guzzle-wsse-middleware": "^4.1.1"
    }
}
```


Usage
-----
``` php
<?php 

$wsse = new \EightPoints\Guzzle\WsseAuthMiddleware($username, $password);

# Optional: Set createdAt by a expression (if not, current time will be used automatically)
# http://php.net/manual/en/datetime.formats.relative.php
# Useful if there is a small difference of time between client and server
# DateTime object will be regenerated for every request
$wsse->setCreatedAtTimeExpression('-10 seconds');

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
This middleware is licensed under the MIT License - see the LICENSE file for details

[1]: http://www.xml.com/pub/a/2003/12/17/dive.html
[2]: http://guzzlephp.org/
[3]: https://getcomposer.org/
[4]: http://twitter.com/floeH
[5]: https://github.com/8p/guzzle-wsse-middleware/contributors
