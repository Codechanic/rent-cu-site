<?php

namespace Vibalco\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Promo
 * 
 * @ORM\Entity
 * 
 */
class PromoCover extends Promo 
{    
    /**
     * @var boolean
     *
     * @ORM\Column(name="isnew", type="boolean", nullable=true)
     */
    private $isnew;
    
    /**
     * Set isnew
     *
     * @param boolean $isnew
     * @return Promo
     */
    public function setIsnew($isnew)
    {
        $this->isnew = $isnew;
    
        return $this;
    }

    /**
     * Get isnew
     *
     * @return boolean 
     */
    public function getIsnew()
    {
        return $this->isnew;
    }
}