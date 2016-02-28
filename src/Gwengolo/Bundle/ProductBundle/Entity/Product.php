<?php

namespace Gwengolo\Bundle\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Product
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Gwengolo\Bundle\ProductBundle\Entity\ProductRepository")
 * @GRID\Source(columns="id, name, collection.name, type.name, color.name",groupBy={"id"})
 */
class Product
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Grid\Column(filterable=false, visible=true, export=false)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Grid\Column(title="nom", field="name", operatorsVisible=false, visible=true, filterable=true)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isVisible", type="boolean")
     */
    private $isVisible;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="Collection", inversedBy="products")
     * @ORM\JoinColumn(name="collection_id", referencedColumnName="id")
     * @Grid\Column(title="collection", field="collection.name", filter="select", operatorsVisible=false, visible=true, filterable=true)
     */
    private $collection;

    /**
     * @ORM\ManyToOne(targetEntity="ProductType", inversedBy="products")
     * @Grid\Column(title="type de produit", field="type.name", filter="select", operatorsVisible=false, visible=true, filterable=true)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="Image", mappedBy="product", cascade={"remove"})
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity="Color", inversedBy="products")
     * @Grid\Column(title="Couleur du cadre", field="color.name", visible=true, filterable=false)
     */
    private $color;

    /**
     * @ORM\ManyToMany(targetEntity="Specificity", cascade="persist")
     */
    private $specificities;

    public function __construct()
    {
        $this->specificities = new ArrayCollection();
    }

    public function addSpecificity($specificity)
    {
        $this->specificities->add($specificity);

        return $this;
    }

    public function removeSpecificity($specificity)
    {
        $this->specificities->removeElement($specificity);

        return $this;
    }

    public function setSpecificities($specificities)
    {
        $this->specificities = $specificities;

        return $this;
    }

    public function getSpecificities()
    {
        return $this->specificities;
    }


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
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->slug = $this->create_slug($name);

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

    /**
     * Set isVisible
     *
     * @param boolean $isVisible
     * @return Product
     */
    public function setIsVisible($isVisible)
    {
        $this->isVisible = $isVisible;

        return $this;
    }

    /**
     * Get isVisible
     *
     * @return boolean 
     */
    public function getIsVisible()
    {
        return $this->isVisible;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Product
     */
    public function setSlug($name)
    {
        $this->slug = $this->create_slug($name);

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    public function setCollection($collection){
        $this->collection = $collection;

        return $this;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function create_slug($string)
    {
       $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $string);

       return strtolower($slug);
    }

    public function getImages($includeMainImage = TRUE)
    {
        if (!$includeMainImage) {
            $result = array();
            foreach($this->images as $image) {
                if(!$image->getIsMain()) {
                    $result[] = $image;
                }
            }

            return $result;
        }

        return $this->images;
    }

    public function getMainImage()
    {
        foreach($this->images as $image){
            if($image->getIsMain()){

                return $image;
            }
        }
    }
}
