<?php

namespace Gwengolo\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Gwengolo\Bundle\AdminBundle\Form\Type\ImageType;
use Gwengolo\Bundle\ProductBundle\Entity\Image;
use Gwengolo\Bundle\ProductBundle\Entity\Product;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageController extends Controller
{
    public function imageAction($id)
    {
        $friendlyErrorMessage = false;
        $technicalErrorMessage = false;
        $successMessage = false;

        $product = $this->getDoctrine()->getManager()->getRepository('GwengoloProductBundle:Product')->find($id);

        $form = $this->createForm(new ImageType()); 
        $formOthers = $this->createForm(new ImageType()); 

        return $this->render('GwengoloAdminBundle:Image:add.html.twig', array(
            'form' => $form->createView(),
            'formOthers' => $formOthers->createView(),
            'err_friend' => $friendlyErrorMessage,
            'err_tech' => $technicalErrorMessage,
            'successMessage' => $successMessage,
            'product' => $product,
            'main_image' => $this->getCurrentMainImage($product),
            'other_images' => $this->getOtherImagesList($product)

            ));
    }

    public function othersUploadAction($id)
    {
        $request = $this->getRequest();

        if($request->getMethod() == 'POST'){

            $product = $this->getDoctrine()->getManager()->getRepository('GwengoloProductBundle:Product')->find($id);
            $form = $this->createForm(new ImageType());
            $form->bind($request);

            $file = $form['image']->getData();
            
            $fileDirectory = $this->get('kernel')->getRootDir() . '/../web/products_img/'.$id.'/others/';
            
            $fileName = uniqid($product->getSlug().'_');
            $extension = $file->guessExtension();

            if($file->move($fileDirectory, $fileName.'.'.$extension)){
                $em = $this->getDoctrine()->getManager();
                
                
                
                $image = new Image();
                $image->setName($fileName.'.'.$extension);
                $image->setIsMain(false);
                $image->setProduct($product);
                
                $em->persist($image);
                $em->flush();
            }
        }

        return $this->redirect($this->generateUrl('gwengolo_admin_image', array('id' => $product->getId())));
    }

    public function mainUploadAction($id)
    {
        $request = $this->getRequest();
        
        if($request->getMethod() == 'POST'){

            $product = $this->getDoctrine()->getManager()->getRepository('GwengoloProductBundle:Product')->find($id);
            $form = $this->createForm(new ImageType());
            $form->bind($request);

            $file = $form['image']->getData();
            $dir = $this->get('kernel')->getRootDir() . '/../web/products_img/'.$id.'/main/';
            $fileDirectory = $this->get('kernel')->getRootDir() . '/../web/products_img/'.$id.'/main/';
            $this->emptyDir($fileDirectory);
            $fileName = uniqid($product->getSlug().'_');
            $extension = $file->guessExtension();

            if($file->move($fileDirectory, $fileName.'.'.$extension)){
                $em = $this->getDoctrine()->getManager();
                
                $this->removeOldImageFromDb($em, $product);
                
                $image = new Image();
                $image->setName($fileName.'.'.$extension);
                $image->setIsMain(true);
                $image->setProduct($product);
                
                $em->persist($image);
                $em->flush();
            }
        }
        return $this->redirect($this->generateUrl('gwengolo_admin_image', array('id' => $product->getId())));
    }

    public function deleteAction($id, $productId)
    {
        $em = $this->getDoctrine()->getManager();
        $image = $em->getRepository('GwengoloProductBundle:Image')->find($id);
        $image_path = $this->get('kernel')->getRootDir() . '/../web/products_img/'.$productId.'/others/'.$image->getName();
        unlink($image_path);
        $cacheImagePath = $this->get('kernel')->getRootDir() . '/../web/media/cache/big_product_pic/products_img/'.$productId.'/others/'.$image->getName();
        unlink($cacheImagePath);

        $em->remove($image);
        $em->flush();

        return $this->redirect($this->generateUrl('gwengolo_admin_image', array('id' => $productId)));
    }

    public function uploadImg($file, $isMain = true)
    {
        $dir = $this->get('kernel')->getRootDir() . '/../web/products_img/'.$id.'/main/';

        if($isMain){
            $fileDirectory = $this->get('kernel')->getRootDir() . '/../web/products_img/'.$id.'/main/';
        }else{
            $fileDirectory = $this->get('kernel')->getRootDir() . '/../web/products_img/'.$id.'/others/';
        }
        
        $this->emptyDir($fileDirectory);
        $fileName = uniqid($product->getSlug().'_');
        $extension = $file->guessExtension();

        return $file->move($fileDirectory, $fileName.'.'.$extension);
    }

    public function emptyDir($dir)
    {  
        try{
            $files = scandir($dir);
        }catch(\Exception $e){
            return;
        }
        
        foreach($files as $key => $file)
        {
            if($key != 0 && $key != 1){
                unlink($dir.$file);
            }
            
        }

        return;
    }

    public function removeOldImageFromDb($em, $product)
    {
        $oldMainImage = $em->getRepository('GwengoloProductBundle:Image')->findBy(array('product' => $product->getId(), 'isMain' => true));

        if(is_array($oldMainImage)){
            foreach($oldMainImage as $image){
                $em->remove($image);
            }
        }else{
            $em->remove($oldMainImage);
        }

        $em->flush();

        return;
    }

    public function getCurrentMainImage($product)
    {
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository('GwengoloProductBundle:Image')->findOneBy(array('product' => $product->getId(), 'isMain' => true));
    }

    public function getOtherImagesList($product)
    {
        $em = $this->getDoctrine()->getManager();
        $images = $em->getRepository('GwengoloProductBundle:Image')->findBy(array('product' => $product->getId(), 'isMain' => false));

        return $images;
    }
}
