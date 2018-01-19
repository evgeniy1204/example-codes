<?php

namespace BetaOmega\EmailBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\HttpFoundation\{RequestStack, Response};
use BetaOmega\EmailBundle\Model\Email;

/**
* \brief Класс отправки сообщения на почту
*/
class EmailManager
{
	/**
	 * Container $container
	 */
    protected $container;

    protected $templating;

    protected $request;

    public function __construct(Container $container, $templating)
    {
        $this->container = $container;
        $this->templating = $templating;
    }

    /**
     * Set this request
     * 
     * @param RequestStack $request_stack
     * 
     */
    public function setRequest(RequestStack $request_stack)
    {
        $this->request = $request_stack->getCurrentRequest();
    }

    /**
     * \brief И сама отправка
     */
    public function send(Email $email): array
    {
        if (count($email->getEmails())) {
            $emailFrom = $this->container->getParameter('mailer_user');
            $password = $this->container->getParameter('mailer_password');
            $host = $this->container->getParameter('mailer_host');
            $port = $this->container->getParameter('mailer_port');
            $certificate = $this->container->getParameter('mailer_encryption');

            $message = \Swift_Message::newInstance()
                ->setSubject($email->getSubject())
                ->setFrom($emailFrom)
                ->setTo($email->getEmails())
                ->setBody(
                    $this->templating->render(
                        "EmailBundle:Emails:" . $email->getTemplate() . ".html.twig",
                        array('email' => $email)
                    ),
                    'text/html'
                );

            if ($email->getAttachment()) {
                $path = $email->getAttachment()['path'];
                foreach ($email->getAttachment()['files'] as $key => $value) {

                    $message->attach(\Swift_Attachment::fromPath($path.$value));
                }
            }

            $transport = \Swift_SmtpTransport::newInstance($host, $port, $certificate)
                ->setUsername($emailFrom)
                ->setPassword($password);

            $mailer = $this->container->get('swiftmailer.mailer.main_mailer');

            if ($mailer->send($message)) {
                return ['status' => 200, 'msg' => 'success'];
            }

            return ['status' => 500, 'msg' => 'error'];
        }

        return ['status' => 404, 'msg' => 'Emails not found'];
    }
}