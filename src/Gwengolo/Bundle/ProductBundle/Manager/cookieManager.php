<?php

namespace Gwengolo\Bundle\ProductBundle\Manager;

use Gwengolo\Bundle\Bundle\ProductBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Cookie;

class CookieManager
{
	protected $em;

	public function __construct($em)
	{
		$this->em = $em;
	}

	public function getCookies($request, $key)
	{
		return $request->cookies->get($key);
	}

	public function updateProductCookies($product, $request, $response)
	{
		//on recupere les produits stockés dans les cookies
		$currentSeenProducts = $this->getCookies($request, 'gwengoSeenProduct');
		if($currentSeenProducts)
		{
			$currentSeenProducts = json_decode($currentSeenProducts, true);
			//on ajoute le nouveau produit
			$currentSeenProducts = $this->addProduct($product, $currentSeenProducts);
			//on supprime produits en trop le cas echeant
			$currentSeenProducts = $this->removeOldestProduct($currentSeenProducts);
		}
		else
		{
			$currentSeenProducts = array($product->getId());
		}
		//on retourne la reponse avec les cookies mis a jour
		$response->headers->clearCookie('gwengoSeenProduct');
		return $this->setCookies($response, 'gwengoSeenProduct', json_encode($currentSeenProducts));
	}

	protected function addProduct($product, $currentSeenProducts)
	{
		// On n'ajoute pas le produit s'il est déjà dans la liste (pour éviter les doublons inutiles)
		if(!in_array($product->getId(), $currentSeenProducts)) {
			array_push($currentSeenProducts, $product->getId());
		}

		return $currentSeenProducts;
	}

	protected function removeOldestProduct($currentSeenProducts)
	{
		$currentSeenProducts = array_reverse($currentSeenProducts);
		foreach($currentSeenProducts as $key => $value)
		{
			if($key > 5)
			{
				unset($currentSeenProducts[$key]);
			}
		}

		return array_reverse($currentSeenProducts);
	}

	protected function setCookies($response, $key, $value)
	{
		$response->headers->setCookie(new Cookie($key, $value, time()+1200));

		return $response;
	}

	public function getLastSeenProducts($request)
	{
        $currentCookies = $request->cookies->get('gwengoSeenProduct'); 
        if($currentCookies)
        {
        	$idArray = json_decode($currentCookies, true);
        	$allProducts = $this->em->getRepository('GwengoloProductBundle:Product')->findAll();
        	$lastSeenProducts = array();
        	foreach($allProducts as $currentProduct)
        	{
        		if(in_array($currentProduct->getId(), $idArray))
        		{
        			$lastSeenProducts[$currentProduct->getId()] = $currentProduct;
        		}
        	}

        	$result = array();
        	foreach($idArray as $currentId)
        	{
        		$result[] = $lastSeenProducts[$currentId];
        	}

        	return array_reverse($result);
        }

        return false;
	}

	
}