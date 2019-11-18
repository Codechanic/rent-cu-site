<?php

namespace Vibalco\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Place
 * 
 * @ORM\Table(name="place")
 * @ORM\Entity
 * @UniqueEntity(fields={"name", "municipality"})
 * 
 * @Gedmo\TranslationEntity(class="Vibalco\MainBundle\Entity\PlaceTranslation")
 */
class Place extends BaseImage {

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
     * @Gedmo\Translatable
     */
    private $name;

    /**
     * @var string
     * 
     * @ORM\Column(name="description", type="text")
     * @Gedmo\Translatable
     */
    private $description;

    /**
     * @var float
     * 
     * @ORM\Column(name="latitude", type="float", nullable=true)
     * @Assert\Regex("/^[-]*(\d+)[.(\d+)]*$/")
     */
    private $latitude;

    /**
     * @var float
     * @ORM\Column(name="longitude", type="float", nullable=true)
     * @Assert\Regex("/^[-]*(\d+)[.(\d+)]*$/")
     */
    private $longitude;
    
    
    /**
     * @var string
     * 
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     */
    private $address;

    /**
     * @var string
     * 
     * @ORM\Column(name="phones", type="string", length=255, nullable=true)
     */
    private $phones;

    /**
     * @var boolean
     * 
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @Gedmo\Locale
     */
    private $locale;

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

    // relationships
    // --------------

    /**
     * @ORM\OneToMany(targetEntity="PlaceTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    private $translations;
    
    /**
     * @ORM\ManyToOne(targetEntity="Municipality", inversedBy="places")
     * @ORM\JoinColumn(name="municipality_id", referencedColumnName="id")
     */
    protected $municipality;

    /**
     * @ORM\ManyToMany(targetEntity="Homestay", mappedBy="places")
     */
    private $homestays;

    /**
     * Constructor
     */
    public function __construct() {
        $this->enabled = true;
        
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->homestays = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->name;
    }
    
    
    protected function getImageUploadDir() {
        return 'uploads/main/place/' ;
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
     * @return Place
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
     * @return Place
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
     * Set latitude
     *
     * @param float $latitude
     * @return Place
     */
    public function setLatitude($latitude) {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float 
     */
    public function getLatitude() {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     * @return Place
     */
    public function setLongitude($longitude) {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float 
     */
    public function getLongitude() {
        return $this->longitude;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Place
     */
    public function setEnabled($enabled) {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled() {
        return $this->enabled;
    }

    /**
     * Test enabled
     *
     * @return boolean 
     */
    public function isEnabled() {
        return $this->enabled;
    }

    /**
     * Set locale
     * 
     * @param type $locale
     * @return Place
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
     * Set meta_title
     *
     * @param string $metaTitle
     * @return Place
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
     * @return Place
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
     * @return Place
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

    /**
     * Add translations
     *
     * @param PlaceTranslation $translations
     * @return Place
     */
    public function addTranslation(PlaceTranslation $translations) {
        if (!$this->translations->contains($translations)) {
            $this->translations[] = $translations;
            $translations->setObject($this);
        }

        return $this;
    }

    /**
     * Remove translations
     *
     * @param PlaceTranslation $translations
     */
    public function removeTranslation(PlaceTranslation $translations) {
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
     * Set municipality
     *
     * @param Municipality $municipality
     * @return Place
     */
    public function setMunicipality(Municipality $municipality = null) {
        $this->municipality = $municipality;

        return $this;
    }

    /**
     * Get municipality
     *
     * @return Municipality 
     */
    public function getMunicipality() {
        return $this->municipality;
    }

    /**
     * Add homestays
     *
     * @param Homestay $homestays
     * @return Place
     */
    public function addHomestay(Homestay $homestays) {
        $this->homestays[] = $homestays;

        return $this;
    }

    /**
     * Remove homestays
     *
     * @param Homestay $homestays
     */
    public function removeHomestay(Homestay $homestays) {
        $this->homestays->removeElement($homestays);
    }

    /**
     * Get homestays
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHomestays() {
        return $this->homestays;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Place
     */
    public function setAddress($address) {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Set phones
     *
     * @param string $phones
     * @return Place
     */
    public function setPhones($phones) {
        $this->phones = $phones;

        return $this;
    }

    /**
     * Get phones
     *
     * @return string 
     */
    public function getPhones() {
        return $this->phones;
    }
}
