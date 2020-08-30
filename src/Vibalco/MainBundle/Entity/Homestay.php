<?php

namespace Vibalco\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Vibalco\AdminBundle\Entity\User;
use Vibalco\GalleryBundle\Model\GalleryInterface;
use Vibalco\MainBundle\Model\CodeInterface;

/**
 * Homestay
 * 
 * @ORM\Table(name="homestay")
 * @ORM\Entity(repositoryClass="Vibalco\MainBundle\Repository\HomestayRepository")
 * 
 * @Gedmo\TranslationEntity(class="Vibalco\MainBundle\Entity\HomestayTranslation")
 */
class Homestay extends BaseImage implements GalleryInterface, CodeInterface {

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
     * @ORM\Column(name="slug", type="string", length=255)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @var string
     * 
     * @ORM\Column(name="name", type="string", length=255, unique=true )
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
     * @var string
     * 
     * @ORM\Column(name="owner", type="string", length=255)
     */
    private $owner;

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
     * @var string
     * 
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     * @Assert\Email()
     */
    private $email;
    
    /**
     * @ORM\Column(name="account", type="string", length=255, nullable=true)
     */
    protected $account;

    /**
     * @var integer
     * 
     * @ORM\Column(name="rooms", type="integer")
     */
    private $rooms;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $breakfastprice;
    
    /**
     * @ORM\Column(name="bedprice", type="float", nullable=true)
     */
    private $extrabedprice;
    
    /**
     * @ORM\Column(type="float", scale=2, nullable=true)
     */
    private $bedchildprice;    
    
    /**
     * @var boolean
     * 
     * @ORM\Column(name="promo", type="boolean")
     */
    private $promo;

    /**
     * @var float
     * 
     * @ORM\Column(name="comision", type="float")
     */
    private $comision;

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
     * @var boolean
     * 
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @var boolean
     * 
     * @ORM\Column(name="showcontact", type="boolean")
     */
    private $showcontact;

    /**
     * @var integer
     * 
     * @ORM\Column(name="rank", type="integer", nullable=false)
     */
    private $rank;

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
    // -------------

    /**
     * @ORM\OneToMany(targetEntity="HomestayTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    private $translations;

    /**
     * @ORM\ManyToOne(targetEntity="AcommodationType", inversedBy="homestays")
     * @ORM\JoinColumn(name="acommodation_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $acommodation;    

    /**
     * @ORM\ManyToMany(targetEntity="Interest", inversedBy="homestays")
     * @ORM\JoinTable(name="homestays_interests")
     */
    private $interests;
    
    /**
     * @ORM\OneToMany(targetEntity="HomestayPrice", mappedBy="homestay", cascade={"remove"})
     */
    private $prices;

    /**
     * @ORM\ManyToMany(targetEntity="HomestayFreeService", inversedBy="homestays")
     * @ORM\JoinTable(name="homestays_freeservices")
     */
    private $freeservices;

    /**
     * @ORM\ManyToMany(targetEntity="HomestayExtraCost", inversedBy="homestays")
     * @ORM\JoinTable(name="homestays_extracosts")
     */
    private $extracosts;

    /**
     * @ORM\ManyToMany(targetEntity="HomestayNotOffered", inversedBy="homestays")
     * @ORM\JoinTable(name="homestays_notoffereds")
     */
    private $notoffereds;

    /**
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="homestays")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $location;

    /**
     * @ORM\ManyToMany(targetEntity="Place", inversedBy="homestays")
     * @ORM\JoinTable(name="homestays_places")
     */
    private $places;
    
    /**
     * @ORM\ManyToOne(targetEntity="Municipality", inversedBy="homestays")
     * @ORM\JoinColumn(name="municipality_id", referencedColumnName="id")
     */
    protected $municipality;
    
    /**
     * @ORM\ManyToMany(targetEntity="Season") 
     */
    protected $seasons;
    
    /**
     * @ORM\ManyToOne(targetEntity="HomestayChain")
     */
    protected $chain;

    /**
     * @ORM\ManyToOne(targetEntity="Vibalco\AdminBundle\Entity\User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $ownerId;

    /**
     * @return mixed
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }

    /**
     * @param \Vibalco\AdminBundle\Entity\User $ownerId
     */
    public function setOwnerId($ownerId)
    {
        $this->ownerId = $ownerId;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->comision = 5.00;
        $this->enabled = true;
        $this->rank = 0;

        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->interests = new \Doctrine\Common\Collections\ArrayCollection();
        $this->prices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->freeservices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->extracosts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->notoffereds = new \Doctrine\Common\Collections\ArrayCollection();
        $this->places = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seasons = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->name;
    }   
        
    protected function getImageUploadDir() {
        return 'uploads/main/homestay/' ;
    }
    
    /**
     * TODO THIS METHOS SHOULD BE IMPLEMENTED BY OTHER VISIT CLASES
     * (MOVE TO AN INTERFACE)
     */
    public function getEntityRoute(){
        return 'homestay';
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
     * Get galleryOwner
     * 
     * @return string
     */
    public function galleryOwner() {
        return 'homestay_' . $this->id;
    }

    /**
     * Get code
     * 
     * @return string
     */
    public function code() {
        return "HS" . $this->id;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Homestay
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
     * Set name
     *
     * @param string $name
     * @return Homestay
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
     * @return Homestay
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
     * Set owner
     *
     * @param string $owner
     * @return Homestay
     */
    public function setOwner($owner) {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return string 
     */
    public function getOwner() {
        return $this->owner;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Homestay
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
     * @return Homestay
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

    /**
     * Set email
     *
     * @param string $email
     * @return Homestay
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set rooms
     *
     * @param integer $rooms
     * @return Homestay
     */
    public function setRooms($rooms) {
        $this->rooms = $rooms;

        return $this;
    }

    /**
     * Get rooms
     *
     * @return integer 
     */
    public function getRooms() {
        return $this->rooms;
    }

    /**
     * Set promo
     *
     * @param boolean $promo
     * @return Homestay
     */
    public function setPromo($promo) {
        $this->promo = $promo;

        return $this;
    }

    /**
     * Get promo
     *
     * @return boolean 
     */
    public function getPromo() {
        return $this->promo;
    }

    /**
     * Set comision
     *
     * @param float $comision
     * @return Homestay
     */
    public function setComision($comision) {
        $this->comision = $comision;

        return $this;
    }

    /**
     * Get comision
     *
     * @return float 
     */
    public function getComision() {
        return $this->comision;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
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
     * @return Homestay
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
     * Set showcontact
     *
     * @param boolean $showcontact
     * @return Homestay
     */
    public function setShowcontact($showcontact) {
        $this->showcontact = $showcontact;

        return $this;
    }

    /**
     * Get showcontact
     *
     * @return boolean 
     */
    public function getShowcontact() {
        return $this->showcontact;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     * @return Homestay
     */
    public function setRank($rank) {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return integer 
     */
    public function getRank() {
        return $this->rank;
    }

    /**
     * Set locale
     * 
     * @param type $locale
     * @return Homestay
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
     * @return Homestay
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
     * @return Homestay
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
     * @return Homestay
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
     * @param \Vibalco\MainBundle\Entity\HomestayTranslation $translations
     * @return Homestay
     */
    public function addTranslation(\Vibalco\MainBundle\Entity\HomestayTranslation $translations) {
        if (!$this->translations->contains($translations)) {
            $this->translations[] = $translations;
            $translations->setObject($this);
        }

        return $this;
    }

    /**
     * Remove translations
     *
     * @param \Vibalco\MainBundle\Entity\HomestayTranslation $translations
     */
    public function removeTranslation(\Vibalco\MainBundle\Entity\HomestayTranslation $translations) {
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
     * Set acommodation
     *
     * @param \Vibalco\MainBundle\Entity\AcommodationType $acommodation
     * @return Homestay
     */
    public function setAcommodation(\Vibalco\MainBundle\Entity\AcommodationType $acommodation = null) {
        $this->acommodation = $acommodation;

        return $this;
    }

    /**
     * Get acommodation
     *
     * @return \Vibalco\MainBundle\Entity\AcommodationType 
     */
    public function getAcommodation() {
        return $this->acommodation;
    }

    /**
     * Add interest
     *
     * @param \Vibalco\MainBundle\Entity\Interest $interests
     * @return Homestay
     */
    public function addInterest(\Vibalco\MainBundle\Entity\Interest $interests) {
        $this->interests[] = $interests;

        return $this;
    }

    /**
     * Remove interest
     *
     * @param \Vibalco\MainBundle\Entity\Interest $interests
     */
    public function removeInterest(\Vibalco\MainBundle\Entity\Interest $interests) {
        $this->interests->removeElement($interests);
    }

    /**
     * Get interests
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInterests() {
        return $this->interests;
    }

    /**
     * Add price
     *
     * @param \Vibalco\MainBundle\Entity\HomestayPrice $prices
     * @return Homestay
     */
    public function addPrice(\Vibalco\MainBundle\Entity\HomestayPrice $prices) {
        $this->prices[] = $prices;

        return $this;
    }

    /**
     * Remove price
     *
     * @param \Vibalco\MainBundle\Entity\HomestayPrice $prices
     */
    public function removePrice(\Vibalco\MainBundle\Entity\HomestayPrice $prices) {
        $this->prices->removeElement($prices);
    }

    /**
     * Get prices
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPrices() {
        return $this->prices;
    }

    /**
     * Add freeservices
     *
     * @param \Vibalco\MainBundle\Entity\HomestayFreeService $freeservices
     * @return Homestay
     */
    public function addFreeservice(\Vibalco\MainBundle\Entity\HomestayFreeService $freeservices) {
        $this->freeservices[] = $freeservices;

        return $this;
    }

    /**
     * Remove freeservices
     *
     * @param \Vibalco\MainBundle\Entity\HomestayFreeService $freeservices
     */
    public function removeFreeservice(\Vibalco\MainBundle\Entity\HomestayFreeService $freeservices) {
        $this->freeservices->removeElement($freeservices);
    }

    /**
     * Get freeservices
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFreeservices() {
        return $this->freeservices;
    }

    /**
     * Add extracosts
     *
     * @param \Vibalco\MainBundle\Entity\HomestayExtraCost $extracosts
     * @return Homestay
     */
    public function addExtracost(\Vibalco\MainBundle\Entity\HomestayExtraCost $extracosts) {
        $this->extracosts[] = $extracosts;

        return $this;
    }

    /**
     * Remove extracosts
     *
     * @param \Vibalco\MainBundle\Entity\HomestayExtraCost $extracosts
     */
    public function removeExtracost(\Vibalco\MainBundle\Entity\HomestayExtraCost $extracosts) {
        $this->extracosts->removeElement($extracosts);
    }

    /**
     * Get extracosts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExtracosts() {
        return $this->extracosts;
    }

    /**
     * Add notoffereds
     *
     * @param \Vibalco\MainBundle\Entity\HomestayNotOffered $notoffereds
     * @return Homestay
     */
    public function addNotoffered(\Vibalco\MainBundle\Entity\HomestayNotOffered $notoffereds) {
        $this->notoffereds[] = $notoffereds;

        return $this;
    }

    /**
     * Remove notoffereds
     *
     * @param \Vibalco\MainBundle\Entity\HomestayNotOffered $notoffereds
     */
    public function removeNotoffered(\Vibalco\MainBundle\Entity\HomestayNotOffered $notoffereds) {
        $this->notoffereds->removeElement($notoffereds);
    }

    /**
     * Get notoffereds
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotoffereds() {
        return $this->notoffereds;
    }

    /**
     * Set location
     *
     * @param \Vibalco\MainBundle\Entity\Location $location
     * @return Homestay
     */
    public function setLocation(\Vibalco\MainBundle\Entity\Location $location = null) {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return \Vibalco\MainBundle\Entity\Location 
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * Add places
     *
     * @param \Vibalco\MainBundle\Entity\Place $places
     * @return Homestay
     */
    public function addPlace(\Vibalco\MainBundle\Entity\Place $places) {
        $this->places[] = $places;

        return $this;
    }

    /**
     * Remove places
     *
     * @param \Vibalco\MainBundle\Entity\Place $places
     */
    public function removePlace(\Vibalco\MainBundle\Entity\Place $places) {
        $this->places->removeElement($places);
    }

    /**
     * Get places
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlaces() {
        return $this->places;
    }
    
    /**
     * Set municipality
     *
     * @param \Vibalco\MainBundle\Entity\Municipality $municipality
     * @return Place
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
     * Set breakfastprice
     *
     * @param float $breakfastprice
     * @return Homestay
     */
    public function setBreakfastprice($breakfastprice)
    {
        $this->breakfastprice = $breakfastprice;
    
        return $this;
    }

    /**
     * Get breakfastprice
     *
     * @return float 
     */
    public function getBreakfastprice()
    {
        return $this->breakfastprice;
    }

    /**
     * Set extrabedprice
     *
     * @param float $extrabedprice
     * @return Homestay
     */
    public function setExtrabedprice($extrabedprice)
    {
        $this->extrabedprice = $extrabedprice;
    
        return $this;
    }

    /**
     * Get extrabedprice
     *
     * @return float 
     */
    public function getExtrabedprice()
    {
        return $this->extrabedprice;
    }

    /**
     * Set bedchildprice
     *
     * @param float $bedchildprice
     * @return Homestay
     */
    public function setBedchildprice($bedchildprice)
    {
        $this->bedchildprice = $bedchildprice;
    
        return $this;
    }

    /**
     * Get bedchildprice
     *
     * @return float 
     */
    public function getBedchildprice()
    {
        return $this->bedchildprice;
    }
    
    /**
     * Add seasons
     *
     * @param \Vibalco\MainBundle\Entity\Season $seasons
     * @return Homestay
     */
    public function addSeason(\Vibalco\MainBundle\Entity\Season $seasons)
    {
        $this->seasons[] = $seasons;
    
        return $this;
    }

    /**
     * Remove seasons
     *
     * @param \Vibalco\MainBundle\Entity\Season $seasons
     */
    public function removeSeason(\Vibalco\MainBundle\Entity\Season $seasons)
    {
        $this->seasons->removeElement($seasons);
    }

    /**
     * Get seasons
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSeasons()
    {
        return $this->seasons;
    }
    
    /**
     * Set chain
     *
     * @param \Vibalco\MainBundle\Entity\HomestayChain $chain
     * @return Homestay
     */
    public function setChain(\Vibalco\MainBundle\Entity\HomestayChain $chain = null) {
        $this->chain = $chain;

        return $this;
    }

    /**
     * Get chain
     *
     * @return \Vibalco\MainBundle\Entity\HomestayChain 
     */
    public function getChain() {
        return $this->chain;
    }
    
    /**
     * Get seasons
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCurrentSeasons()
    {       
        return !$this->seasons->isEmpty() ? $this->seasons : 
               ($this->chain != null ? $this->chain->getSeasons() : null);
    }
    
    /**
     * Get prices
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCurrentPrices() {
        
        $prices = array();
        $seasons = $this->getCurrentSeasons();

        foreach ($this->prices as $price) {
            if($seasons->contains($price->getSeason())) {
                $prices[$price->getCode()] = $price;
            }
        }
        
        return $prices;
    }
    
    public function getMinPrice() {
        $result = null;
        
        foreach ($this->prices as $price) {
            $tmp = $price->getPrice();
            if($tmp > 0 && ($result == null || $result > $tmp)){
                $result = $tmp;
            }
        }
        return $result;
    }

    /**
     * Set account
     *
     * @param string $account
     * @return Homestay
     */
    public function setAccount($account)
    {
        $this->account = $account;
    
        return $this;
    }

    /**
     * Get account
     *
     * @return string 
     */
    public function getAccount()
    {
        return $this->account;
    }
    
    public function hasLatLng(){
        return $this->latitude && $this->longitude;
    }
}
