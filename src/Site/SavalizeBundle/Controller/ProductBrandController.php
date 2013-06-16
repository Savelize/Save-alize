<?php

namespace Site\SavalizeBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Site\SavalizeBundle\Entity\ProductBrand;

/**
 * ProductBrand controller.
 *
 */
class ProductBrandController extends Controller
{
    /**
     * Lists all ProductBrand entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SiteSavalizeBundle:ProductBrand')->findAll();

        return $this->render('SiteSavalizeBundle:ProductBrand:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a ProductBrand entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteSavalizeBundle:ProductBrand')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProductBrand entity.');
        }

        return $this->render('SiteSavalizeBundle:ProductBrand:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

}
