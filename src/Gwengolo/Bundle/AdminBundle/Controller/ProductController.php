<?php

namespace Gwengolo\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Gwengolo\Bundle\AdminBundle\Form\Type\ProductType;
use Gwengolo\Bundle\AdminBundle\Manager\ProductManager;
use Gwengolo\Bundle\ProductBundle\Entity\Product;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Action\RowAction;

class ProductController extends Controller
{
    public function searchAction()
    {
       $source = new Entity('GwengoloProductBundle:Product');
        $grid = $this->get('grid');
        $grid->setSource($source);
        $editAction = new RowAction('Modifier', 'gwengolo_admin_product_edit', false, '_self', array('class' => 'grid_delete_action'));
        $imageAction = new RowAction('Gérer les images', 'gwengolo_admin_image', false, '_self', array('class' => 'grid_delete_action'));
        $deleteAction = new RowAction('Supprimer', 'gwengolo_admin_product_delete', true, '_self', array('class' => 'grid_delete_action', 'confirmMessage' => 'toto?'));
        $deleteAction->setConfirmMessage('Attention, ce produit va être supprimé, voulez vous continuer?');
        $grid->addRowAction($editAction);
        $grid->addRowAction($deleteAction);
        $grid->addRowAction($imageAction);

        return $grid->getGridResponse('GwengoloAdminBundle:Product:search.html.twig', array('name' => 'toto'));

    	//return $this->render('GwengoloAdminBundle:Product:search.html.twig', array('name' => 'toto'));
    }

    public function addAction()
    {
    	$request = $this->getRequest();
       	$form = $this->createForm(new ProductType(), new Product());

    	if ($request->getMethod() == 'POST') {
    		$this->get('gwengolo_admin.product_manager')->saveNewProduct($form->bind($request));

            return $this->redirect($this->generateUrl('gwengolo_admin_product_search'));
		}
    	
    	
    	return $this->render('GwengoloAdminBundle:Product:add.html.twig', array('form' => $form->createView(), 'current_action' => 'add'));
    }

    public function editAction($id)
    {
        $product = $this->getDoctrine()->getManager()->getRepository('GwengoloProductBundle:Product')->find($id);
        $form = $this->createForm(new ProductType(), $product);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $this->get('gwengolo_admin.product_manager')->updateProduct($form->bind($request));
        }

        return $this->render('GwengoloAdminBundle:Product:add.html.twig', array('form' => $form->createView(), 'current_action' => 'edit'));
    }

    public function deleteAction($id)
    {
        $product = $this->getDoctrine()->getManager()->getRepository('GwengoloProductBundle:Product')->find($id);
        $this->get('gwengolo_admin.product_manager')->deleteProduct($product);
        
        return $this->redirect($this->generateUrl('gwengolo_admin_product_search'));
    }
}