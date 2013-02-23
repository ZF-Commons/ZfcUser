<?php

namespace ZfcUser\Mail;

use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;

/**
 * Simple email implementation for ZfcUser using {@see Zend\Mail}.
 *
 * @author Tom Oram <tom@x2k.co.uk>
 */
class ZendMail implements MessageFetcherInterface, MailTransportInterface
{
    /**
     * @var PhpRenderer
     */
    protected $renderer;

    /**
     * @var string
     */
    protected $templatePath;

    /**
     * 
     * @param PhpRenderer $renderer
     * @param string      $templatePath
     */
    public function __construct(PhpRenderer $renderer, $templatePath = '')
    {
        $this->renderer = $renderer;
        $this->templatePath = (string) $templatePath;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $name
     * @param array  $params
     * @return string 
     */
    public function getMessage($name, array $params = array())
    {
        $model = new ViewModel();

        $templateName = strlen($this->templatePath)
            ? $this->templatePath . '/' . $name
            : $name;

        $model->setTemplate($templateName);

        return $this->renderer->render($model);
    }

    /**
     * Sends a mail using {@see Zend\Mail\Sendmail}
     *
     * @todo Add support for SMTP
     *
     * @param string $to
     * @param string $from
     * @param string $subject
     * @param string $body
     */
    public function send($to, $from, $subject, $body)
    {
        $message = new Message();
        $message->addTo($to)
            ->addFrom($from)
            ->setSubject($subject)
            ->setBody($body);

        $transport = new Sendmail();

        $transport->send($message);
    }
}
