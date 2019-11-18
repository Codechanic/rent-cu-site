<?php

namespace Vibalco\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AntiqueCar
 * 
 * @ORM\Table(name="rcu_antiquecar")
 * @ORM\Entity(repositoryClass="Vibalco\MainBundle\Repository\AntiqueCarRepository")
 * 
 * @Gedmo\TranslationEntity(class="Vibalco\MainBundle\Entity\AntiqueCarTranslation")
 */
class AntiqueCar extends BaseImage {

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
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"name", "owner"})
     */
    private $slug;
    
    /**
     * @var boolean
     * 
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;
    
    /**
     * @var integer
     * 
     * @ORM\Column(name="rank", type="integer", nullable=false)
     */
    private $rank;

    /**
     * @var string
     * 
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * 
     * @ORM\Column(name="owner", type="string", length=255)
     */
    private $owner;
    
    /**
     * @var boolean
     * 
     * @ORM\Column(name="hardcover", type="boolean")
     */
    private $hardcover;
            
    /**
     * @var boolean
     * 
     * @ORM\Column(name="convertible", type="boolean")
     */
    private $convertible;
    
    /**
     * @var integer
     * 
     * @ORM\Column(name="year", type="integer")
     */
    private $year;    

    /**
     * @var string
     * 
     * @ORM\Column(name="color", type="string", length=255)
     * @Gedmo\Translatable
     */
    private $color;
    
    /**
     * @var float
     *
     * @ORM\Column(name="pricehour", type="float")
     */
    private $pricehour;
    
    /**
     * @var float
     *
     * @ORM\Column(name="price8hour", type="float")
     */
    private $price8hour;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="phones", type="string", length=255, nullable=true)
     */
    private $phones;    

    /**
     * @var string
     * 
     * @ORM\Column(name="email", type="string", length=255, unique=true, nullable=true)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="Municipality", inversedBy="antiquecars")
     * @ORM\JoinColumn(name="municipality_id", referencedColumnName="id")
     */
    protected $municipality;
    
    /**
     * @ORM\ManyToOne(targetEntity="AntiqueCarBrand", inversedBy="cars")
     * @ORM\JoinColumn(name="carbrand_id", referencedColumnName="id")
     */
    private $brand;
    
    /**
     * @Gedmo\Locale
     */
    private $locale;

    // relationships
    // -------------

    /**
     * @ORM\OneToMany(targetEntity="AntiqueCarTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    private $translations;

    public function __construct() {
        $this->enabled = true;
        $this->rank = 0;
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->name;
    }
    
    protected function getImageUploadDir() {
        return 'uploads/main/antiquecar/' ;
    }
    
    public function getId() {
        return $this->id;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getName() {
        return $this->name;
    }

    public function setOwner($owner) {
        $this->owner = $owner;
        return $this;
    }

    public function getOwner() {
        return $this->owner;
    }
    
    public function getHardcover() {
        return $this->hardcover;
    }

    public function setHardcover($hardcover) {
        $this->hardcover = $hardcover;
        return $this;
    }
    
    public function getEnabled() {
        return $this->enabled;
    }

    public function setEnabled($enabled) {
        $this->enabled = $enabled;
        return $this;
    }
    
    public function setRank($rank) {
        $this->rank = $rank;
        return $this;
    }

    public function getRank() {
        return $this->rank;
    }

    public function getConvertible() {
        return $this->convertible;
    }

    public function setConvertible($convertible) {
        $this->convertible = $convertible;
        return $this;
    }

    public function getYear() {
        return $this->year;
    }

    public function setYear($year) {
        $this->year = $year;
        return $this;
    }

    public function getColor() {
        return $this->color;
    }

    public function setColor($color) {
        $this->color = $color;
        return $this;
    }

    public function getPricehour() {
        return $this->pricehour;
    }

    public function setPricehour($pricehour) {
        $this->pricehour = $pricehour;
        return $this;
    }

    public function getPrice8hour() {
        return $this->price8hour;
    }

    public function setPrice8hour($price8hour) {
        $this->price8hour = $price8hour;
        return $this;
    }

    public function getPhones() {
        return $this->phones;
    }

    public function setPhones($phones) {
        $this->phones = $phones;
        return $this;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }    
    
    /**
     * Set municipality
     *
     * @param \Vibalco\MainBundle\Entity\Municipality $municipality
     */
    public function setMunicipality(\Vibalco\MainBundle\Entity\Municipality $municipality = null) {
        $this->municipality = $municipality;

        return $this;
    }

    /**
     * Get municipality
     *
     * @return \Vibalco\MainBundle\Entity\Municipality 
     */
    public function getMunicipality() {
        return $this->municipality;
    }
    
    /**
     * Set brand
     *
     * @param \Vibalco\MainBundle\Entity\AntiqueCarBrand $brand
     */
    public function setBrand(\Vibalco\MainBundle\Entity\AntiqueCarBrand $brand = null) {
        $this->brand = $brand;
        return $this;
    }
    
    /**
     * Get brand
     *
     * @return \Vibalco\MainBundle\Entity\AntiqueCarBrand 
     */
    public function getBrand() {
        return $this->brand;
    }
    
    /**
     * Set slug
     *
     * @param string $slug
     * @return AntiqueCar
     */
    public function setSlug($slug) {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug() {
        return $this->slug;
    }
    
    /**
     * Set locale
     * 
     * @param type $locale
     * @return AntiqueCar
     */
    public function setLocale($locale) {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string 
     */
    public function getLocale() {
        return $this->locale;
    }

    /**
     * Add translations
     *
     * @param AntiqueCarTranslation $translations
     * @return AntiqueCar
     */
    public function addTranslation(AntiqueCarTranslation $translations) {
        if (!$this->translations->contains($translations)) {
            $this->translations[] = $translations;
            $translations->setObject($this);
        }

        return $this;
    }

    /**
     * Remove translations
     *
     * @param AntiqueCarTranslation $translations
     */
    public function removeTranslation(AntiqueCarTranslation $translations) {
        $this->translations->removeElement($translations);
    }

    /**
     * Get translations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTranslations() {
        return $this->translations;
    }
}