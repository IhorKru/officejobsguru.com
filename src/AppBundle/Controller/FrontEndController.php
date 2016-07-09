<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FrontEndController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('FrontEnd/index.html.twig');
    }
    
    /**
    * @Route("/thankureg", name="thankureg")
    */
    public function thankuregAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('FrontEnd/thankureg.html.twig');
    }
    
    /**
    * @Route("/terms", name="terms")
    */
    public function termsAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('FrontEnd/terms.html.twig');
    }
    
    /**
    * @Route("/privacy", name="privacy")
    */
    public function privacyAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('FrontEnd/privacy.html.twig');
    }
    
    /**
    * @Route("/unsubscribe", name="unsubscribe")
    */
    public function unsubscribeAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('FrontEnd/unsubscribe.html.twig');
    }
    
    
}


