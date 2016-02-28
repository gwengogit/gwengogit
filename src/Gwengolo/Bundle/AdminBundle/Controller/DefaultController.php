<?php

namespace Gwengolo\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Gwengolo\Bundle\ProductBundle\Entity\Newsletter;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GwengoloAdminBundle:Default:index.html.twig', array('name' => 'toto'));
    }

    public function searchCollectionsAction()
    {
    	return $this->render('GwengoloAdminBundle:Collection:search.html.twig', array('name' => 'toto'));
    }

    public function addCollectionAction()
    {
    	return $this->render('GwengoloAdminBundle:Collection:add.html.twig', array('name' => 'toto'));
    }

    public function searchProductsAction()
    {
    	return $this->render('GwengoloAdminBundle:Product:search.html.twig', array('name' => 'toto'));
    }

    public function addProductAction()
    {
    	return $this->render('GwengoloAdminBundle:Product:add.html.twig', array('name' => 'toto'));
    }

    public function newsletterAction()
    {
        $em = $this->getDoctrine()->getManager();
        $emails = $em->getRepository('GwengoloProductBundle:Newsletter')->findAll();

        return $this->render('GwengoloAdminBundle:Default:newsletter.html.twig', array('emails' => $emails));
    }
}
