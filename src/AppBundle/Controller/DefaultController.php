<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Document;
use Gumlet\ImageResize;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->container->get("doctrine.orm.default_entity_manager");
        $img = $em->getRepository("AppBundle\Entity\Document")->FindAll();

       



        return $this->render('default/index.html.twig', array(
            
            'img' => $img
        ));
    }

   
}
