<?php

namespace Vibalco\GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Image
 * 
 * @ORM\Table(name="gallery_image")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Image {

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
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;
    private $temp;

    /**
     * @Assert\File(maxSize ="2M", mimeTypes = {"image/jpg","image/png","image/gif","image/jpeg"})
     */
    private $image;

    /**
     * @var string
     * 
     * @ORM\Column(name="owner", type="string", length=255)
     */
    private $owner;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Image
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
     * @return Image
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
        return "uploads/gallery/{$this->owner}/";
    }

    protected function getUploadRootDir() {
        return __DIR__ . '/../../../../public_html/' . $this->getUploadDir();
    }

    public function getAbsolutePath() {
        return null === $this->path ? null : $this->getUploadRootDir() . $this->path;
    }

    public function getWebPath() {
        return null === $this->path ? null : $this->getUploadDir() . $this->path;
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
                unlink($this->getUploadRootDir() . $this->temp);
            } catch (\Exception $e) {
                // nothing to do
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
            if (file_exists($path) && !is_dir($path)) {
                unlink($path);
            }
            
            $dir = dirname($path);
            
            if (count(glob($dir . '/*')) === 0 ) {
                rmdir($dir);
            }
        } catch (\Exception $e) {
            // nothing to do
        }
    }

    /**
     * Set owner
     *
     * @param string $owner
     * @return Image
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

}
