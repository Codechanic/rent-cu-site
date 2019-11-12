<?php

namespace Vibalco\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ContactForm
 *
 * @ORM\Table(name="rcu_contactform")
 * @ORM\Entity
 */
class ContactForm
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
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     * @Assert\NotBlank
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fromdate", type="date")
     */
    private $fromdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="todate", type="date")
     */
    private $todate;
    
    /**
     * @ORM\ManyToOne(targetEntity="Homestay")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $homestay;
    
    /**
     * @ORM\Column(name="active", type="boolean")
     */
    protected $active;
    
    /**
     * @Assert\IsTrue
     */
    public function isValid() {
        return ($this->email != null || $this->phone != null );
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
    
    public function __construct() {
        $this->active = true;
    }
    
    public function __toString() {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ContactForm
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
     * Set email
     *
     * @param string $email
     * @return ContactForm
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
     * Set phone
     *
     * @param string $phone
     * @return ContactForm
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return ContactForm
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
     * Set from
     *
     * @param \DateTime $fromdate
     * @return ContactForm
     */
    public function setFromdate($fromdate)
    {
        $this->fromdate = $fromdate;
    
        return $this;
    }

    /**
     * Get fromdate
     *
     * @return \DateTime 
     */
    public function getFromdate()
    {
        return $this->fromdate;
    }

    /**
     * Set todate
     *
     * @param \DateTime $todate
     * @return ContactForm
     */
    public function setTodate($todate)
    {
        $this->todate = $todate;
    
        return $this;
    }

    /**
     * Get to
     *
     * @return \DateTime 
     */
    public function getTodate()
    {
        return $this->todate;
    }
    
    /**
     * Set homestay
     *
     * @param \Vibalco\MainBundle\Entity\Homestay $homestay
     * @return Promo
     */
    public function setHomestay(\Vibalco\MainBundle\Entity\Homestay $homestay = null)
    {
        $this->homestay = $homestay;
    
        return $this;
    }

    /**
     * Get homestay
     *
     * @return \Vibalco\MainBundle\Entity\Homestay 
     */
    public function getHomestay()
    {
        return $this->homestay;
    }
    
    public function getActive() {
        return $this->active;
    }

    public function setActive($active) {
        $this->active = $active;
        return $this;
    }
}
