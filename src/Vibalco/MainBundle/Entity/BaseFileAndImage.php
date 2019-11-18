<?php

namespace Vibalco\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BaseFileAndImage
 * 
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
class BaseFileAndImage extends BaseImage
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $filepath;
    protected $filetemp;     
    
    /**
     * @Assert\File(maxSize ="10M", mimeTypes = {"application/pdf", "application/doc"})
     */
    protected $file;
    
    /**
     * Returns the directory where the file will be located
     * (You must override this method to chage the default directory)
     * 
     * @return string
     */   
    protected function getFileUploadDir() {
        return 'uploads/' ;
    }
    
    /**
     * Returns a unique generated name for the uploaded file
     * (You must override this method to chage this behavior)
     * 
     * @return string
     */       
    protected function createFileFileName() 
    {
        return uniqid() . '.' . $this->getFile()->guessExtension();
    }
    
    /**
     * Set filepath
     *
     * @param string $filepath
     * @return 
     */
    public function setFilepath($filepath)
    {
        $this->filepath = $filepath;
    
        return $this;
    }

    /**
     * Get filepath
     *
     * @return string 
     */
    public function getFilepath()
    {
        return $this->filepath;
    }
    
    /**
     * Set file.
     *
     * @param UploadedFile $file
     * 
     */
    public function setFile(UploadedFile $file = null) 
    {
        $this->file = $file;
        // check if we have an old file filepath
        if (isset($this->filepath))
        {
            // store the old name to delete after the update
            $this->filetemp = $this->filepath;
            $this->filepath = null;
        }

        if (NULL !== $this->file) {
             $this->filepath = $this->createFileFileName();
        }

        return $this;
    }

    /**
     * Get file
     *
     * @return UploadedFile 
     */
    public function getFile() {
        return $this->file;
    }

    protected function getFileUploadRootDir() {
        return __DIR__ . '/../../../../public_html/' . $this->getFileUploadDir();
    }

    public function getFileAbsolutePath() {
        return null === $this->filepath ? null : $this->getFileUploadRootDir() . $this->filepath;
    }

    public function getFileWebPath() {
        return null === $this->filepath ? null : $this->getFileUploadDir() . $this->filepath;
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function uploadFile() {
        if (null === $this->getFile()) {
            return;
        }
        if (isset($this->filetemp)) {
            try {
                unlink($this->getFileUploadRootDir() . $this->filetemp);
            } catch (\Exception $e) {
                // nothing to do
            }
            $this->filetemp = null;
        }
        $this->getFile()->move($this->getFileUploadRootDir(), $this->filepath);
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUploadFile() {
        $filepath = $this->getFileAbsolutePath();
        try {
            if (file_exists($filepath) && !is_dir($filepath)) {
                unlink($filepath);
            }
        } catch (\Exception $e) {
            // nothing to do
        }
    }
}
