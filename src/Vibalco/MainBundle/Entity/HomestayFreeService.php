<?php

namespace Vibalco\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * HomestayFreeService
 *
 * @ORM\Table(name="homestay_freeservices")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * 
 * @Gedmo\TranslationEntity(class="Vibalco\MainBundle\Entity\HomestayFreeServiceTranslation")
 */
class HomestayFreeService {

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
     * @ORM\OneToMany(targetEntity="HomestayFreeServiceTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    private $translations;

    /**
     * @ORM\ManyToMany(targetEntity="Homestay", mappedBy="freeservices")
     */
    private $homestays;

    public function __construct() {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->homestays = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->name;
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
     * @return HomestayFreeService
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
     * Add translations
     *
     * @param \Vibalco\MainBundle\Entity\HomestayFreeServiceTranslation $translations
     * @return HomestayFreeService
     */
    public function addTranslation(\Vibalco\MainBundle\Entity\HomestayFreeServiceTranslation $translations) {
        if (!$this->translations->contains($translations)) {
            $this->translations[] = $translations;
            $translations->setObject($this);
        }

        return $this;
    }

    /**
     * Remove translations
     *
     * @param \Vibalco\MainBundle\Entity\HomestayFreeServiceTranslation $translations
     */
    public function removeTranslation(\Vibalco\MainBundle\Entity\HomestayFreeServiceTranslation $translations) {
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
     * @param \Vibalco\MainBundle\Entity\Homestay $homestays
     * @return HomestayFreeService
     */
    public function addHomestay(\Vibalco\MainBundle\Entity\Homestay $homestays) {
        $this->homestays[] = $homestays;

        return $this;
    }

    /**
     * Remove homestays
     *
     * @param \Vibalco\MainBundle\Entity\Homestay $homestays
     */
    public function removeHomestay(\Vibalco\MainBundle\Entity\Homestay $homestays) {
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
