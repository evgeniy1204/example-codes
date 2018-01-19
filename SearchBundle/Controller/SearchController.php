<?php

namespace BetaOmega\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\{Route,Method};
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/search")
 */
class SearchController extends Controller
{
    /**
     * @Route("/", name="search_index")
     * 
     * @Route("/{type}", name="search_synopses", requirements={"type":"%ccy%|%download%|%commented%|%recomended%"})
     * 
     * @Method({"GET", "POST"})
     * 
     */
    public function indexAction(Request $request, $type = null)
    {
    	$em = $this->getDoctrine()->getManager();
    	$paginator  = $this->get('knp_paginator');

    	$synopsesOnPage = $request->get('show') ?? $this->getParameter('beta-omega.app.synopses.items-on-page-default');

    	/** @var $coursesService \BetaOmega\SearchBundle\Services\SearchService */
    	$coursesService = $this->get('beta-omega.search.service')->getClassAndYearFromCourse();

    	/** @var $synopses \BetaOmega\SearchBundle\Services\SearchService */
    	$synopses = $this->get('beta-omega.search.service')->searchSynopses($type);
        
    	$pagination = $paginator->paginate(
            $synopses,
            $request->query->getInt('page', $request->get('page', 1)),
            $synopsesOnPage
        );

        return $this->render('SearchBundle:Index:index.html.twig',
        	[
        		'coursesService' => $coursesService,
                'totalCount' => $pagination->getTotalItemCount(),
        		'synopses' => $pagination
        	]);
    }

    /**
     * Dynamic search
     * 
     * @Route("/dynamic-search-synopses", name="dynamic_search_synopses")
     * @Method("POST")
     * 
     * @param Request $request
     * @return template
     */
    public function dynamicSearchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->request->get('data')) {
            $synopses = $em->getRepository('SynopsesBundle:Synopses')->findSynopsesPerName($request->request->get('data'));
        } else {
            $synopses = [];
        }

        $request->query->set('sortBy', $request->request->get('sortByField'));

        return $this->render('SearchBundle:Index:_list-search.html.twig',
            [
                'synopses' => ['items' => $synopses],
                'totalCount' => count($synopses),
                'pagination' => false,
                'showByNone' => false
            ]);
    }

    /**
     * Search topics
     *
     * @Route("/search-topic", name="search_topic")
     * @Method("POST")
     */
    public function dynamicSearchTopicsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($request->request->get('data')) {
            $topics = $em->getRepository('ForumBundle:Topic')->searchTopics($request->request->get('data'));
            $template = "_result-search.html.twig";
        } else {
            $topics = $this->get('beta-omega.forum.manager')->loadTopics();
            $template = "_topics.html.twig";
        }


        return $this->render('ForumBundle:Forum:'.$template.'', [
            'topics' => $topics
        ]);
    }
}
