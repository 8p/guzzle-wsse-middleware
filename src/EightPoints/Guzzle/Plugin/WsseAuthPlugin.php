<?php

namespace EightPoints\Guzzle\Plugin;

use       GuzzleHttp\Event\BeforeEvent,
          GuzzleHttp\Event\SubscriberInterface;

/**
 * Adds WSSE auth headers based on http://www.xml.com/pub/a/2003/12/17/dive.html
 *
 * @package   EightPoints\Guzzle\Plugin
 *
 * @copyright 8points IT
 * @author    Florian Preusner
 *
 * @version   2.0
 * @since     2013-10
 */
class WsseAuthPlugin implements SubscriberInterface {

    /**
     * @var string $username
     */
    private $username;

    /**
     * @var string $password
     */
    private $password;

    /**
     * @var string $createdAt
     */
    private $createdAt;

    /**
     * @var string $digest
     */
    private $digest;

    /**
     * @var string $nonce
     */
    private $nonce;


    /**
     * Constructor
     *
     * @author  Florian Preusner
     * @version 1.0
     * @since   2013-10
     *
     * @param   string $username
     * @param   string $password
     */
    public function __construct($username, $password) {

        $this->createdAt = date('r');

        $this->setUsername( $username);
        $this->setPassword( $password);
        $this->setNonce(    $this->generateNonce());
        $this->setDigest(   $this->generateDigest($this->nonce, $this->createdAt, $this->password));
    } // end: __construct()

    /**
     * Get Username
     *
     * @author  Florian Preusner
     * @version 1.0
     * @since   2013-10
     *
     * @return  string $username
     */
    public function getUsername() {

        return $this->username;
    } // end: getUsername()

    /**
     * Set Username
     *
     * @author  Florian Preusner
     * @version 1.0
     * @since   2013-10
     *
     * @param   string $value
     * @return  void
     */
    public function setUsername($value) {

        $this->username = $value;
    } // end: setUsername()

    /**
     * Get Password
     *
     * @author  Florian Preusner
     * @version 1.0
     * @since   2013-10
     *
     * @return  string $password
     */
    public function getPassword() {

        return $this->password;
    } // end: getPassword()

    /**
     * Set Password
     *
     * @author  Florian Preusner
     * @version 1.0
     * @since   2013-10
     *
     * @param   string $value
     * @return  void
     */
    public function setPassword($value) {

        $this->password = $value;
    } // end: setPassword()

    /**
     * Get created datetime
     *
     * @author  Florian Preusner
     * @version 1.0
     * @since   2013-10
     *
     * @return  string
     */
    public function getCreatedAt() {

        return $this->createdAt;
    } // end: getCreatedAt()

    /**
     * Get Nonce
     *
     * @author  Florian Preusner
     * @version 1.0
     * @since   2013-10
     *
     * @return  string $nonce
     */
    public function getNonce() {

        return $this->nonce;
    } // end: getNonce()

    /**
     * Set Nonce
     *
     * @author  Florian Preusner
     * @version 1.0
     * @since   2013-10
     *
     * @param   string $value
     * @return  void
     */
    public function setNonce($value) {

        $this->nonce = $value;
    } // end: setNonce()

    /**
     * Get Digest
     *
     * @author  Florian Preusner
     * @version 1.0
     * @since   2013-10
     *
     * @return  string $digest
     */
    public function getDigest() {

        return $this->digest;
    } // end: getDigest()

    /**
     * Set Digest
     *
     * @author  Florian Preusner
     * @version 1.0
     * @since   2013-10
     *
     * @param   string $value
     */
    public function setDigest($value) {

        $this->digest = $value;
    } // end: setDigest()

    /**
     * {@inheritdoc}
     *
     * @author  Florian Preusner
     * @version 2.0
     * @since   2013-10
     */
    public function getEvents() {

        return ['before' => ['onBefore']];
    } // end: getEvents

    /**
     * Add WSSE auth headers to Request
     *
     * @author  Florian Preusner
     * @version 2.0
     * @since   2013-10
     *
     * @param   BeforeEvent $event
     *
     * @return  void
     */
    public function onBefore(BeforeEvent $event) {

        $request = $event->getRequest();
        $xwsse   = array(
            sprintf('Username="%s"', $this->username),
            sprintf('PasswordDigest="%s"', $this->digest),
            sprintf('Nonce="%s"', $this->nonce),
            sprintf('Created="%s"', $this->createdAt)
        );

        $request->addHeader('Authorization', 'WSSE profile="UsernameToken"');
        $request->addHeader('X-WSSE', sprintf('UsernameToken %s', implode(', ', $xwsse)));
    } // end: onBefore()

    /**
     * Generate Digest
     *
     * @author  Florian Preusner
     * @version 1.0
     * @since   2013-10
     *
     * @param   string $nonce
     * @param   string $createdAt
     * @param   string $password
     *
     * @return  string
     */
    public function generateDigest($nonce, $createdAt, $password) {

        return base64_encode(sha1(base64_decode($nonce) . $createdAt . $password, true));
    } // end: generateDigest()

    /**
     * Generate Nonce (number user once)
     *
     * @author  Florian Preusner
     * @version 1.0
     * @since   2013-10
     *
     * @return  string
     */
    public function generateNonce() {

        return hash('sha512', uniqid(true));
    } // end: generateNonce()
} // end: WsseAuthPlugin