<?php 

namespace BetaOmega\AppBundle\Services;

use BetaOmega\EmailBundle\Model\Email;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\{RequestStack, Response};
use Symfony\Component\HttpKernel\Exception\HttpNotFoundException;

use BetaOmega\EmailBundle\Services\EmailManager;
use Symfony\Component\DependencyInjection\ContainerInterface as Containers;

/**
* \brief Менеджер приложения
*/
class AppManager
{
	/**
	 * User $user
	 */
	protected $user;

	/**
	 * ObjectManager $entityManager
	 */
	protected $entityManager;

	/**
	 * EmailManager $emailSender
	 */
	protected $emailSender;

	protected $request;

    /**
     * Containers $container
     */
    protected $container;

    public function __construct(ObjectManager $entityManager, TokenStorage $tokenStorage, EmailManager $emailSender, Containers $container)
    {
        $this->user = $tokenStorage->getToken()->getUser();
        $this->entityManager = $entityManager;
        $this->emailSender = $emailSender;
        $this->container = $container;
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
     * 
     */
    public function prepareAndSendAdvice()
    {
    	$data = $this->request->request->get('advice');

    	if ($data) {
    		$synopsis = (int) $data['synopsis'];
    		if (! $synopsis) {
    			throw new NotFoundHttpException("Synopsis not found");
    		}

    		$synopsis = $this->entityManager->getRepository('SynopsesBundle:Synopses')->find($synopsis);
    		$author = $synopsis->getUser();

    		$email = new Email();
    		$email->setSubject("C’è qualcosa per te!");
    		$email->setText($data['text']);
    		$email->addEmail($author->getEmail());

    		$this->emailSender->send($email);
    	}
    }
}