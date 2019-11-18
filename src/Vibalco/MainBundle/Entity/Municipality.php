<?php

namespace Vibalco\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Municipality
 *
 * @ORM\Table(name="municipality")
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="Vibalco\MainBundle\Entity\MunicipalityTranslation")
 */
class Municipality extends BaseImage {

    /**
     * @var integer $id
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     * @ORM\Column(name="name", type="string", unique=true )
     */
    protected $name;

    /**
     * @var string $description
     * @Gedmo\Translatable
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @ManyToOne(targetEntity="Province", inversedBy="municipalities")
     * @JoinColumn(name="province_id", referencedColumnName="id")
     */
    protected $province;

    /**
     * @OneToMany(targetEntity="Place", mappedBy="municipality")
     */
    protected $places;

    /**
     * @ORM\OneToMany(targetEntity="Homestay", mappedBy="municipality")
     */
    protected $homestays;

    /**
     * @ORM\OneToMany(targetEntity="AntiqueCar", mappedBy="municipality")
     */
    protected $antiquecars;  
        
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
     *   targetEntity="MunicipalityTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;

    /**
     * Constructor
     */
    public function __construct() {
        $this->homestays = new \Doctrine\Common\Collections\ArrayCollection();
        $this->places = new \Doctrine\Common\Collections\ArrayCollection();
        $this->antiquecars = new \Doctrine\Common\Collections\ArrayCollection();
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }
       
    protected function getImageUploadDir() {
        return 'uploads/municipality/' ;
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
     * @return Municipality
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
     * @return Municipality
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

    public function getTranslations() {
        return $this->translations;
    }

    public function addTranslation(MunicipalityTranslation $t) {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function removeTranslation(MunicipalityTranslation $t) {
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

    public function __toString() {
        return $this->name;
    }

    /**
     * Set province
     *
     * @param \Vibalco\MainBundle\Entity\Province $province
     * @return Municipality
     */
    public function setProvince(\Vibalco\MainBundle\Entity\Province $province = null) 
    {
        $this->province = $province;

        return $this;
    }

    /**
     * Get province
     *
     * @return \Vibalco\MainBundle\Entity\Province 
     */
    public function getProvince() 
    {
        return $this->province;
    }

    /**
     * Add places
     *
     * @param \Vibalco\MainBundle\Entity\Place $places
     * @return Municipality
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
     * Add antiquecars
     *
     * @param \Vibalco\MainBundle\Entity\AntiqueCar $antiquecars
     * @return Municipality
     */
    public function addAntiqueCar(\Vibalco\MainBundle\Entity\AntiqueCar $antiquecars) {
        $this->antiquecars[] = $antiquecars;

        return $this;
    }

    /**
     * Remove antiquecars
     *
     * @param \Vibalco\MainBundle\Entity\AntiqueCar $antiquecars
     */
    public function removeAntiqueCar(\Vibalco\MainBundle\Entity\AntiqueCar $antiquecars) {
        $this->antiquecars->removeElement($antiquecars);
    }

    /**
     * Get antiquecars
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAntiqueCars() {
        return $this->antiquecars;
    }
    
    /**
     * Add homestay
     *
     * @param \Vibalco\MainBundle\Entity\Homestay $homestay
     * @return Municipality
     */
    public function addHomestay(\Vibalco\MainBundle\Entity\Homestay $homestay) 
    {
        $this->homestays[] = $homestay;

        return $this;
    }

    /**
     * Remove homestay
     *
     * @param \Vibalco\MainBundle\Entity\Homestay $homestay
     */
    public function removeHomestay(\Vibalco\MainBundle\Entity\Homestay $homestay) 
    {
        $this->homestays->removeElement($homestay);
    }

    /**
     * Get homestay
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHomestay()
    {
        return $this->homestays;
    }

    /**
     * Set meta_title
     *
     * @param string $metaTitle
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
