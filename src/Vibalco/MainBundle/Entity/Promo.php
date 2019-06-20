<?php

namespace Vibalco\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Promo
 * 
 * @ORM\Table(name="rcu_promo")
 * @ORM\Entity(repositoryClass="Vibalco\MainBundle\Repository\PromoRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="promo_type", type="string")
 * @DiscriminatorMap({
 *      "promo"="Promo", 
 *      "discount"="PromoDiscount", 
 *      "cover"="PromoCover", 
 *      "premium"="PromoPremium",
 *      "strip"="PromoStrip",
 *      "top"="PromoTop"
 * })
 * 
 * @Gedmo\TranslationEntity(class="Vibalco\MainBundle\Entity\PromoTranslation")
 */
class Promo extends BaseImage {

    /**
     * @var integer
     * 
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * 
     * @ORM\Column(name="name", type="string", length=255)
     * @Gedmo\Translatable
     */
    protected $name;

    /**
     * @var string
     * 
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     */
    protected $description;
        
    /**
     * @ORM\Column(name="showcount", type="integer")
     */
    protected $showcount;
    
    /**
     * @ORM\Column(name="showoffset", type="integer")
     */
    protected $showoffset;
    
    /**
     * @ORM\Column(name="url", type="string", nullable=true)
     * @Assert\Url
     */
    protected $url;
    
    /**
     * @ORM\Column(name="from_date", type="datetime", nullable=false)
     */
    protected $from_date;
    
    /**
     * @ORM\Column(name="to_date", type="datetime", nullable=false)
     */
    protected $to_date;
    
    /**
     * @Gedmo\Locale
     */
    protected $locale;

    // relationships
    // -------------

    /**
     * @ORM\OneToMany(targetEntity="PromoTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;
    
    /**
     * @ORM\ManyToOne(targetEntity="Homestay")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $homestay;
    
//    /**
//     * @Assert\True
//     */
//    public function isXxx() {
//        $r = $this->from_date <= $this->to_date;
//        return $r ? true : false;
//    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        
        $this->showcount = 0;
        $this->showoffset = 0;
        $this->from_date = new \DateTime('today');
        $this->to_date = new \DateTime('tomorrow');
        
    }
    
    /**
     * TODO fix
     * @return type
     */
    public function getProduct() {
        return $this->homestay;
    }

    public function __toString() {
        return $this->name;
    }
    
    protected function getImageUploadDir() {
        return 'uploads/promos/' ;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Promo
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Promo
     */
    public function setUrl($url) {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Get from_date
     *
     * @return \DateTime 
     */
    public function getFromDate() {
        return $this->from_date;
    }
    
    /**
     * Set from_date
     *
     * @return this 
     */
    public function setFromDate(\DateTime $from_date) {        
        $this->from_date = $from_date;        
        return $this;
    }

    /**
     * Set to_date
     *
     * @return \DateTime 
     */
    public function getToDate() {
        return $this->to_date;
    }

    /**
     * Set to_date
     *
     * @return this 
     */
    public function setToDate(\DateTime $to_date) {        
        $this->to_date = $to_date;        
        return $this;
    }
        
    /**
     * Set description
     *
     * @param string $description
     * @return Promo
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        
        if(!$this->description && $this->homestay) {
            return $this->homestay->getDescription();
        }
        
        return $this->description;
    }   
    
    /**
     * Set locale
     * 
     * @param type $locale
     * @return Promo
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
     * @param PromoTranslation $translations
     * @return Promo
     */
    public function addTranslation(PromoTranslation $translations) {
        if (!$this->translations->contains($translations)) {
            $this->translations[] = $translations;
            $translations->setObject($this);
        }

        return $this;
    }

    /**
     * Remove translations
     *
     * @param PromoTranslation $translations
     */
    public function removeTranslation(PromoTranslation $translations) {
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
    
    /**
     * Set discountshows
     * 
     * @return integer
     */
    public function incShowCount()
    {
        $this->showoffset++;
        return $this->showcount++;
    }    

    /**
     * Set showcount
     *
     * @param integer $showcount
     * @return Promo
     */
    public function setShowcount($showcount)
    {
        if($showcount != null) {        
            $this->showcount = $showcount;
        }
        return $this;
    }

    /**
     * Get showcount
     *
     * @return integer 
     */
    public function getShowcount()
    {
        return $this->showcount;
    }

    /**
     * Set showoffset
     *
     * @param integer $showoffset
     * @return Promo
     */
    public function setShowoffset($showoffset)
    {
        if($showoffset != null) {        
            $this->showoffset = $showoffset;
        }
        
        return $this;
    }

    /**
     * Get showoffset
     *
     * @return integer 
     */
    public function getShowoffset()
    {
        return $this->showoffset;
    }

    /**
     * Set homestay
     *
     * @param Homestay $homestay
     * @return Promo
     */
    public function setHomestay(Homestay $homestay = null)
    {
        $this->homestay = $homestay;
    
        return $this;
    }

    /**
     * Get homestay
     *
     * @return Homestay 
     */
    public function getHomestay()
    {
        return $this->homestay;
    }
    
    public function inRange($date) {
        return $this->from_date <= $date && $date <= $this->to_date;
    }
    
    /**
     * Get the image web path
     * 
     * @return string
     */
    public function getImageWebPath() {
        if(!$this->path && $this->homestay) {
            return $this->homestay->getImageWebPath();
        }
        
        return null === $this->getPath() ? null : $this->getImageUploadDir() . $this->getPath();
    }
}