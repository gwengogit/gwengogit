<?php

namespace Gwengolo\Bundle\AdminBundle\Manager;

use Gwengolo\Bundle\Bundle\ProductBundle\Entity\Product;

class ProductManager
{
	protected $em;

	public function __construct($em)
	{
		$this->em = $em;
	}

	public function saveNewProduct($formData, $isVisible = true)
	{
		$new_product = $formData->getData();
	    $new_product->setIsVisible($isVisible);
	    $this->persistAndFlush($new_product);
	    
	}

	public function updateProduct($formData)
	{
		$product = $formData->getData();
		$this->persistAndFlush($product);
	}

	public function deleteProduct($product)
	{
		$this->removeAndFlush($product);
	}

	public function updateProductType($formData)
	{
		try{
			$productType = $formData->getData();
			$this->persistAndFlush($productType);

			return true;
		}catch(\Exception $e){
			return array('exception' => $e->getMessage());
		}		
	}

	public function updateCollection($formData)
	{
		try{
			$collection = $formData->getData();
			$this->persistAndFlush($collection);

			return true;
		}catch(\Exception $e){
			return array('exception' => $e->getMessage());
		}
	}

	public function updateColor($formData)
	{
		try{
			$color = $formData->getData();
			$this->persistAndFlush($color);

			return true;
		}catch(\Exception $e){
			return array('exception' => $e->getMessage());
		}
	}

	public function updateSpecificity($formData)
	{
		try{
			$specificity = $formData->getData();
			$this->persistAndFlush($specificity);

			return true;
		}catch(\Exception $e){
			return array('exception' => $e->getMessage());
		}
	}

	public function persistAndFlush($entity)
	{
		$this->em->persist($entity);
	    $this->em->flush();
	}

	public function removeAndFlush($entity)
	{
		$this->em->remove($entity);
		$this->em->flush();
	}
}