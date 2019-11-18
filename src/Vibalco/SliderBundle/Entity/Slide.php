<?php

namespace Vibalco\SliderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable; 

/**
 * Slide
 * @Gedmo\TranslationEntity(class="Vibalco\SliderBundle\Entity\SlideTranslation")
 * @ORM\Table(name="slide")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Slide implements Translatable {

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
     */
    private $name;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(name="title", type="text")
     */
    private $title;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(name="subtitle", type="text")
     */
    private $subtitle;

    /**
     * @var string $path
     * @ORM\Column(name="path", type="string", nullable=true)
     */
    private $path;
    private $temp;

    /**
     * @Assert\Image(maxSize ="2M", mimeTypes = {"image/jpg","image/png","image/gif","image/jpeg"})
     */
    protected $image;

    /**
     * @var boolean $enabled
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    protected $enabled;
    
  

     /**
     * @Gedmo\Locale
     */
    private $locale;
    
    
    public function __construct() {
        $this->enabled = true;
         
         $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->title;
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
     * @return Slider
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
     * Set title
     *
     * @param string $title
     * @return Slider
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set subtitle
     *
     * @param string $subtitle
     * @return Slider
     */
    public function setSubtitle($subtitle) {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string 
     */
    public function getSubtitle() {
        return $this->subtitle;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Slide
     */
    public function setPath($path) {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * Set image.
     *
     * @param UploadedFile $image
     * @return Slide
     */
    public function setImage(UploadedFile $image = null) {
        $this->image = $image;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
        
        return $this;
    }

    /**
     * Get image
     *
     * @return UploadedFile 
     */
    public function getImage() {
        return $this->image;
    }

    protected function getUploadDir() {
        return 'uploads/slider/slide';
    }

    protected function getUploadRootDir() {
        return __DIR__ . '/../../../../public_html/' . $this->getUploadDir();
    }

    public function getAbsolutePath() {
        return null === $this->path ? null : $this->getUploadRootDir() . '/' . $this->path;
    }

    public function getWebPath() {
        return null === $this->path ? null : $this->getUploadDir() . '/' . $this->path;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        if (NULL !== $this->image) {
            $this->path = uniqid() . '.' . $this->getImage()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (null === $this->getImage()) {
            return;
        }
        if (isset($this->temp)) {
            try {
                unlink($this->getUploadRootDir() . '/' . $this->temp);
            } catch (\Exception $e) {
                //nada
            }
            $this->temp = null;
        }
        $this->getImage()->move($this->getUploadRootDir(), $this->path);
        $this->image = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        $path = $this->getAbsolutePath();
        try {
            if (file_exists($path) && !is_dir($path))
                unlink($path);
        } catch (\Exception $e) {
            // nothing to do
        }
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Slide
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
     * @ORM\OneToMany(
     *   targetEntity="SlideTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;

   

    public function getTranslations() {
        return $this->translations;
    }

    public function addTranslation(SlideTranslation $t) {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function removeTranslation(SlideTranslation $t) {
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
}