<?php

namespace Vibalco\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Settings
 *
 * @ORM\Table(name="rcu_settings")
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="Vibalco\AdminBundle\Entity\SettingsTranslation")
 */
class Settings
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="text", nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook", type="string", length=255, nullable=true)
     * @Assert\Url
     */
    private $facebook;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter", type="string", length=255, nullable=true)
     * @Assert\Url
     */
    private $twitter;

    /**
     * @var string
     *
     * @ORM\Column(name="tripadvisor", type="string", length=255, nullable=true)
     * @Assert\Url
     */
    private $tripadvisor;

    /**
     * @var string
     *
     * @ORM\Column(name="googleplus", type="string", length=255, nullable=true)
     * @Assert\Url
     */
    private $googleplus;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     * @Assert\Email
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="adminemail", type="string", length=255, nullable=true)
     * @Assert\Email
     */
    private $adminemail;
    
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
     * @ORM\Column(name="contactdescription", type="text", nullable=true)
     * @Gedmo\Translatable
     */
    private $contactdescription;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="aboutus", type="text", nullable=true)
     * @Gedmo\Translatable
     */
    private $aboutus;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="policies", type="text", nullable=true)
     * @Gedmo\Translatable
     */
    private $policies;
    
    /**
     * @var string
     *
     * @ORM\Column(name="domain", type="string", length=255, nullable=true)
     */
    private $domain;

    /**
     * @var boolean
     *
     * @ORM\Column(name="offline", type="boolean",nullable=true)
     */
    private $offline;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    private $message;
    
    /**
     * @ORM\Column(name="exchangecuc", type="float", nullable=true)
     */
    protected $exchangecuc;
    
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
     * @ORM\OneToMany(targetEntity="SettingsTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    private $translations;
    
    
    public function __construct() {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set locale
     * 
     * @param type $locale
     * @return Settings
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
     * @param \Vibalco\AdminBundle\Entity\SettingsTranslation $translations
     * @return Settings
     */
    public function addTranslation(\Vibalco\AdminBundle\Entity\SettingsTranslation $translations) {
        if (!$this->translations->contains($translations)) {
            $this->translations[] = $translations;
            $translations->setObject($this);
        }

        return $this;
    }

    /**
     * Remove translations
     *
     * @param \Vibalco\AdminBundle\Entity\SettingsTranslation $translations
     */
    public function removeTranslation(\Vibalco\AdminBundle\Entity\SettingsTranslation $translations) {
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
     * Set meta_title
     *
     * @param string $metaTitle
     * @return Settings
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
     * @return Settings
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
     * @return Settings
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

    public function getTitle() {
        return "Settings";
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Settings
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
     * Set contactdescription
     *
     * @param string $contactdescription
     * @return Settings
     */
    public function setContactDescription($contactdescription)
    {
        $this->contactdescription = $contactdescription;
    
        return $this;
    }

    /**
     * Get contactdescription
     *
     * @return string 
     */
    public function getContactDescription()
    {
        return $this->contactdescription;
    }

    /**
     * Set aboutus
     *
     * @param string $aboutus
     * @return Settings
     */
    public function setAboutus($aboutus)
    {
        $this->aboutus = $aboutus;
    
        return $this;
    }

    /**
     * Get aboutus
     *
     * @return string 
     */
    public function getAboutus()
    {
        return $this->aboutus;
    }

    /**
     * Set policies
     *
     * @param string $policies
     * @return SettingsRcu
     */
    public function setPolicies($policies)
    {
        $this->policies = $policies;
    
        return $this;
    }

    /**
     * Get policies
     *
     * @return string 
     */
    public function getPolicies()
    {
        return $this->policies;
    }
    
    /**
     * Set facebook
     *
     * @param string $facebook
     * @return Settings
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
    
        return $this;
    }

    /**
     * Get facebook
     *
     * @return string 
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set twitter
     *
     * @param string $twitter
     * @return Settings
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
    
        return $this;
    }

    /**
     * Get twitter
     *
     * @return string 
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set tripadvisor
     *
     * @param string $tripadvisor
     * @return Settings
     */
    public function setTripAdvisor($tripadvisor)
    {
        $this->tripadvisor = $tripadvisor;
    
        return $this;
    }

    /**
     * Get tripadvisor
     *
     * @return string 
     */
    public function getTripAdvisor()
    {
        return $this->tripadvisor;
    }

    /**
     * Set googleplus
     *
     * @param string $googleplus
     * @return Settings
     */
    public function setGooglePlus($googleplus)
    {
        $this->googleplus = $googleplus;
    
        return $this;
    }

    /**
     * Get googleplus
     *
     * @return string 
     */
    public function getGooglePlus()
    {
        return $this->googleplus;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Settings
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set adminemail
     *
     * @param string $adminemail
     * @return Settings
     */
    public function setAdminemail($adminemail)
    {
        $this->adminemail = $adminemail;
    
        return $this;
    }

    /**
     * Get adminemail
     *
     * @return string 
     */
    public function getAdminemail()
    {
        return $this->adminemail;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Settings
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set phones
     *
     * @param string $phones
     * @return Settings
     */
    public function setPhones($phones)
    {
        $this->phones = $phones;
    
        return $this;
    }

    /**
     * Get phones
     *
     * @return string 
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * Set domain
     *
     * @param string $domain
     * @return Settings
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    
        return $this;
    }

    /**
     * Get domain
     *
     * @return string 
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set offline
     *
     * @param boolean $offline
     * @return Settings
     */
    public function setOffline($offline)
    {
        $this->offline = $offline;
    
        return $this;
    }

    /**
     * Get offline
     *
     * @return boolean 
     */
    public function getOffline()
    {
        return $this->offline;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Settings
     */
    public function setMessage($message)
    {
        $this->message = $message;
    
        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set exchangecuc
     *
     * @param float $exchangecuc
     * @return Settings
     */
    public function setExchangecuc($exchangecuc)
    {
        $this->exchangecuc = $exchangecuc;
    
        return $this;
    }

    /**
     * Get exchangecuc
     *
     * @return float 
     */
    public function getExchangecuc()
    {
        return $this->exchangecuc;
    }
}