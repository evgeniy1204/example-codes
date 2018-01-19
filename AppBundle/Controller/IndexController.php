<?php

namespace BetaOmega\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use BetaOmega\AdminBundle\Entity\Article;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository('AdminBundle:Article')->findBy(['enable' => 1]);

        return $this->render("AppBundle:Default:index.html.twig", ['articles' => $articles]);
    }


    /**
     * @param Request $request
     *
     * @Route("/sender-email/{template}", name="app_sender", defaults={"template" : "default"})
     * @Method("POST")
     */
    public function notificateAction(Request $request, $template)
    {
        $this->get('beta-omega.admin.service')->lestenerSendEmails($template);

        return new Response("ok", 200);
    }
}
