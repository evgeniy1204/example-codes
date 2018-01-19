<?php

namespace BetaOmega\AppBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\{RequestStack};
use Symfony\Component\HttpKernel\Exception\HttpNotFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface as Containers;

/**
 * \brief Менеджер статей
 */
class ArticleManager
{
    /**
     * ObjectManager $entityManager
     */
    protected $entityManager;

    protected $request;

    /**
     * Containers $container
     */
    protected $container;

    public function __construct(ObjectManager $entityManager, Containers $container)
    {
        $this->entityManager = $entityManager;
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
     * Find articles by tag
     *
     * @param $tag
     */
    public function getArticles($tag)
    {
        $articles = $this->entityManager->getRepository('AdminBundle:Article')->getArticlesByTag($tag);

        $articlesWithPagination = $this->getArticlesWithPaginate($articles);

        return $articlesWithPagination;
    }

    /**
     * @param array $articles
     */
    public function getArticlesWithPaginate(array $articles)
    {
        $paginator  = $this->container->get('knp_paginator');

        $pagination = $paginator->paginate(
            $articles,
            $this->request->query->getInt('page', $this->request->get('page', 1)),
            $this->container->getParameter('beta-omega.admin.items-on-page')
        );

        return $pagination;
    }
}