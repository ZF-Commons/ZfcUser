<?php

namespace ZfcUser\Controller;

use Zend\Form\Form;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use ZfcUser\Entity\UserInterface;
use ZfcUser\Form\ResetPassword;
use ZfcUser\Hash\UserHashInterface;
use ZfcUser\Mail\MailTransportInterface;
use ZfcUser\Mail\MessageFetcherInterface;
use ZfcUser\Mapper\UserInterface as UserMapper;
use ZfcUser\Options\MailOptions;
use ZfcUser\Options\ForgottenPasswordControllerOptionsInterface;
use ZfcUser\Service\User as UserService;

/**
 * Controller for handling password retrievals.
 *
 * @author Tom Oram <tom@x2k.co.uk>
 */
class ForgottenPasswordController extends AbstractActionController
{
    /**
     * @var UserControllerOptionsInterface
     */
    protected $options;

    /**
     * @var MailOptions
     */
    protected $mailOptions;
    
    /**
     * @var UserMapper
     */
    protected $userMapper;
    
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var Form
     */
    protected $forgottenPasswordForm;

    /**
     * @var ResetPassword
     */
    protected $resetPasswordForm;

    /**
     * @var MessageFetcherInterface
     */
    protected $messageFetcher;

    /**
     * @var MailTransportInterface
     */
    protected $mailTransport;
    
    /**
     * @var UserHashInterface
     */
    protected $hashHandler;

    /*
     * Actions
     */

    /**
     * Displays a form where a user can enter their email address to reset their password.
     *
     * @return array
     */
    public function indexAction()
    {
        // if password retrieval is disabled
        if (!$this->getOptions()->getEnableForgottenPassword()) {
            return array('enableForgottenPassword' => false);
        }

        $form = $this->getForgottenPasswordForm();

        $prg = $this->prgForm($form, $this->url()->fromRoute('zfcuser/forgottenpassword'));

        if ($prg !== false) {
            return $prg;
        }

        // Fetch the user and generate an email

        $mapper = $this->getUserMapper();
        $user = $mapper->findByEmail($form->getEmail());

        $this->sendEmail($user);

        $this->flashMessenger()->setNamespace('zfcuser-login-form')
            ->addMessage('An email has been sent to your email address with further instructions.');

        return $this->redirect()->toRoute('zfcuser/login');
    }



    /**
     * This is the target of the link in the email.
     * A form is diplayed so a new password can be entered.
     *
     * @return array
     */
    public function resetAction()
    {
        // if password retrieval is disabled
        if (!$this->getOptions()->getEnableForgottenPassword()) {
            return array('enableForgottenPassword' => false);
        }

        $id = (int) $this->params('id');
        $hash = $this->params('hash');

        // Verify the request
        $user = $this->getUserMapper()->findById($id);

        if (!$user || !$this->getHashHandler()->checkHash($user, $hash))
        {
            $this->flashMessenger()->setNamespace('zfcuser-login-form')
                ->addMessage('Invalid password reset link.');

            return $this->redirect()->toRoute('zfcuser/login');
        }

        $form = $this->getResetPasswordForm();

        $prg = $this->prgForm(
            $form,
            $this->url()->fromRoute('zfcuser/forgottenpassword/reset', array('id' => $id, 'hash' => $hash)),
            array('userId' => $id, 'userHash' => $hash)
        );

        if ($prg !== false) {
            return $prg;
        }

        $newPassword = $form->getNewPassword();

        $this->getUserService()->resetPassword($user, $newPassword);

        $this->flashMessenger()->setNamespace('zfcuser-login-form')
            ->addMessage('You can now log in with your new password.');

        return $this->redirect()->toRoute('zfcuser/login');
    }

    /*
     * Internal Methods
     */

    /**
     * User Post/Request/Get and validates the form.
     *
     * @param Form   $form
     * @param string $prgUrl
     * @return mixed If the value is not false it should be returned from the action
     */
    protected function prgForm(Form $form, $prgUrl, $params = array())
    {
        $params['form'] = $form;
        $params['enableForgottenPassword'] = true;

        $prg = $this->prg($prgUrl, true);

        if ($prg instanceof Response) {
            return $prg;
        }

        if ($prg === false) {
            return $params;
        }

        $form->setData($prg);

        if (!$form->isValid()) {
            return $params;
        }

        return false;
    }

    /**
     * Decides what to use to display as the users name.
     *
     * @param UserInterface $user
     * @return string
     */
    protected function usersName(UserInterface $user)
    {
        if (strlen($user->getDisplayName())) {
            return $user->getDisplayName();
        }

        if (strlen($user->getUsername())) {
            return $user->getUsername();
        }

        return $user->getEmail();
    }

    /**
     * Send the password retrieval message to the user.
     *
     * @param UserInterface $user
     */
    protected function sendEmail(UserInterface $user)
    {
        $mailOptions = $this->getMailOptions();

        $name = $this->usersName($user);

        $link = $this->url()->fromRoute(
            'zfcuser/forgottenpassword/reset',
            array(
                'id' => $user->getId(),
                'hash' => $this->getHashHandler()->createHash($user),
                array('force_canonical' => true)
            )
        );

        $params = array(
            'name' => $name,
            'link' => $link,
        );

        $message = $this->getMessageFetcher()->getMessage('forgotten-password', $params);

        die($message);
        $from = $mailOptions->getFromAddress();

        $to = sprintf(
            '"%s" <%s>',
            addslashes($name),
            $user->getEmail()
        );

        $this->getMailTransport()->send(
            $to,
            $from,
            $mailOptions->getForgottenPasswordSubject(),
            $message
        );
    }

    /*
     * Getters & Setters
     */

    /**
     * get options
     *
     * @return UserControllerOptionsInterface
     */
    public function getOptions()
    {
        if (!$this->options instanceof ForgottenPasswordControllerOptionsInterface) {
            $this->setOptions($this->getServiceLocator()->get('zfcuser_module_options'));
        }
        return $this->options;
    }

    /**
     * set options
     *
     * @param ForgottenPasswordControllerOptionsInterface $options
     * @return self
     */
    public function setOptions(ForgottenPasswordControllerOptionsInterface $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Get the mail options
     *
     * @return MailOptions
     */
    public function getMailOptions()
    {
        if (!$this->mailOptions) {
            $options = $this->getServiceLocator()->get('zfcuser_module_options');
            $this->setMailOptions($options->getMail());
        }

        return $this->mailOptions;
    }
    
    /**
     * Set the mail options
     *
     * @param MailOptions $mailOptions
     * @return self
     */
    public function setMailOptions(MailOptions $mailOptions)
    {
        $this->mailOptions = $mailOptions;

        return $this;
    }

    /**
     * Get the user mapper.
     *
     * @return UserMapper
     */
    public function getUserMapper()
    {
        if (!$this->userMapper) {
            $this->setUserMapper($this->getServiceLocator()->get('zfcuser_user_mapper'));
        }

        return $this->userMapper;
    }

    /**
     * Set the user mapper.
     *
     * @param UserMapper $userMapper
     * @return self
     */
    public function setUserMapper(UserMapper $userMapper)
    {
        $this->userMapper = $userMapper;

        return $this;
    }

    /**
     * Get the user service.
     *
     * @return UserService
     */
    public function getUserService()
    {
        if (!$this->userService) {
            $this->setUserService($this->getServiceLocator()->get('zfcuser_user_service'));
        }

        return $this->userService;
    }

    /**
     * Set the user service.
     *
     * @param UserService $userService
     * @return self
     */
    public function setUserService(UserService $userService)
    {
        $this->userService = $userService;

        return $this;
    }

    /**
     * Returns the forgotten password form.
     *
     * @return Form
     */
    public function getForgottenPasswordForm()
    {
        if (!$this->forgottenPasswordForm) {
            $this->setForgottenPasswordForm($this->getServiceLocator()->get('zfcuser_forgotten_password_form'));
        }

        return $this->forgottenPasswordForm;
    }

    /**
     * Sets the forgotten password form.
     *
     * @param Form $forgottenPasswordForm
     * @return self
     */
    public function setForgottenPasswordForm(Form $forgottenPasswordForm)
    {
        $this->forgottenPasswordForm = $forgottenPasswordForm;

        return $this;
    }

    /**
     * Return the reset password form.
     *
     * @return ResetPassword
     */
    public function getResetPasswordForm()
    {
        if (!$this->resetPasswordForm) {
            $this->setResetPasswordForm($this->getServiceLocator()->get('zfcuser_reset_password_form'));
        }

        return $this->resetPasswordForm;
    }

    /**
     * Sets the reset password form 
     *
     * @param ResetPassword $form
     * @return self
     */
    public function setResetPasswordForm(ResetPassword $form)
    {
        $this->resetPasswordForm = $form;

        return $this;
    }

    /**
     * Returns the mail message fetcher.
     *
     * @return MessageFetcherInterface
     */
    public function getMessageFetcher()
    {
        if (!$this->messageFetcher) {
            $this->setMessageFetcher($this->getServiceLocator()->get('zfcuser_mail_fetcher'));
        }

        return $this->messageFetcher;
    }

    /**
     * Sets the mail message fetcher.
     *
     * @param MessageFetcherInterface $fetcher
     * @return self
     */
    public function setMessageFetcher(MessageFetcherInterface $fetcher)
    {
        $this->messageFetcher = $fetcher;

        return $this;
    }

    /**
     * Gets the mail transport.
     *
     * @return MailTransportInterface
     */
    public function getMailTransport()
    {
        if (!$this->mailTransport) {
            $this->setMailTransport($this->getServiceLocator()->get('zfcuser_mail_transport'));
        }

        return $this->mailTransport;
    }

    /**
     * Sets the mail transport.
     *
     * @param MailTransportInterface $transport
     * @return self
     */
    public function setMailTransport(MailTransportInterface $transport)
    {
        $this->mailTransport = $transport;

        return $this;
    }

    /**
     * Set the hasher
     *
     * @return UserHashInterface
     */
    public function getHashHandler()
    {
        if (!$this->hashHandler) {
            $this->hashHandler = $this->getServiceLocator()->get('zfcuser_hash_handler');
        }

        return $this->hashHandler;
    }

    /**
     * Get the hasher.
     *
     * @param UserHashInterface $hashHandler
     * @return self
     */
    public function setHashHandler(UserHashInterface $hashHandler)
    {
        $this->hashHandler = $hashHandler;

        return $this;
    }
}
