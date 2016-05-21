<?php

namespace EightPoints\Guzzle;

use EightPoints\Guzzle\Util\GuidGenerator;
use Psr\Http\Message\RequestInterface;

/**
 * Adds WSSE auth headers based on http://www.xml.com/pub/a/2003/12/17/dive.html
 *
 * @version   2.0
 * @since     2013-10
 */
class WsseAuthMiddleware
{
    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @version 1.0
     * @since   2013-10
     *
     * @param string $username
     * @param string $password
     */
    public function __construct($username, $password)
    {
        $this->setUsername($username);
        $this->setPassword($password);

        $this->createdAt = new \DateTime();
    }

    /**
     * @version 1.0
     * @since   2013-10
     *
     * @return  string $username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @version 1.0
     * @since   2013-10
     *
     * @param  string $value
     * @return void
     */
    public function setUsername($value)
    {
        $this->username = $value;
    }

    /**
     * @version 1.0
     * @since   2013-10
     *
     * @return string $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @version 1.0
     * @since   2013-10
     *
     * @param  string $value
     * @return void
     */
    public function setPassword($value)
    {
        $this->password = $value;
    }

    /**
     * @version 4.1
     * @since   2016-05
     *
     * @param  \DateTime $value
     * @return void
     */
    public function setCreatedAt(\DateTime $value)
    {
        $this->createdAt = $value;
    }

    /**
     * @version 4.1
     * @since   2016-05
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Add WSSE auth headers to Request
     *
     * @version 3.0
     * @since   2015-06
     *
     * @return callable
     * @throws \InvalidArgumentException
     */
    public function attach()
    {
        return function (callable $handler) {

            return function (RequestInterface $request, array $options) use ($handler) {

                $createdAt = $this->createdAt->format('c');
                $nonce = $this->generateNonce();
                $digest = $this->generateDigest($nonce, $createdAt, $this->password);

                $xwsse = array(
                    sprintf('Username="%s"', $this->username),
                    sprintf('PasswordDigest="%s"', $digest),
                    sprintf('Nonce="%s"', $nonce),
                    sprintf('Created="%s"', $createdAt)
                );

                $request = $request->withHeader('Authorization', 'WSSE profile="UsernameToken"');
                $request = $request->withHeader('X-WSSE', sprintf('UsernameToken %s', implode(', ', $xwsse)));

                return $handler($request, $options);
            };
        };
    }

    /**
     * @version 1.0
     * @since   2013-10
     *
     * @param string $nonce
     * @param string $createdAt
     * @param string $password
     *
     * @return string
     */
    public function generateDigest($nonce, $createdAt, $password)
    {
        return base64_encode(sha1(base64_decode($nonce) . $createdAt . $password, true));
    }

    /**
     * Generate Nonce (number user once)
     *
     * @version 1.0
     * @since   2013-10
     *
     * @return string
     */
    public function generateNonce()
    {
        $uniqueId = GuidGenerator::generate();

        return base64_encode(hash('sha512', $uniqueId));
    }
}
