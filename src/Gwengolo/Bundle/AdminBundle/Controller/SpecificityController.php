<?php

namespace Gwengolo\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Gwengolo\Bundle\AdminBundle\Form\Type\SpecificityType;
use Gwengolo\Bundle\ProductBundle\Entity\Specificity;

class SpecificityController extends Controller
{
    public function addAction()
    {
        $friendlyErrorMessage = false;
        $technicalErrorMessage = false;
        $successMessage = false;
        $request = $this->getRequest();
        $form = $this->createForm(new SpecificityType(), new Specificity());

        if($request->getMethod() == 'POST'){
            try{
                $specificity = $form->bind($request)->getData();

                $em = $this->getDoctrine()->getManager();
                $em->persist($specificity);
                $em->flush();
            }catch(\Exception $e){
                $technicalErrorMessage = $e->getMessage();
                $friendlyErrorMessage = 'Une erreur s\'est produite, ci-dessous l\'erreur technique à transmettre au développeur:';
            }
            if(!$friendlyErrorMessage){
                $successMessage = 'La nouvelle spécificité a bien été enregistrée.';
            }
        }
        
        return $this->render('GwengoloAdminBundle:Specificity:add.html.twig', array(
            'form' => $form->createView(),
            'err_friend' => $friendlyErrorMessage,
            'err_tech' => $technicalErrorMessage,
            'successMessage' => $successMessage

            ));
    }

    public function searchAction()
    {
        $specificities = $this->getDoctrine()->getManager()->getRepository('GwengoloProductBundle:Specificity')->findAll();

        return $this->render('GwengoloAdminBundle:Specificity:search.html.twig', array('specificities' => $specificities));
    }

    public function editAction($id)
    {
        $successMessage = false;
        $err_tech = false;
        $err_friend = false;

        $specificity = $this->getDoctrine()->getManager()->getRepository('GwengoloProductBundle:Specificity')->find($id);
        $form = $this->createForm(new SpecificityType(), $specificity);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $result = $this->get('gwengolo_admin.product_manager')->updateSpecificity($form->bind($request));
            if(is_array($result)){
                $err_friend = "Une erreur s'est produite, ci-dessous le message d'erreur technique à transmettre au développeur:";
                $err_tech = $result['exception'];
            }else{
                $successMessage = 'La spécificité a bien été modifiée.';
            }
        }

        return $this->render('GwengoloAdminBundle:Specificity:edit.html.twig', array('form' => $form->createView(), 
            'current_action' => 'edit',
            'err_friend' => false,
            'err_tech' => false,
            'successMessage' => $successMessage,
            'specificityId' => $id));
    }
    

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $specificity = $em->getRepository('GwengoloProductBundle:Specificity')->find($id);
        $em->remove($specificity);
        $em->flush();

        return $this->redirect($this->generateUrl('gwengolo_admin_specificity_search'));
    }
}
