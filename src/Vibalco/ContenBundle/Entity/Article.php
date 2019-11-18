<?php

namespace Vibalco\ContenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * Post
 *
 * @ORM\Table(name="article")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\TranslationEntity(class="Vibalco\ContenBundle\Entity\ArticleTranslation")
 */
class Article implements Translatable {

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
     * @var string $slug
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=255, nullable=false)
     */
    protected $slug;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

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
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     * @Gedmo\Translatable
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="posts")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;

    /**
     * @var boolean $enabled
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    protected $enabled;

    /**
     * @var integer $menu
     *
     * @ORM\Column(name="menu", type="integer", nullable=true)
     */
    protected $menu;

    /**
     * @var string $locale
     * 
     * @Gedmo\Locale
     */
    private $locale;
    
     public function getCreated() {
        return $this->created;
    }



    public function setCreated($created) {
        $this->created = $created;
    }

    public function getMenu() {
        return $this->menu;
    }

    public function setMenu($menu) {
        $this->menu = $menu;
    }

    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
    }

    public function __construct() {
        $this->enabled = true;
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setCreated(new \DateTime());
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
     * @return Post
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
        return 'uploads/blog/post/';
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
     * Set text
     *
     * @param string $text
     * @return Post
     */
    public function setText($text) {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText() {
        return $this->text;
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
     * Set category
     *
     * @param \Vibalco\BlogBundle\Entity\Category $category
     * @return Post
     */
    public function setCategory(\Vibalco\ContenBundle\Entity\Category $category = null) {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Vibalco\BlogBundle\Entity\Category 
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * @ORM\OneToMany(
     *   targetEntity="ArticleTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;

    public function getTranslations() {
        return $this->translations;
    }

    public function addTranslation(ArticleTranslation $t) {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function removeTranslation(ArticleTranslation $t) {
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

}
