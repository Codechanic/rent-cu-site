<?php

namespace Vibalco\ContenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * Category
 * @author Vibalco <dev@vibalco.com>
 * @ORM\Table(name="category")
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="Vibalco\ContenBundle\Entity\CategoryTranslation")
 * @ORM\HasLifecycleCallbacks
 */
class Category  implements Translatable{

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
     * @ORM\Column(name="alias", type="string", length=255)
     * 
     */
    private $alias;

    /**
     * @var string $path
     * @ORM\Column(name="path", type="string", nullable=true)
     */
    private $path;
    private $temp;

    /**
     * @Assert\Image(maxSize ="1M", mimeTypes = {"image/jpg","image/png","image/gif","image/jpeg"})
     */
    protected $image;
    
    /**
     * @var string $locale
     * 
     * @Gedmo\Locale
     */
    private $locale;


    
    /**
     * @ORM\OneToMany(
     *   targetEntity="CategoryTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;

 
    public function getTranslations() {
        return $this->translations;
    }

    public function addTranslation(CategoryTranslation $t) {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function removeTranslation(CategoryTranslation $t) {
        $this->translations->removeElement($t);
    }
    
  

    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="category")
     */
    protected $posts;
    
    public function getAlias() {
        return $this->alias;
    }

    public function setAlias($alias) {
        $this->alias = $alias;
    }

        
    
    /**
     * Set image.
     *
     * @param UploadedFile $image
     * @return Post
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
        return 'uploads/blog/cat/';
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

    public function __toString() {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

        /**
     * Set path
     *
     * @param string $path
     * @return Category
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

        
    /**
     * Add posts
     *
     * @param \Vibalco\BlogBundle\Entity\Post $posts
     * @return Category
     */
    public function addPost(\Vibalco\ContenBundle\Entity\Article $posts)
    {
        $this->posts[] = $posts;
    
        return $this;
    }

    /**
     * Remove posts
     *
     * @param \Vibalco\BlogBundle\Entity\Post $posts
     */
    public function removePost(\Vibalco\ContenBundle\Entity\Article $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts;
    }
    
     /**
     * Set locale
     * 
     * @param string $locale
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

    /**
     * Set translatable locale
     * 
     * @param string $locale
     */
    public function setTranslatableLocale($locale) {
        $this->locale = $locale;
    }
}