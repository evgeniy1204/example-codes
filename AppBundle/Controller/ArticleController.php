<?php

namespace BetaOmega\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BetaOmega\AdminBundle\Entity\Article;

/**
 * Class ArticleController
 * @package BetaOmega\AppBundle\Controller
 *
 * @Route("/article")
 */
class ArticleController extends Controller
{
    /**
     * @param Request $request
     *
     * @Route("/search/{tag}", name="app_articles_index")
     */
    public function indexAction(Request $request, $tag)
    {
        $articles = $this->get('beta-omega.article.manager')->getArticles($tag);

        return $this->render('AppBundle:Article:index.html.twig', ['articles' => $articles]);
    }

    /**
     * Finds and displays a article entity.
     *
     * @Route("/{id}", name="article_show_homepage")
     */
    public function showAction(Article $article)
    {
        return $this->render('AppBundle:Article:show.html.twig', array(
            'article' => $article
        ));
    }
}