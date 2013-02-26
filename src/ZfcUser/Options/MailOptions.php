<?php

namespace ZfcUser\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Options for class for the email system.
 *
 * @author Tom Oram <tom@x2k.co.uk>
 */
class MailOptions extends AbstractOptions
{
    /**
     * The name of the class or service which generates the email messages.
     *
     * @param string
     */
    protected $messageFetcherClass = 'ZfcUser\Mail\ZendMail';

    /**
     * Any additional options for the fetcher class.
     *
     * @param array
     */
    protected $messageFetcherOptions = array();

    /**
     * The name of the class or service which sends emails.
     *
     * @param string
     */
    protected $transportClass = 'ZfcUser\Mail\ZendMail';

    /**
     * Any additional options for the transport class.
     *
     * @param array
     */
    protected $transportOptions = array();

    /**
     * The email address which message are sent from.
     *
     * @param string
     */
    protected $fromAddress = '';

    /**
     * The subject of a forgotten password email.
     *
     * Accepted values: string
     */
    protected $forgottenPasswordSubject = 'Forgotten password request';

    /**
     * The subject of a account validation emails.
     *
     * Accepted values: string
     */
    protected $accountValidationSubject = 'Validate your account';

    /**
     * Gets the value for messageFetcherClass.
     *
     * @return string
     */
    public function getMessageFetcherClass()
    {
        return $this->messageFetcherClass;
    }

    /**
     * Sets the value for messageFetcherClass.
     *
     * @param string $messageFetcherClass
     */
    public function setMessageFetcherClass($messageFetcherClass)
    {
        $this->messageFetcherClass = $messageFetcherClass;
    }

    /**
     * Gets the value for messageFetcherOptions.
     *
     * @return array
     */
    public function getMessageFetcherOptions()
    {
        return $this->messageFetcherOptions;
    }

    /**
     * Sets the value for messageFetcherOptions.
     *
     * @param array $messageFetcherOptions
     */
    public function setMessageFetcherOptions($messageFetcherOptions)
    {
        $this->messageFetcherOptions = $messageFetcherOptions;
    }

    /**
     * Gets the value for transportClass.
     *
     * @return string
     */
    public function getTransportClass()
    {
        return $this->transportClass;
    }

    /**
     * Sets the value for transportClass.
     *
     * @param string $transportClass
     */
    public function setTransportClass($transportClass)
    {
        $this->transportClass = $transportClass;
    }

    /**
     * Gets the value for transportOptions.
     *
     * @return array
     */
    public function getTransportOptions()
    {
        return $this->transportOptions;
    }

    /**
     * Sets the value for transportOptions.
     *
     * @param array $transportOptions
     */
    public function setTransportOptions($transportOptions)
    {
        $this->transportOptions = $transportOptions;
    }

    /**
     * Gets the value for fromAddress.
     *
     * @return string
     */
    public function getFromAddress()
    {
        return $this->fromAddress;
    }

    /**
     * Sets the value for fromAddress.
     *
     * @param string $fromAddress
     */
    public function setFromAddress($fromAddress)
    {
        $this->fromAddress = $fromAddress;
    }

    /**
     * Gets the value for forgottenPasswordSubject.
     *
     * @return string
     */
    public function getForgottenPasswordSubject()
    {
        return $this->forgottenPasswordSubject;
    }

    /**
     * Sets the value for forgottenPasswordSubject.
     *
     * @param string $forgottenPasswordSubject
     */
    public function setForgottenPasswordSubject($forgottenPasswordSubject)
    {
        $this->forgottenPasswordSubject = $forgottenPasswordSubject;
    }

    /**
     * Gets the value for accountValidationSubject.
     *
     * @return string
     */
    public function getAccountValidationSubject()
    {
        return $this->accountValidationSubject;
    }

    /**
     * Sets the value for accountValidationSubject.
     *
     * @param string $accountValidationSubject
     */
    public function setAccountValidationSubject($accountValidationSubject)
    {
        $this->accountValidationSubject = $accountValidationSubject;
    }
}
