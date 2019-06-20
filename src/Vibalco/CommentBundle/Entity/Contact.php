<?php

namespace Vibalco\CommentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Tags
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Contact {

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
     * @Assert\NotBlank(message = "news.contacts.errors.email")
     * @Assert\Email()
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @ORM\ManyToMany(targetEntity="Mailing", mappedBy="contacts")
     * */
    protected $mailing;
    
    
     /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;
    
    
    public function getToken() {
        return $this->token;
    }

    public function getEnabled() {
        return $this->enabled;
    }

    public function setToken($token) {
        $this->token = $token;
    }

    public function setEnabled($enabled) {
        $this->enabled = $enabled;
    }

          /**
     * @var string
     *
     * @ORM\Column(name="enabled", type="boolean", length=255)
     */
    private $enabled;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    public function __construct() {
        $this->mailing = new ArrayCollection();
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    
    public function addMailing(\Vibalco\CommentBundle\Entity\Mailing $mailing) {
        $this->mailing[] = $mailing;
    }

    public function getMailing() {
        return $this->mailing;
    }

    public function __toString() {
        return $this->email;
    }

}
