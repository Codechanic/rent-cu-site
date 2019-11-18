<?php

namespace Vibalco\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Province
 *
 * @ORM\Table(name="province")
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="Vibalco\MainBundle\Entity\ProvinceTranslation")
 * @ORM\HasLifecycleCallbacks
 */
class Province extends BaseImage {

    /**
     * @var integer $id
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     * @ORM\Column(name="name", type="string", unique=true)
     */
    protected $name;
    
    /**
     * @var integer $order
     * @ORM\Column(name="norder", type="integer", nullable=true)
     */
    protected $order;
    
    /**
     * @var string $description
     * @Gedmo\Translatable
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @OneToMany(targetEntity="Municipality", mappedBy="province", cascade={"persist", "remove"})
     */
    protected $municipalities;
    
    // meta tags
    // ---------

    /**
     * @var string
     * 
     * @ORM\Column(name="meta_title", type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     */
    private $meta_title;

    /**
     * @var string
     * 
     * @ORM\Column(name="meta_description", type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     */
    private $meta_description;

    /**
     * @var string
     * 
     * @ORM\Column(name="meta_keywords", type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     */
    private $meta_keywords;

    /**
     * @Gedmo\Locale
     */
    private $locale;

    /**
     * @ORM\OneToMany(
     *   targetEntity="ProvinceTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;

    /**
     * Constructor
     */

    /**
     * Constructor
     */
    public function __construct() {
        $this->municipalities = new \Doctrine\Common\Collections\ArrayCollection();
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString() {
        return $this->name;        
    }
    
    protected function getImageUploadDir() {
        return 'uploads/province/' ;
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
     * @return Province
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
     * Set description
     *
     * @param string $description
     * @return Province
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
        return $this->description;
    }
    
    /**
     * Add municipalities
     *
     * @param \Vibalco\MainBundle\Entity\Municipality $municipalities
     * @return Province
     */
    public function addMunicipalitie(\Vibalco\MainBundle\Entity\Municipality $municipalities) {
        $this->municipalities[] = $municipalities;

        return $this;
    }

    /**
     * Remove municipalities
     *
     * @param \Vibalco\MainBundle\Entity\Municipality $municipalities
     */
    public function removeMunicipalitie(\Vibalco\MainBundle\Entity\Municipality $municipalities) {
        $this->municipalities->removeElement($municipalities);
    }

    /**
     * Get municipalities
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMunicipalities() {
        return $this->municipalities;
    }

    public function getTranslations() {
        return $this->translations;
    }

    public function addTranslation(ProvinceTranslation $t) {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function removeTranslation(ProvinceTranslation $t) {
        $this->translations->removeElement($t);
    }

    /**
     * Set translatable locale
     * 
     * @param type $locale
     * @return Post
     */
    public function setTranslatableLocale($locale) {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Set locale
     * 
     * @param type $locale
     * @return Post
     */
    public function setLocale($locale) {
        $this->locale = $locale;
        $this->setTranslatableLocale($locale);

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

    public function getOrder() {
        return $this->order;
    }

    public function setOrder($order) {
        $this->order = $order;
    }

    /**
     * Set meta_title
     *
     * @param string $metaTitle
     * @return Destination
     */
    public function setMetaTitle($metaTitle) {
        $this->meta_title = $metaTitle;

        return $this;
    }

    /**
     * Get meta_title
     *
     * @return string 
     */
    public function getMetaTitle() {
        return $this->meta_title;
    }

    /**
     * Set meta_description
     *
     * @param string $metaDescription
     * @return Destination
     */
    public function setMetaDescription($metaDescription) {
        $this->meta_description = $metaDescription;

        return $this;
    }

    /**
     * Get meta_description
     *
     * @return string 
     */
    public function getMetaDescription() {
        return $this->meta_description;
    }

    /**
     * Set meta_keywords
     *
     * @param string $metaKeywords
     * @return Destination
     */
    public function setMetaKeywords($metaKeywords) {
        $this->meta_keywords = $metaKeywords;

        return $this;
    }

    /**
     * Get meta_keywords
     *
     * @return string 
     */
    public function getMetaKeywords() {
        return $this->meta_keywords;
    }
}