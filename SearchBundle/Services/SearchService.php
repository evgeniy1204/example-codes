<?php

namespace BetaOmega\SearchBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\DependencyInjection\ContainerInterface as Containers;
use Symfony\Component\HttpFoundation\{Response, RequestStack};

use BetaOmega\AppBundle\Entity\Course;
use BetaOmega\AppBundle\Entity\Classes;
use BetaOmega\AppBundle\Entity\YearOfStudy;

/**
 * Class SearchService
 *
 * @package BetaOmega\SearchBundle\Service
 */
class SearchService extends Container
{

	/**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $entityManager;

    /**
     *  @var User $user
     */
    protected $user;

    /**
     * @var Containers $container
     */
    protected $container;

    /**
     * @var RequestStack $request
     */
    protected $request;

    /**
     * @param ObjectManager           $entityManager
     */
    public function __construct(ObjectManager $entityManager, TokenStorage $tokenStorage, Containers $container)
    {
        $this->entityManager = $entityManager;
        $this->user = $tokenStorage->getToken()->getUser();
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
     * Course select event listener
     * 
     * @param Request $request
     * @return array
     * 
     */
    public function getClassAndYearFromCourse(): array
    {
        $courses = $this->entityManager->getRepository('AppBundle:Course')->findAll();

        if (! $this->request->isMethod($this->request::METHOD_POST)) {
            return [
                    'courses' => $courses,
                    'classes' => [],
                    'years' => []
                    ]; 
        }

        $selectedCourseId = $this->request->request->get('course');

        $course = $this->entityManager->getRepository('AppBundle:Course')->find($selectedCourseId);

        return [
                'courses' => $courses,
                'classes' => $course->getClasses(),
                'years' => $course->getYear()
                ];
    }

    /**
     * Search synopses on search page
     * 
     * @return array $synopses
     */
    public function searchSynopses($type = null)
    {
        if (! $this->request->isMethod($this->request::METHOD_GET)) {
            return [];
        }

        $synopses = [];

        $course = $this->request->get('course-search');
        $class = $this->request->get('class-search');
        $year = $this->request->get('year-search');
        $tag = $this->request->get('tag');
        
        $sortBy = $this->request->get('sortBy') ?? null;

        if ($this->user != null) {
            switch ($type) {
                /**
                 * Static page CCY
                 */
                case $this->getParameter('ccy'):
                    $synopses = $this->entityManager->getRepository('SynopsesBundle:Synopses')->getSynopsesByClassAndYear(
                        $this->user->getCourse(),
                        $this->user->getClasses(),
                        $this->user->getYear(),
                        $tag);
                    break;
                 // The most download on this week
                case $this->getParameter('download'):
                    $synopses = $this->entityManager->getRepository('SynopsesBundle:Synopses')->getSynopsesTheMostDownloadThisWeek();
                    break;
                case $this->getParameter('commented'):
                    $synopses = $this->entityManager->getRepository('SynopsesBundle:Synopses')->getSynopsesTheMostReviews();
                    if ($sortBy == null) { // if not sort by. when sort by per reviews
                        $this->request->attributes->set('sortBy', 'reviews');
                    }
                    break;
                case $this->getParameter('recomended'):
                    $followers = $this->user->getFollowers()->toArray();
                    $synopses = $this->entityManager->getRepository('SynopsesBundle:Synopses')->getRecomendedSynopses($this->user->getCourse(),
                                                                                                                $this->user->getClasses(),
                                                                                                                $this->user->getYear(),
                                                                                                                $this->user->getSubject(),
                                                                                                                $followers);
                    $synopses = array_unique($synopses);
                    break;
                default:
                    $synopses = $this->entityManager->getRepository('SynopsesBundle:Synopses')->getSynopsesByClassAndYear($course, $class, $year, $tag);
                    break;
            }
        }

        return $synopses;
    }

    /**
     * Get parameters in system config
     * 
     * @param strint $parameter
     * @return string
     */
    public function getParameter($parameter)
    {
        return $this->container->getParameter($parameter);
    }
}