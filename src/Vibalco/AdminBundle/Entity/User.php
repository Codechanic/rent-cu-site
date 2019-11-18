<?php

namespace Vibalco\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile; 

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Vibalco\AdminBundle\Entity\UserRepository")
 */
class User implements AdvancedUserInterface, \Serializable {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $username
     *
     * @ORM\Column(name="username", type="string", length=30, unique=true)
     */
    protected $username;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    protected $name;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", unique=true)
     * @Assert\Email()
     */
    protected $email;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string")
     */
    protected $password;

    /**
     * @var string $salt
     *
     * @ORM\Column(name="salt", type="string")
     */
    protected $salt;

    /**
     * @var string $path
     * @ORM\Column(name="path", type="string", nullable=true)
     */
    private $path;
    private $temp;

    /**
     * @Assert\Image(maxSize ="1M", mimeTypes = {"image/jpg","image/png","image/gif","image/jpeg"})
     */
    protected $file;

    /**
     * @var boolean $enabled
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    protected $enabled;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     *
     */
    private $roles;
   

    public function getRoles()    {
       
        return $this->roles->toArray();
        
    }

    public function __construct() {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->enabled = true;
    }

    public function __toString() {
        return $this->name;
    }

    public function eraseCredentials() {
       // nothing 
    }

    public function getPassword() {
        return $this->password;
    }

  

    public function getSalt() {
        return $this->salt;
    }

    public function getUsername() {
        return $this->username;
    }

    public function isAccountNonExpired() {
        return $this->enabled;
    }

    public function isAccountNonLocked() {
        return $this->enabled;
    }

    public function isCredentialsNonExpired() {
        return $this->enabled;
    }

    public function isEnabled() {
        return $this->enabled;
    }

    /**
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
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
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     */
    public function setSalt($salt) {
        //$this->salt = $salt;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     */
    public function setEnabled($enabled) {
        $this->enabled = $enabled;
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
     * Set path
     *
     * @param string $path
     * @return User
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
     * @return User
     */
    public function setFile(UploadedFile $image = null) {
        $this->file = $image;
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
    public function getFile() {
        return $this->file;
    }

    protected function getUploadDir() {
        return 'uploads/admin/user/';
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
        if (NULL !== $this->file) {
            $this->path = uniqid() . '.' . $this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (null === $this->getFile()) {
            return;
        }
        if (isset($this->temp)) {
            try {
                unlink($this->getUploadRootDir() . '/' . $this->temp);
            } catch (\Exception $e) {
                // nothing to do
            }
            $this->temp = null;
        }
        $this->getFile()->move($this->getUploadRootDir(), $this->path);
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

    public function serialize() {
        
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));        
    }

    public function unserialize($serialized) {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }
    
    
    public function setRoles($roles) {
        $this->roles = $roles;
    }


    

}