<?php

namespace Site\SavalizeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SiteSavalizeBundle:Default:index.html.twig');
    }
    public function showOneReviewAction()
    {
    	return $this->render('SiteSavalizeBundle:Default:oneReview.html.twig', array());
    }
    public function showAllReviews()
    {
    	 $em=$this->getDoctrine()->getEntityManager();
    	 $categories = $em->getRepository('SiteSavalizeBundle:Category')->findAll();
    	 return $this->render('SiteSavalizeBundle:Default:allReviews.html.twig', array('cats' => $categories));
    }
}
