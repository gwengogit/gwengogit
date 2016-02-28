<?php

namespace Gwengolo\Bundle\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Gwengolo\Bundle\ProductBundle\Entity\Product;
use Gwengolo\Bundle\ProductBundle\Entity\Newsletter;
use Gwengolo\Bundle\ProductBundle\Manager\CookieManager;
use Symfony\Component\HttpFoundation\Cookie;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('GwengoloProductBundle:Product')->findBy(array(), array('id' => 'DESC'), 8);;
        $firstLineProducts = array();
        $i = 0;
        foreach($products as $currentProduct)
        {
            if($i < 4)
            {
                $firstLineProducts[] = $currentProduct;
                $i++;
            }else{
                break;
            }
        }
        $secondLineProducts = array();
        $i = 0;
        foreach($products as $currentProduct)
        {
            if($i > 3)
            {
                $secondLineProducts[] = $currentProduct;
            }

            $i++;
        }

        return $this->render('GwengoloFrontBundle:home:home.html.twig', array('firstLine' => $firstLineProducts, 'secondLine' => $secondLineProducts));
    }

    public function collectionsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $collections = $em->getRepository('GwengoloProductBundle:Collection')->findAll();

        

    	return $this->render('GwengoloFrontBundle:home:collection.html.twig', array('collections' => $collections));
    }

    public function contactAction()
    {
        $cookieManager = $this->get('gwengolo_product.cookie_manager');
        $lastSeenProducts = $cookieManager->getLastSeenProducts($this->getRequest());

    	return $this->render('GwengoloFrontBundle:home:contact.html.twig', array('name' => 'yhoo', 'lastSeenProducts' => $lastSeenProducts));
    }

    public function collectionContentAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $collection = $em->getRepository('GwengoloProductBundle:Collection')->findBy(array('slug' => $slug));
    	
    	return $this->render('GwengoloFrontBundle:home:collectionContent.html.twig', array('products' => $collection[0]->getProducts()));
    }


    public function productAction($productSlug)
    {
        
        $cookieManager = $this->get('gwengolo_product.cookie_manager');
        $lastSeenProducts = $cookieManager->getLastSeenProducts($this->getRequest());

        $product = $this->getDoctrine()->getManager()->getRepository('GwengoloProductBundle:Product')->findOneBy(array('slug' => $productSlug));
        $sameCatProducts = $product->getType()->getProducts();
        
        $response = $this->render('GwengoloFrontBundle:home:product.html.twig', array('lastSeenProducts' => $lastSeenProducts, 'product' => $product, 'sameCatProducts' => $sameCatProducts, 'productId' => $product->getId()));
    	$response = $cookieManager->updateProductCookies($product, $this->getRequest(), $response);
        
        return $response;      
    }

    public function brandAction()
    {
    	return $this->render('GwengoloFrontBundle:home:brand.html.twig', array('name' => 'yhoo'));
    }

    public function findUsAction()
    {
        return $this->render('GwengoloFrontBundle:home:findus.html.twig', array('name' => 'yhoo'));
    }

    public function searchAction()
    {
        if(isset($_POST['search_product_label'])) {
            $em = $this->getDoctrine()->getManager();
            $result =   $em->getRepository("GwengoloProductBundle:Product")->createQueryBuilder('p')
                            ->join('p.collection', 'c')
                            ->where('p.name LIKE :search')
                            ->orWhere('c.name LIKE :search')
                            ->setParameter('search', '%'.$_POST['search_product_label'].'%')
                            ->getQuery()
                            ->getResult();
        }
        
        return $this->render('GwengoloFrontBundle:home:search.html.twig', array('products' => $result));
    }

    public function recordEmailAction()
    {
        if(isset($_POST['name']) && isset($_POST['email'])) {
            $em = $this->getDoctrine()->getManager();

            $sameEmail = $em->getRepository('GwengoloProductBundle:Newsletter')->findOneBy(array('email' => $_POST['email']));
            if(empty($sameEmail)) {
                $nl = new Newsletter();
                $nl->setName($_POST['name']);
                $nl->setEmail($_POST['email']);
                $nl->setRecorded(time());

                $em->persist($nl);
                $em->flush();
                
                echo "Votre adresse email a bien été enregistrée."; exit;
            } else {
                echo "Cette adresse email est déjà enregistrée."; exit;
            }
            
        }

        echo "Un problème technique a empêché l'enregistrement de votre adresse email."; exit;

    }

    public function sendMessageAction()
    {

        
        $message = \Swift_Message::newInstance()
        ->setSubject('Test email depuis site gwengolo')
        ->setFrom('send@matthieurolland.io')
        ->setTo('matthieu.rolland1985@gmail.com')
        ->setBody('Le test est bien arrivé');
    ;
    $this->get('mailer')->send($message);

        print_r($_POST); exit;

        Echo 'Votre message a bien été envoyé.';
    }
}
