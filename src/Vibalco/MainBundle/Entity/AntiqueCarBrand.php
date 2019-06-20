<?php

namespace Vibalco\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * AntiqueCarBrand
 * @ORM\Table(name="rcu_antiquecarbrand")
 * @ORM\Entity
 */
class AntiqueCarBrand {

    /**
     * @var integer $id
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     * @ORM\Column(name="name", type="string", unique=true )
     * @Assert\NotBlank
     */
    protected $name;

    /**
     * @OneToMany(targetEntity="AntiqueCar", mappedBy="brand")
     */
    protected $cars;
    
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
     * @return AntiqueCarBrand
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
     * Constructor
     */
    public function __construct()
    {
        $this->cars = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add cars
     *
     * @param \Vibalco\MainBundle\Entity\AntiqueCar $cars
     * @return AntiqueCarBrand
     */
    public function addCar(\Vibalco\MainBundle\Entity\AntiqueCar $cars)
    {
        $this->cars[] = $cars;
    
        return $this;
    }

    /**
     * Remove cars
     *
     * @param \Vibalco\MainBundle\Entity\AntiqueCar $cars
     */
    public function removeCar(\Vibalco\MainBundle\Entity\AntiqueCar $cars)
    {
        $this->cars->removeElement($cars);
    }

    /**
     * Get cars
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCars()
    {
        return $this->cars;
    }
    
    public function __toString() {
        return $this->name;
    }
}