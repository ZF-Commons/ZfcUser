<?php

namespace ZfcUser\Mail;

/**
 * Interface for getting the contents of an email from a template.
 *
 * @author Tom Oram <tom@x2k.co.uk>
 */
interface MessageFetcherInterface
{
    /**
     * Fetches a message template of the given name and substitutes the
     * provided params into it.
     *
     * @param string $name   The name of the template
     * @param array  $params The params to be substitued into the template
     * @return string        The message ready to be sent
     */
    public function getMessage($name, array $params = array());
}
