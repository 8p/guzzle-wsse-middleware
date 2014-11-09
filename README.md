README
======
This plugin integrates [WSSE][1] funtionality into Guzzle, a PHP framework for building RESTful web service clients.


Requirements
------------
 - PHP 5.3.2 or above (at least 5.3.4 recommended to avoid potential bugs)
 - [Guzzle PHP Framework][2]

 
Installation
------------
Using [composer][3]:

``` json
{
    "require": {
        "eightpoints/guzzle-wsse-plugin": "~2.0"
    }
}
```

Usage
-----
``` php
<?php 

$wsse   = new EightPoints\Guzzle\Plugin\WsseAuthPlugin("username", "password");
$client = new Guzzle\Service\Client("http://example.com");
$client->getEmitter()->attach($wsse);
$response = $client->get("/someapi")->send();
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