<?php

namespace BetaOmega\AppBundle\Services;

use BetaOmega\ForumBundle\Entity\Topic;
use BetaOmega\SynopsesBundle\Entity\Downloads;
use BetaOmega\SynopsesBundle\Entity\Synopses;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\{RequestStack};
use BetaOmega\EmailBundle\Services\EmailManager;
use BetaOmega\EmailBundle\Model\Email;
use Symfony\Component\DependencyInjection\ContainerInterface as Containers;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * \brief Notification event
 */
class NotificationEmailManager
{

    /**
     * ObjectManager $entityManager
     */
    protected $entityManager;

    /**
     * EmailManager $emailSender
     */
    protected $emailSender;

    /**
     * RequestStack $request_stack
     */
    protected $request;

    /**
     * Containers $container
     */
    protected $container;

    public function __construct(ObjectManager $entityManager, EmailManager $emailSender, Containers $container)
    {
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
     * Added new synopsis. Send email users which subscribe on author
     *
     * @param Synopses $synopses
     */
    public function eventNewSynopsis(Synopses $synopses)
    {
        $author = $synopses->getUser();

        $emails = [];

        foreach ($author->getFollows() as $entity) {
            if ($entity->getNotificationNewSynopsisMyFollows()) {
                $emails[] = $entity->getEmail();
            }
        }

        $author = $synopses->getUser()->getNameAndSurname();
        $synopsis = $synopses->getName();
        $urlReturn = $this->generateReturnUrl('synopses_show', ['id' => $synopses->getId()]);

        $email = new Email();
        $email->setSubject("C’è qualcosa per te!");
        $email->setText("Utente " . $author . " ha aggiunto una nuova dispensa " . $synopsis . ".");
        $email->setEmails($emails);
        $email->setUrlReturn($urlReturn);

        return $this->emailSender->send($email);
    }

    /**
     * Added new synopsis with subject which i interesting
     *
     * @param Synopses $synopses
     */
    public function eventNewSynopsisWithMySubject(Synopses $synopses)
    {
        $usersWithThisSubject = $this->entityManager->getRepository('UserBundle:User')->getUserWhichInterestingSubjects($synopses->getSubject());

        $emails = [];

        foreach ($usersWithThisSubject as $entity) {
            if ($entity->getNotificationNewSynopsisMySubject()) {
                $emails[] = $entity->getEmail();
            }
        }

        $subject = $synopses->getSubject()->getName();
        $synopsisName = $synopses->getName();
        $urlReturn = $this->generateReturnUrl('synopses_show', ['id' => $synopses->getId()]);

        $email = new Email();
        $email->setSubject("C’è qualcosa per te!");
        $email->setText("Alla disciplina " . $subject . " è stata aggiunta una nuova dispensa " . $synopsisName . ".");
        $email->setEmails($emails);
        $email->setUrlReturn($urlReturn);

        return $this->emailSender->send($email);
    }

    /**
     * When somebody download my synopsis
     *
     * @param Synopses $synopses
     */
    public function eventDownloadMySynopsis(Downloads $downloads)
    {
        $author = $downloads->getSynopses()->getUser();

        if ($author->getNotificationDownloadMySynopsis()) {
            $authorName = $downloads->getUser()->getNameAndSurname();
            $synopsisName = $downloads->getSynopses()->getName();
            $urlReturn = $this->generateReturnUrl('synopses_show', ['id' => $downloads->getSynopses()->getId()]);

            $email = new Email();
            $email->setSubject("La tua dispensa è stata scaricata!");
            $email->setText("Utente " . $authorName . " ha appena scaricato la tua dispensa " . $synopsisName . ".");
            $email->addEmail($author->getEmail());
            $email->setUrlReturn($urlReturn);

            return $this->emailSender->send($email);
        }
    }

    /**
     * Event when my follows added new post in forum
     *
     * @param Topic $topic
     */
    public function newPostMyFollows(Topic $topic)
    {
        if ($topic->getTopic() == null) {
            $author = $topic->getUser();

            foreach ($author->getFollows() as $user) {
                if ($user->getNotificationNewPostMyFollows()) {
                    $authorName = $topic->getUser()->getNameAndSurname();
                    $topicName = $topic->getSubject();
                    $urlReturn = $this->generateReturnUrl('topic_show', ['id' => $topic->getId()]);

                    $email = new Email();
                    $email->setSubject("C’è qualcosa per te!");
                    $email->setText("Utente " . $authorName . " che stai seguendo ha appena caricato un nuovo post su forum " . $topicName . ".");
                    $email->addEmail($user->getEmail());
                    $email->setUrlReturn($urlReturn);

                    return $this->emailSender->send($email);
                }
            }
        }
    }

    /**
     * Event when send comment in my post in forum
     *
     * @param Topic $topic
     */
    public function newCommentMyPost(Topic $topic)
    {
        if ($topic->getTopic()) {
            $author = $topic->getTopic()->getUser();

            if ($author->getNotificationNewCommentMyPost()) {
                $authorName = $topic->getUser()->getNameAndSurname();
                $topicName = $topic->getSubject();
                $urlReturn = $this->generateReturnUrl('topic_show', ['id' => $topic->getTopic()->getId(), 'comment' => $topic->getId()]);

                $email = new Email();
                $email->setSubject("C’è qualcosa per te!");
                $email->setText("Utente " . $authorName . " ha appena lasciato un commento al tuo post " . $topicName . "");
                $email->addEmail($author->getEmail());
                $email->setUrlReturn($urlReturn);

                return $this->emailSender->send($email);
            }
        }
    }

    /**
     * @param $routeName
     * @param array $parameters
     */
    private function generateReturnUrl($routeName, $parameters = [])
    {
        return $this->container->get('router')->generate($routeName, $parameters, 0);
    }
}