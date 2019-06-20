<?php

namespace Vibalco\CommentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Mailing
 *
 * @ORM\Entity
 */
class Mailing {

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
     * @Assert\NotBlank(message = "news.mailing.errors.title")
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     * @Assert\NotBlank(message = "news.mailing.errors.body")
     * @ORM\Column(name="body", type="text")
     */
    private $body;



    /**
     * @var boolean
     *
     * @ORM\Column(name="sended", type="boolean")
     */
    private $sended;


    
    
      /**
     * @ORM\ManyToMany(targetEntity="Contact", inversedBy="mailing")
     * @ORM\JoinTable(name="mailing_contact")
     * */
    protected $contacts;
    

    
    
    
    
    

    public function __construct() {
        $this->contacts = new ArrayCollection();
    }

    public function addContacts(\Vibalco\CommentBundle\Entity\Contact $contact) {
        $contact->addMailing($this);
        $this->contacts[] = $contact;
    }

    public function getContacts() {
        return $this->contacts;
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
     * Set title
     *
     * @param string $title
     * @return Mailing
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
     * Set body
     *
     * @param string $body
     * @return Mailing
     */
    public function setBody($body) {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody() {
        return $this->body;
    }



    /**
     * Set sended
     *
     * @param boolean $sended
     * @return Mailing
     */
    public function setSended($sended) {
        $this->sended = $sended;

        return $this;
    }

    /**
     * Get sended
     *
     * @return boolean 
     */
    public function getSended() {
        return $this->sended;
    }


    public function __toString() {
        return $this->title;
    }

}
