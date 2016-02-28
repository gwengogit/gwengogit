<?php

namespace Gwengolo\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Gwengolo\Bundle\AdminBundle\Form\Type\ColorType;
use Gwengolo\Bundle\ProductBundle\Entity\Color;

class ColorController extends Controller
{
    public function addAction()
    {
        $friendlyErrorMessage = false;
        $technicalErrorMessage = false;
        $successMessage = false;
        $request = $this->getRequest();
        $form = $this->createForm(new ColorType(), new Color());

        if($request->getMethod() == 'POST'){
            try{
                $color = $form->bind($request)->getData();

                $em = $this->getDoctrine()->getManager();
                $em->persist($color);
                $em->flush();
            }catch(\Exception $e){
                $technicalErrorMessage = $e->getMessage();
                $friendlyErrorMessage = 'Une erreur s\'est produite, ci-dessous l\'erreur technique à transmettre au développeur:';
            }
            if(!$friendlyErrorMessage){
                $successMessage = 'La nouvelle couleur a bien été enregistrée.';
            }
        }
        
        return $this->render('GwengoloAdminBundle:Color:add.html.twig', array(
            'form' => $form->createView(),
            'err_friend' => $friendlyErrorMessage,
            'err_tech' => $technicalErrorMessage,
            'successMessage' => $successMessage

            ));
    }

    public function searchAction()
    {
        $colors = $this->getDoctrine()->getManager()->getRepository('GwengoloProductBundle:Color')->findAll();

        return $this->render('GwengoloAdminBundle:Color:search.html.twig', array('colors' => $colors));
    }

    public function editAction($id)
    {
        $successMessage = false;
        $err_tech = false;
        $err_friend = false;

        $color = $this->getDoctrine()->getManager()->getRepository('GwengoloProductBundle:Color')->find($id);
        $form = $this->createForm(new ColorType(), $color);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $result = $this->get('gwengolo_admin.product_manager')->updateColor($form->bind($request));
            if(is_array($result)){
                $err_friend = "Une erreur s'est produite, ci-dessous le message d'erreur technique à transmettre au développeur:";
                $err_tech = $result['exception'];
            }else{
                $successMessage = 'La couleur a bien été modifiée.';
            }
        }

        return $this->render('GwengoloAdminBundle:Color:edit.html.twig', array('form' => $form->createView(), 
            'current_action' => 'edit',
            'err_friend' => false,
            'err_tech' => false,
            'successMessage' => $successMessage,
            'colorId' => $id));
    }
    

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $color = $em->getRepository('GwengoloProductBundle:Color')->find($id);
        $em->remove($color);
        $em->flush();

        return $this->redirect($this->generateUrl('gwengolo_admin_color_search'));
    }
}
