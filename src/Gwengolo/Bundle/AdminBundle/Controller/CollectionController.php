<?php

namespace Gwengolo\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Gwengolo\Bundle\AdminBundle\Form\Type\CollectionType;
use Gwengolo\Bundle\ProductBundle\Entity\Collection;

class CollectionController extends Controller
{

    public function searchAction()
    {
        $collections = $this->getDoctrine()->getManager()->getRepository('GwengoloProductBundle:Collection')->findAll();

    	return $this->render('GwengoloAdminBundle:Collection:search.html.twig', array('collections' => $collections));
    }

    public function addAction()
    {
    	$friendlyErrorMessage = false;
    	$technicalErrorMessage = false;
    	$successMessage = false;
    	$request = $this->getRequest();
    	$form = $this->createForm(new CollectionType());

    	if($request->getMethod() == 'POST'){
    		try{
    			$data = $form->bind($request)->getData();

	    		$collection = new Collection();

	    		$collection->setName($data['name']);

	    		$em = $this->getDoctrine()->getManager();
	    		$em->persist($collection);
	    		$em->flush();
    		}catch(\Exception $e){
    			$technicalErrorMessage = $e->getMessage();
    			$friendlyErrorMessage = 'Une erreur s\'est produite, ci-dessous l\'erreur technique à destination du développeur';
    		}
    		if(!$friendlyErrorMessage){
    			$successMessage = 'La collection a bien été enregistrée';
    		}
    	}
    	
    	return $this->render('GwengoloAdminBundle:Collection:add.html.twig', array(
    		'form' => $form->createView(),
    		'err_friend' => $friendlyErrorMessage,
    		'err_tech' => $technicalErrorMessage,
    		'successMessage' => $successMessage

    		));
    }

    public function editAction($id)
    {
        $successMessage = false;
        $err_tech = false;
        $err_friend = false;

        $collection = $this->getDoctrine()->getManager()->getRepository('GwengoloProductBundle:Collection')->find($id);
        $form = $this->createForm(new CollectionType(), $collection);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $result = $this->get('gwengolo_admin.product_manager')->updateCollection($form->bind($request));
            if(is_array($result)){
                $err_friend = "Une erreur s'est produite, ci-dessous le message d'erreur technique à transmettre au développeur:";
                $err_tech = $result['exception'];
            }else{
                $successMessage = 'La collection a bien été renommée.';
            }
        }

        return $this->render('GwengoloAdminBundle:Collection:edit.html.twig', array('form' => $form->createView(), 
            'current_action' => 'edit',
            'err_friend' => false,
            'err_tech' => false,
            'successMessage' => $successMessage,
            'collectionId' => $id));
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $collection = $em->getRepository('GwengoloProductBundle:Collection')->find($id);
        $em->remove($collection);
        $em->flush();

        return $this->redirect($this->generateUrl('gwengolo_admin_collection_search'));
    }
}
