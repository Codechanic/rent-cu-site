<?php

namespace Vibalco\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Promo
 * 
 * @ORM\Entity
 * 
 */
class PromoDiscount extends Promo 
{    
    /**
     * @ORM\Column(name="discount", type="float", nullable=true)
     */
    protected $discount;
    
    /**
     * @ORM\Column(name="price", type="float", nullable=true)
     */
    protected $price;

    /**
     * Set discount
     *
     * @param float $discount
     * @return Promo
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    
        return $this;
    }

    /**
     * Get discount
     *
     * @return float 
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return Promo
     */
    public function setPrice($price)
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }
}