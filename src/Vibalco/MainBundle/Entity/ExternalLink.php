<?php

namespace Vibalco\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ExternalLink
 *
 * @ORM\Table(name="rcu_externallink")
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="Vibalco\MainBundle\Entity\ExternalLinkTranslation")
 */
class ExternalLink
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
     * @ORM\Column(name="url", type="string", length=255)
     * @Assert\Url
     * @Assert\NotBlank
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank
     * @Gedmo\Translatable
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="norder", type="integer")
     */
    private $norder;
    
    /**
     * @Gedmo\Locale
     */
    private $locale;

    /**
     * @ORM\OneToMany(
     *   targetEntity="ExternalLinkTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;

    public function __construct() {
        $this->norder = 0;
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString() {
        $this->name;
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
     * Set url
     *
     * @param string $url
     * @return ExternalLink
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ExternalLink
     */
    public function setName($name)
    {
        $this->name = $name;
    
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
     * Set norder
     *
     * @param integer $norder
     * @return ExternalLink
     */
    public function setNorder($norder)
    {
        $this->norder = $norder;
    
        return $this;
    }

    /**
     * Get norder
     *
     * @return integer 
     */
    public function getNorder()
    {
        return $this->norder;
    }
    
    public function getTranslations() {
        return $this->translations;
    }

    public function addTranslation(ExternalLinkTranslation $t) {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function removeTranslation(ExternalLinkTranslation $t) {
        $this->translations->removeElement($t);
    }

    /**
     * Set translatable locale
     * 
     * @param type $locale
     */
    public function setTranslatableLocale($locale) {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Set locale
     * 
     * @param type $locale
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
}
