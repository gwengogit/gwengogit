<?php

namespace Gwengolo\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Gwengolo\Bundle\ProductBundle\Entity\ProductType;
use Gwengolo\Bundle\AdminBundle\Form\Type\ProductTypeType;

class TypeController extends Controller
{

    public function searchAction()
    {
        $types = $this->getDoctrine()->getManager()->getRepository('GwengoloProductBundle:ProductType')->findAll();

    	return $this->render('GwengoloAdminBundle:Type:search.html.twig', array('types' => $types));
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $type = $em->getRepository('GwengoloProductBundle:ProductType')->find($id);
        $em->remove($type);
        $em->flush();

        return $this->redirect($this->generateUrl('gwengolo_admin_type_search'));
    }

    public function addAction()
    {
        $friendlyErrorMessage = false;
        $technicalErrorMessage = false;
        $successMessage = false;
        $request = $this->getRequest();
        $form = $this->createForm(new ProductTypeType(), new ProductType());

        if($request->getMethod() == 'POST'){
            try{
                $type = $form->bind($request)->getData();

                $em = $this->getDoctrine()->getManager();
                $em->persist($type);
                $em->flush();
            }catch(\Exception $e){
                $technicalErrorMessage = $e->getMessage();
                $friendlyErrorMessage = 'Une erreur s\'est produite, ci-dessous l\'erreur technique à transmettre au développeur:';
            }
            if(!$friendlyErrorMessage){
                $successMessage = 'Le type de produit a bien été enregistré.';
            }
        }
        
        return $this->render('GwengoloAdminBundle:Type:add.html.twig', array(
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

        $type = $this->getDoctrine()->getManager()->getRepository('GwengoloProductBundle:ProductType')->find($id);
        $form = $this->createForm(new ProductTypeType(), $type);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $result = $this->get('gwengolo_admin.product_manager')->updateProductType($form->bind($request));
            if(is_array($result)){
                $err_friend = "Une erreur s'est produite, ci-dessous le message d'erreur technique à transmettre au développeur:";
                $err_tech = $result['exception'];
            }else{
                $successMessage = 'Le type de produit a bien été renommé.';
            }
        }

        return $this->render('GwengoloAdminBundle:Type:edit.html.twig', array('form' => $form->createView(), 
            'current_action' => 'edit',
            'err_friend' => false,
            'err_tech' => false,
            'successMessage' => $successMessage,
            'typeId' => $id));
    }
}
