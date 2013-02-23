<?php

namespace ZfcUser\Mail;

/**
 * Interface for sending emails.
 *
 * @author Tom Oram <tom@x2k.co.uk>
 */
interface MailTransportInterface
{
    /**
     * Sends an email.
     *
     * @param string $to
     * @param string $from
     * @param string $subject
     * @param string $body
     */
    public function send($to, $from, $subject, $body);
}
