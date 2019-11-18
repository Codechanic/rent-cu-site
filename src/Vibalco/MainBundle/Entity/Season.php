<?php

namespace Vibalco\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vibalco\MainBundle\Model\CodeInterface;

/**
 * Season
 * 
 * @ORM\Table(name="season")
 * @ORM\Entity
 */
class Season implements CodeInterface {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * 
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="SeasonRange", mappedBy="season", cascade={"persist", "remove"})
     * @ORM\OrderBy({"start" = "ASC"})
     * @Assert\Valid(traverse="true")
     */
    protected $ranges;

    /**
     * @ORM\OneToMany(targetEntity="HomestayPrice", mappedBy="season", cascade={"remove"})
     */
    protected $homestay_prices;    
    
    public function __construct() {
        $this->ranges = new \Doctrine\Common\Collections\ArrayCollection();
        $this->homestay_prices = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->name;
    }

    public function code() {
        return "#S" . $this->id;
    }
    
    /**
     * @Assert\True(message="Los rangos de fecha no deben colisionar")
     */
    public function isRangesValid() {
        foreach ($this->ranges as $r1) {
            if($r1 == null) {
                continue;
            }
            foreach ($this->ranges as $r2) {
                if($r2 == null) {
                    continue;
                }
                if($r1 !== $r2 && $this->rangeCollide($r1, $r2)) {
                    return false;
                }
            }
        }
        return true;
    }
    
    private function rangeCollide(SeasonRange $r1, SeasonRange $r2) {
        $r1ini = $r1->getStart();
        $r1end = $r1->getEnd();
        
        $r2ini = $r2->getStart();
        $r2end = $r2->getEnd();
        
        return $this->inRange($r1ini, $r1end, $r2ini) || $this->inRange($r1ini, $r1end, $r2end);
    }
    
    private function inRange(\DateTime $start, \DateTime $end, \DateTime $date) {        
        return ($start <= $date && $date <= $end);
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
     * @return Season
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
     * Add ranges
     *
     * @param \Vibalco\MainBundle\Entity\SeasonRange $ranges
     * @return Season
     */
    public function addRange(\Vibalco\MainBundle\Entity\SeasonRange $range) {
        $this->ranges[] = $range;

        return $this;
    }

    /**
     * Remove ranges
     *
     * @param \Vibalco\MainBundle\Entity\SeasonRange $ranges
     */
    public function removeRange(\Vibalco\MainBundle\Entity\SeasonRange $range) {
        $this->ranges->removeElement($range);
    }

    /**
     * Get ranges
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRanges() {
        return $this->ranges;
    }

    /**
     * Add HomestayPrices
     *
     * @param \Vibalco\MainBundle\Entity\HomestayPrice $homestayPrices
     * @return Season
     */
    public function addHomestayPrice(\Vibalco\MainBundle\Entity\HomestayPrice $homestayPrices) {
        $this->homestay_prices[] = $homestayPrices;

        return $this;
    }

    /**
     * Remove homestayPrices
     *
     * @param \Vibalco\MainBundle\Entity\HomestayPrice $homestayPrices
     */
    public function removeHomestayPrice(\Vibalco\MainBundle\Entity\HomestayPrice $homestayPrices) {
        $this->homestay_prices->removeElement($homestayPrices);
    }

    /**
     * Get homestayPrices
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHomestayPrices() {
        return $this->homestay_prices;
    }
}