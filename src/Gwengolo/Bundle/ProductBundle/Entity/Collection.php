<?php

namespace Gwengolo\Bundle\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Collection
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Gwengolo\Bundle\ProductBundle\Entity\CollectionRepository")
 */
class Collection
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="collection")
     */
    private $products;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Collection
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->setSlug($name);

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function setSlug($name)
    {
        $this->slug = $this->create_slug($name);

        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function create_slug($string)
    {
       $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $string);

       return strtolower($slug);
    }
}
