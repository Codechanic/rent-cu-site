<?php

namespace Vibalco\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Interest
 * 
 * @ORM\Table(name="interest")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * 
 * @Gedmo\TranslationEntity(class="Vibalco\MainBundle\Entity\InterestTranslation")
 */
class Interest extends BaseImage {

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
     * @ORM\Column(name="name", type="string", length=255, unique=true )
     * @Gedmo\Translatable
     */
    private $name;

    /**
     * @Gedmo\Locale
     */
    private $locale;

    // relationships
    // -------------

    /**
     * @ORM\OneToMany(targetEntity="InterestTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    private $translations;

    /**
     * @ORM\ManyToMany(targetEntity="Homestay", mappedBy="interests")
     */
    private $homestays;

    /**
     * Constructor
     */
    public function __construct() {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->homestays = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->name;
    }
    
    
    protected function getImageUploadDir() {
        return 'uploads/main/interest/' ;
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
     * @return Interest
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
     * Set locale
     * 
     * @param type $locale
     * @return Interest
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
     * @param InterestTranslation $translations
     * @return Interest
     */
    public function addTranslation(InterestTranslation $translations) {
        if (!$this->translations->contains($translations)) {
            $this->translations[] = $translations;
            $translations->setObject($this);
        }

        return $this;
    }

    /**
     * Remove translations
     *
     * @param InterestTranslation $translations
     */
    public function removeTranslation(InterestTranslation $translations) {
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
     * Add homestays
     *
     * @param Homestay $homestays
     * @return Interest
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
}