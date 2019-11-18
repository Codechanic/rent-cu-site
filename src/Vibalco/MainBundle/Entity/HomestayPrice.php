<?php

namespace Vibalco\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vibalco\MainBundle\Model\CodeInterface;

/**
 * HomestayPrice
 * 
 * @ORM\Table(name="homestay_price")
 * @ORM\Entity
 */
class HomestayPrice implements CodeInterface {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="code", type="string", length=255) 
     */
    private $code;

    /**
     * @ORM\ManyToOne(targetEntity="Season", inversedBy="homestay_prices")
     * @ORM\JoinColumn(name="season_id", referencedColumnName="id")
     */
    private $season;

    /**
     * @ORM\ManyToOne(targetEntity="Homestay", inversedBy="prices")
     * @ORM\JoinColumn(name="homestay_id", referencedColumnName="id")
     */
    private $homestay;

    public function __construct($season, $homestay, $price = null) {
        $this->season = $season;
        $this->homestay = $homestay;
        
        $this->code = $homestay->code() . $season->code();
        
        if (null === $price) {
            $this->price = 0.00;
        } else {
            $this->price = $price;
        }
    }

    public function __toString() {
        return $this->price;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }
    
    public function code() {
        return $this->code;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return HomestayPrice
     */
    public function setPrice($price) {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return HomestayPrice
     */
    public function setCode($code) {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * Set season
     *
     * @param \Vibalco\MainBundle\Entity\Season $season
     * @return HomestayPrice
     */
    public function setSeason(\Vibalco\MainBundle\Entity\Season $season = null) {
        $this->season = $season;

        return $this;
    }

    /**
     * Get season
     *
     * @return \Vibalco\MainBundle\Entity\Season 
     */
    public function getSeason() {
        return $this->season;
    }

    /**
     * Set homestay
     *
     * @param \Vibalco\MainBundle\Entity\Homestay $homestay
     * @return HomestayPrice
     */
    public function setHomestay(\Vibalco\MainBundle\Entity\Homestay $homestay = null) {
        $this->homestay = $homestay;

        return $this;
    }

    /**
     * Get homestay
     *
     * @return \Vibalco\MainBundle\Entity\Homestay 
     */
    public function getHomestay() {
        return $this->homestay;
    }

}
